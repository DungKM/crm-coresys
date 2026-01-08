<?php

namespace Webkul\Instagram\Services;

use Webkul\Instagram\Contracts\InstagramMessengerContract;
use Illuminate\Support\Facades\Http;

class InstagramMessengerService implements InstagramMessengerContract
{
    public function sendText(string $igUserId, string $text): array
    {
        $token = env('IG_PAGE_TOKEN'); 
        // ⚠ IG dùng chung Page token với FB (Page connect IG)

        $payload = [
            'recipient' => ['id' => $igUserId],
            'message'   => ['text' => $text],
        ];

        $res = Http::post(
            "https://graph.facebook.com/v19.0/me/messages?access_token={$token}",
            $payload
        );

        return $res->json();
    }
}