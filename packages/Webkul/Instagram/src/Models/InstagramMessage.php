<?php

namespace Webkul\Instagram\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstagramMessage extends Model
{
    protected $table = 'instagram_messages';

    protected $fillable = [
        'conversation_id',
        'direction',
        'text',
        'ig_mid',
        'raw',
        'sent_at',
    ];

    protected $casts = [
        'raw'     => 'array',
        'sent_at'=> 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(InstagramConversation::class, 'conversation_id');
    }
}