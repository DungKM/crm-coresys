<?php

namespace Webkul\Admin\Http\Controllers\Instagram;

use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Instagram\Models\InstagramConversation;
use Webkul\Instagram\Models\InstagramMessage;
use Webkul\Instagram\Contracts\InstagramMessengerContract;
use Illuminate\Support\Facades\Http;
class InstagramApiController extends Controller
{
    public function conversations()
    {
        return InstagramConversation::orderByDesc('last_time')->get();
    }

    public function messages(Request $request)
    {
        $igUserId = $request->query('ig_user_id');

        $convo = InstagramConversation::where('ig_user_id', $igUserId)->firstOrFail();

        return response()->json([
            'conversation' => $convo,
            'messages' => $convo->messages()->orderBy('sent_at')->get(),
        ]);
    }

    public function send(Request $request)
    {
        $igSid = (string) $request->input('ig_user_id'); // IGSID (id người nhắn)
        $text  = trim((string) $request->input('text'));

        abort_unless($igSid !== '' && $text !== '', 422, 'ig_user_id and text are required');

        $pageToken = env('IG_PAGE_TOKEN'); // Page access token của Page link IG
        abort_unless($pageToken, 500, 'IG_PAGE_TOKEN is not set');

        // 1) đảm bảo có conversation
        $convo = InstagramConversation::firstOrCreate(
            ['ig_user_id' => $igSid],
            ['unread' => false]
        );

        // 2) gọi IG Send API (Messenger API for Instagram)
        $payload = [
            'messaging_type' => 'RESPONSE',
            // 'messaging_product' => 'instagram', // nếu bạn muốn chắc hơn thì bật
            'recipient' => ['id' => $igSid],
            'message'   => ['text' => $text],
        ];

        $res = Http::asJson()->post('https://graph.facebook.com/v20.0/me/messages', $payload + [
            'access_token' => $pageToken,
        ]);

        if (!$res->successful()) {
            return response()->json([
                'message' => 'Instagram send failed',
                'ig'      => $res->json(),
            ], 422);
        }

        $ig = $res->json(); // thường có message_id

        // 3) lưu message out để UI hiện ngay
        $msg = InstagramMessage::create([
            'conversation_id' => $convo->id,
            'direction'       => 'out',
            'text'            => $text,
            'ig_mid'          => $ig['message_id'] ?? null,
            'raw'             => $ig,
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
                'id'   => $msg->id,
                'from' => 'out',
                'text' => $msg->text,
                'at'   => $msg->sent_at?->format('H:i'),
            ],
            'instagram' => $ig,
        ]);
    }

    public function delete(Request $request)
    {
        InstagramConversation::where('ig_user_id', $request->ig_user_id)->delete();
        return response()->json(['ok' => true]);
    }
}