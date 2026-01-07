<?php

namespace Webkul\Admin\Http\Controllers\Facebook;

use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Facebook\Models\FacebookConversation;
use Webkul\Facebook\Models\FacebookMessage;
use Illuminate\Support\Facades\Http;

class FacebookApiController extends Controller
{
    public function conversations(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $query = FacebookConversation::query()
            ->orderByDesc('last_time')
            ->orderByDesc('updated_at');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('last_snippet', 'like', "%{$q}%")
                  ->orWhere('psid', 'like', "%{$q}%");
            });
        }

        $rows = $query->limit(100)->get();

        return response()->json($rows->map(fn ($c) => [
            'psid'   => $c->psid,
            'name'   => $c->name ?: $c->psid,
            'avatar' => $c->avatar ?: mb_strtoupper(mb_substr($c->name ?: 'C', 0, 2)),
            'snippet'=> $c->last_snippet ?: '',
            'time'   => $c->last_time ? $c->last_time->diffForHumans() : '',
            'unread' => (bool) $c->unread,
            'type'   => 'dm',
        ]));
    }
    public function deleteConversation(Request $request)
    {
        $psid = (string) $request->input('psid');
        abort_unless($psid !== '', 422, 'psid is required');

        $convo = FacebookConversation::where('psid', $psid)->first();

        // Không có thì coi như xóa xong
        if (!$convo) {
            return response()->json(['ok' => true, 'psid' => $psid]);
        }

        // Xóa toàn bộ message thuộc conversation
        FacebookMessage::where('conversation_id', $convo->id)->delete();

        // Xóa conversation
        $convo->delete();

        return response()->json(['ok' => true, 'psid' => $psid]);
    }


    public function messages(Request $request)
    {
        $psid = (string) $request->query('psid');
        abort_unless($psid, 422, 'psid is required');

        $convo = FacebookConversation::where('psid', $psid)->firstOrFail();

        $msgs = FacebookMessage::where('conversation_id', $convo->id)
            ->orderBy('sent_at')
            ->limit(300)
            ->get();

        return response()->json([
            'conversation' => [
                'psid' => $convo->psid,
                'name' => $convo->name ?: $convo->psid,
                'avatar' => $convo->avatar ?: mb_strtoupper(mb_substr($convo->name ?: 'C', 0, 2)),
            ],
            'messages' => $msgs->map(fn ($m) => [
                'from' => $m->direction === 'out' ? 'out' : 'in',
                'text' => $m->text ?? '',
                'at'   => $m->sent_at ? $m->sent_at->format('H:i') : '',
            ]),
        ]);
    }
    public function send(Request $request)
    {
        $psid = (string) $request->input('psid');
        $text = trim((string) $request->input('text'));

        abort_unless($psid !== '' && $text !== '', 422, 'psid and text are required');

        $pageToken = env('FB_PAGE_TOKEN');
        abort_unless($pageToken, 500, 'FB_PAGE_TOKEN is not set');

        // 1) đảm bảo có conversation
        $convo = FacebookConversation::firstOrCreate(
            ['psid' => $psid],
            ['unread' => false]
        );

        // 2) gọi Facebook Send API
        $payload = [
            'recipient' => ['id' => $psid],
            'message'   => ['text' => $text],
        ];

        $res = Http::asJson()->post('https://graph.facebook.com/v19.0/me/messages', $payload + [
            'access_token' => $pageToken,
        ]);

        if (!$res->successful()) {
            // trả lỗi rõ cho Vue (để không bị Unexpected token '<')
            return response()->json([
                'message' => 'Facebook send failed',
                'fb'      => $res->json(),
            ], 422);
        }

        $fb = $res->json(); // thường có message_id

        // 3) lưu message out để UI hiện ngay
        $msg = FacebookMessage::create([
            'conversation_id' => $convo->id,
            'direction'       => 'out',
            'text'            => $text,
            'fb_mid'          => $fb['message_id'] ?? null,
            'raw'             => $fb,
            'sent_at'         => now(),
        ]);

        // 4) cập nhật preview convo
        $convo->update([
            'unread'       => false,
            'last_snippet' => mb_strimwidth($text, 0, 80, '…'),
            'last_time'    => now(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => [
                'id' => $msg->id,
                'from' => 'out',
                'text' => $msg->text,
                'at' => $msg->sent_at?->format('H:i'),
            ],
            'facebook' => $fb,
        ]);
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
        $name = trim(($d['first_name'] ?? '').' '.($d['last_name'] ?? ''));

        return [
            'name' => $name !== '' ? $name : null,
            'avatar' => $d['profile_pic'] ?? null,
        ];
    }

}