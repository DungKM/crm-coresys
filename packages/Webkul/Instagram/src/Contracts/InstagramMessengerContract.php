<?php

namespace Webkul\Instagram\Contracts;

interface InstagramMessengerContract
{
    public function sendText(string $igUserId, string $text): array;
}