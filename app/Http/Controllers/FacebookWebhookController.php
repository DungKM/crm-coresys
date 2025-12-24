<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FacebookWebhookController extends Controller
{
     public function handle(Request $request)
    {
        // 1. VERIFY WEBHOOK (GET)
        if ($request->isMethod('get')) {
            $mode = $request->query('hub_mode');
            $token = $request->query('hub_verify_token');
            $challenge = $request->query('hub_challenge');

            if ($mode === 'subscribe' && $token === env('FB_VERIFY_TOKEN')) {
                return response($challenge, 200);
            }

            return response('Invalid verify token', 403);
        }

        // 2. RECEIVE MESSAGE (POST)
        logger()->info('FB_WEBHOOK_RAW', $request->all());

        return response('EVENT_RECEIVED', 200);
    }
private function sendText(string $psid, string $text): void
{
    $token = env('FB_PAGE_TOKEN');

    $payload = [
        'recipient' => ['id' => $psid],
        'message'   => ['text' => $text],
    ];

    \Http::post("https://graph.facebook.com/v19.0/me/messages", $payload + [
        'access_token' => $token,
    ]);
}

}
