<?php

namespace Webkul\Instagram\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Instagram\Models\InstagramMessage;

class InstagramMessageRepository extends Repository
{
    public function model(): string
    {
        return InstagramMessage::class;
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