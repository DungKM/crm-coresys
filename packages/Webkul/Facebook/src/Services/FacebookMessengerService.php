<?php

namespace Webkul\Facebook\Services;

use Webkul\Facebook\Contracts\FacebookMessengerContract;
use Illuminate\Support\Facades\Http;

class FacebookMessengerService implements FacebookMessengerContract
{
    public function sendText(string $psid, string $text): array
    {
        $token = config('services.facebook.page_token');

        $payload = [
            'recipient' => ['id' => $psid],
            'message'   => ['text' => $text],
        ];

        $res = Http::post(
            "https://graph.facebook.com/v19.0/me/messages?access_token={$token}",
            $payload
        );

        return $res->json();
    }
}