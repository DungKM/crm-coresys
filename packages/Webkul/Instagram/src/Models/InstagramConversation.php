<?php

namespace Webkul\Instagram\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstagramConversation extends Model
{
    protected $table = 'instagram_conversation';

    protected $fillable = [
        'ig_user_id',
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
        return $this->hasMany(InstagramMessage::class, 'conversation_id');
    }
}