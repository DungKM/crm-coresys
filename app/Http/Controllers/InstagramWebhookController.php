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
        
        if ($request->isMethod('get')) {
            $mode      = $request->query('hub_mode');
            $token     = $request->query('hub_verify_token');
            $challenge = $request->query('hub_challenge');

            if ($mode === 'subscribe' && $token === env('IG_VERIFY_TOKEN')) {
                return response($challenge, 200);
            }

            return response('Invalid verify token', 403);
        }
        $payload = $request->all();
        logger()->info('IG_WEBHOOK_RAW', $payload);

        foreach (($payload['entry'] ?? []) as $entry) {
            foreach (($entry['messaging'] ?? []) as $event) {
                if (($event['messaging_product'] ?? null) && $event['messaging_product'] !== 'instagram') {
                    continue;
                }

                if (!empty($event['message']['is_echo'])) {
                    continue;
                }

                if (empty($event['message'])) {
                    continue;
                }

                $igUserId = data_get($event, 'sender.id');
                if (!$igUserId) continue;

                $text = data_get($event, 'message.text');
                $mid  = data_get($event, 'message.mid') ?: data_get($event, 'message.id'); // tuỳ payload
                $ts   = data_get($event, 'timestamp');

                $sentAt = $ts ? Carbon::createFromTimestampMs((int) $ts) : now();

                $convo = InstagramConversation::firstOrCreate(
                    ['ig_user_id' => (string)$igUserId],
                    ['unread' => true]
                );
                if ($mid && InstagramMessage::where('ig_mid', $mid)->exists()) {
                    continue;
                }
                InstagramMessage::create([
                    'conversation_id' => $convo->id,
                    'direction'       => 'in',
                    'text'            => $text,
                    'ig_mid'          => $mid,
                    'raw'             => $event,
                    'sent_at'         => $sentAt,
                ]);
                $convo->update([
                    'unread'       => true,
                    'last_snippet' => $text ? mb_strimwidth($text, 0, 80, '…') : 'New message',
                    'last_time'    => $sentAt,
                ]);
            }
        }

        return response('EVENT_RECEIVED', 200);
    }

    
    private function fetchIgProfile(string $igUserId): ?array
    {
        $token = env('IG_PAGE_TOKEN');
        if (!$token) return null;

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
}