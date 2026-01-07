<?php

namespace Webkul\Facebook\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Facebook\Models\FacebookMessage;

class FacebookMessageRepository extends Repository
{
    public function model(): string
    {
        return FacebookMessage::class;
    }

    public function storeIncoming(int $conversationId, array $data)
    {
        return $this->create([
            'conversation_id' => $conversationId,
            'direction'       => 'in',
            ...$data,
        ]);
    }

    public function storeOutgoing(int $conversationId, array $data)
    {
        return $this->create([
            'conversation_id' => $conversationId,
            'direction'       => 'out',
            ...$data,
        ]);
    }
}