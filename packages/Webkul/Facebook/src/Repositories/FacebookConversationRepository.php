<?php

namespace Webkul\Facebook\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Facebook\Models\FacebookConversation;

class FacebookConversationRepository extends Repository
{
    public function model(): string
    {
        return FacebookConversation::class;
    }

    public function getByPsid(string $psid)
    {
        return $this->findOneByField('psid', $psid);
    }

    public function firstOrCreateByPsid(string $psid)
    {
        return $this->model->firstOrCreate(['psid' => $psid]);
    }

    public function markRead(string $psid): void
    {
        $this->model->where('psid', $psid)->update(['unread' => false]);
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