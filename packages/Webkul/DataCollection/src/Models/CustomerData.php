<?php

namespace Webkul\DataCollection\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerData extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customer_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'title',
        'customer_type',
        'source',
        'status',
        'verify_token',
        'last_assigned_to',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
