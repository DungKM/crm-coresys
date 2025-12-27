<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Facebook\Models\FacebookConversation;
use Webkul\Facebook\Models\FacebookMessage;
use Carbon\Carbon;

class FacebookWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1) VERIFY WEBHOOK (GET)
        if ($request->isMethod('get')) {
           $mode      = $request->query('hub_mode');
           $token     = $request->query('hub_verify_token');
           $challenge = $request->query('hub_challenge');

            if ($mode === 'subscribe' && $token === env('FB_VERIFY_TOKEN')) {
                return response($challenge, 200);
            }

            return response('Invalid verify token', 403);
        }

        // 2) RECEIVE EVENT (POST)
        $payload = $request->all();
        logger()->info('FB_WEBHOOK_RAW', $payload);

        // chỉ xử lý event Messenger (page->messages)
        foreach (($payload['entry'] ?? []) as $entry) {
            foreach (($entry['messaging'] ?? []) as $event) {

                // Bỏ qua echo (tin do page gửi ra cũng bắn về webhook dạng is_echo)
                if (!empty($event['message']['is_echo'])) {
                    continue;
                }

                // Chỉ xử lý message text (có thể mở rộng attachments sau)
                if (empty($event['message'])) {
                    continue;
                }

                $psid = data_get($event, 'sender.id');
                if (!$psid) continue;

                $text = data_get($event, 'message.text');
                $mid  = data_get($event, 'message.mid');
                $ts   = data_get($event, 'timestamp');

                $sentAt = $ts ? Carbon::createFromTimestampMs((int) $ts) : now();

                // 2.1) Upsert conversation
                $convo = FacebookConversation::firstOrCreate(
                    ['psid' => $psid],
                    ['unread' => true]
                );

                // 2.2) Chống trùng khi Facebook retry webhook
                if ($mid && FacebookMessage::where('fb_mid', $mid)->exists()) {
                    continue;
                }

                // 2.3) Save message
                FacebookMessage::create([
                    'conversation_id' => $convo->id,
                    'direction'       => 'in',
                    'text'            => $text,
                    'fb_mid'          => $mid,
                    'raw'             => $event,
                    'sent_at'         => $sentAt,
                ]);

                // 2.4) Update convo preview
                $convo->update([
                    'unread'       => true,
                    'last_snippet' => $text ? mb_strimwidth($text, 0, 80, '…') : 'New message',
                    'last_time'    => $sentAt,
                ]);
            }
        }

        return response('EVENT_RECEIVED', 200);
    }

    private function fetchFbProfile(string $psid): ?array
{
    $pageToken = env('FB_PAGE_TOKEN'); // Page Access Token
    if (!$pageToken) return null;

    $res = Http::timeout(10)->get("https://graph.facebook.com/v19.0/{$psid}", [
        'fields' => 'first_name,last_name,profile_pic',
        'access_token' => $pageToken,
    ]);

    if (!$res->successful()) {
        logger()->warning('FB_PROFILE_FETCH_FAILED', [
            'psid' => $psid,
            'status' => $res->status(),
            'body' => $res->body(),
        ]);
        return null;
    }

    $d = $res->json();
    $name = trim(($d['first_name'] ?? '') . ' ' . ($d['last_name'] ?? ''));

    return [
        'name'   => $name !== '' ? $name : null,
        'avatar' => $d['profile_pic'] ?? null,
    ];
}

    private function ensureConversationProfile(FacebookConversation $convo): void
    {
        // Nếu đã có tên rồi thì thôi (hoặc bạn có thể refresh theo ngày sau)
        if (!empty($convo->name) && !empty($convo->avatar) && str_starts_with((string)$convo->avatar, 'http')) {
            return;
        }

        $profile = $this->fetchFbProfile($convo->psid);
        if (!$profile) return;

        $updates = [];
        if (!empty($profile['name']))   $updates['name'] = $profile['name'];
        if (!empty($profile['avatar'])) $updates['avatar'] = $profile['avatar'];

        if ($updates) $convo->update($updates);
    }

}