<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use Webkul\Instagram\Models\InstagramConversation;
use Webkul\Instagram\Models\InstagramMessage;

class InstagramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        
        // 1) VERIFY WEBHOOK (GET)
        if ($request->isMethod('get')) {
            $mode      = $request->query('hub_mode');
            $token     = $request->query('hub_verify_token');
            $challenge = $request->query('hub_challenge');

            if ($mode === 'subscribe' && $token === env('IG_VERIFY_TOKEN')) {
                return response($challenge, 200);
            }

            return response('Invalid verify token', 403);
        }

        // 2) RECEIVE EVENT (POST)
        $payload = $request->all();
        logger()->info('IG_WEBHOOK_RAW', $payload);

        foreach (($payload['entry'] ?? []) as $entry) {
            // IG messaging thường nằm ở entry[].messaging[]
            foreach (($entry['messaging'] ?? []) as $event) {

                // Chỉ xử lý sự kiện IG messaging
                // Một số payload có "messaging_product": "instagram"
                if (($event['messaging_product'] ?? null) && $event['messaging_product'] !== 'instagram') {
                    continue;
                }

                // bỏ echo (tin do page/ig gửi ra)
                if (!empty($event['message']['is_echo'])) {
                    continue;
                }

                // chỉ xử lý message
                if (empty($event['message'])) {
                    continue;
                }

                // IG: sender.id là user IG (scoped)
                $igUserId = data_get($event, 'sender.id');
                if (!$igUserId) continue;

                $text = data_get($event, 'message.text');
                $mid  = data_get($event, 'message.mid') ?: data_get($event, 'message.id'); // tuỳ payload
                $ts   = data_get($event, 'timestamp');

                $sentAt = $ts ? Carbon::createFromTimestampMs((int) $ts) : now();

                // 2.1) Upsert conversation
                $convo = InstagramConversation::firstOrCreate(
                    ['ig_user_id' => (string)$igUserId],
                    ['unread' => true]
                );

                // 2.2) chống trùng khi Meta retry webhook
                if ($mid && InstagramMessage::where('ig_mid', $mid)->exists()) {
                    continue;
                }

                // 2.3) Save message
                InstagramMessage::create([
                    'conversation_id' => $convo->id,
                    'direction'       => 'in',
                    'text'            => $text,
                    'ig_mid'          => $mid,
                    'raw'             => $event,
                    'sent_at'         => $sentAt,
                ]);

                // 2.4) Update convo preview
                $convo->update([
                    'unread'       => true,
                    'last_snippet' => $text ? mb_strimwidth($text, 0, 80, '…') : 'New message',
                    'last_time'    => $sentAt,
                ]);

                // 2.5) (optional) cập nhật name/avatar nếu bạn muốn
                // $this->ensureConversationProfile($convo);
            }
        }

        return response('EVENT_RECEIVED', 200);
    }

    /**
     * OPTIONAL: fetch profile (chỉ làm được nếu Graph cho phép với token + scope phù hợp)
     * Tuỳ case IG có thể KHÔNG trả avatar trực tiếp như FB profile_pic.
     */
    private function fetchIgProfile(string $igUserId): ?array
    {
        $token = env('IG_PAGE_TOKEN'); // thường là PAGE access token có quyền ig messaging
        if (!$token) return null;

        // NOTE: endpoint/fields có thể khác tuỳ loại token & mode app.
        // Nếu call fail thì bạn log lại để biết thiếu permission/endpoint.
        $res = Http::timeout(10)->get("https://graph.facebook.com/v19.0/{$igUserId}", [
            'fields' => 'name,profile_pic', // có thể không support
            'access_token' => $token,
        ]);

        if (!$res->successful()) {
            logger()->warning('IG_PROFILE_FETCH_FAILED', [
                'ig_user_id' => $igUserId,
                'status' => $res->status(),
                'body' => $res->body(),
            ]);
            return null;
        }

        $d = $res->json();

        return [
            'name'   => $d['name'] ?? null,
            'avatar' => $d['profile_pic'] ?? null,
        ];
    }

    // private function ensureConversationProfile(InstagramConversation $convo): void
    // {
    //     if (!empty($convo->name) && !empty($convo->avatar) && str_starts_with((string)$convo->avatar, 'http')) {
    //         return;
    //     }
    //     $profile = $this->fetchIgProfile($convo->ig_user_id);
    //     if (!$profile) return;
    //     $updates = [];
    //     if (!empty($profile['name'])) $updates['name'] = $profile['name'];
    //     if (!empty($profile['avatar'])) $updates['avatar'] = $profile['avatar'];
    //     if ($updates) $convo->update($updates);
    // }
}