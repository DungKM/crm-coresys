<?php

namespace Webkul\Facebook\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FacebookConversation extends Model
{
    protected $table = 'facebook_conversations';

    protected $fillable = [
        'psid',
        'name',
        'avatar',
        'unread',
        'last_snippet',
        'last_time',
    ];

    protected $casts = [
        'unread'    => 'boolean',
        'last_time' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(FacebookMessage::class, 'conversation_id');
    }
}