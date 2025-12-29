<?php

namespace Webkul\Instagram\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Instagram\Models\InstagramConversation;

class InstagramConversationRepository extends Repository
{
    public function model(): string
    {
        return InstagramConversation::class;
    }

    public function getByUser(string $igUserId)
    {
        return $this->findOneByField('ig_user_id', $igUserId);
    }

    public function firstOrCreateByUser(string $igUserId)
    {
        return $this->model->firstOrCreate(['ig_user_id' => $igUserId]);
    }

    public function markRead(string $igUserId): void
    {
        $this->model->where('ig_user_id', $igUserId)->update(['unread' => false]);
    }

    public function list(string $q = '')
    {
        $query = $this->model->orderByDesc('last_time');

        if ($q) {
            $query->where('last_snippet', 'like', "%{$q}%");
        }

        return $query->get();
    }
}