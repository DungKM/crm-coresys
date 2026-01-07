<?php

namespace Webkul\Facebook\Contracts;

interface FacebookMessengerContract
{
    public function sendText(string $psid, string $text): array;
}