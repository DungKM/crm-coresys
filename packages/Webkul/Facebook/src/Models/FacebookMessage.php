<?php

namespace Webkul\Facebook\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacebookMessage extends Model
{
    protected $table = 'facebook_messages';

    protected $fillable = [
        'conversation_id',
        'direction',
        'text',
        'fb_mid',
        'raw',
        'sent_at',
    ];

    protected $casts = [
        'raw'     => 'array',
        'sent_at'=> 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(FacebookConversation::class, 'conversation_id');
    }
}