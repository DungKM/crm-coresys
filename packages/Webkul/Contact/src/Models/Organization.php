<?php

namespace Webkul\Contact\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Attribute\Traits\CustomAttribute;
use Webkul\Contact\Contracts\Organization as OrganizationContract;
use Webkul\User\Models\UserProxy;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model implements OrganizationContract
{
    use CustomAttribute, HasFactory;
=======

class Organization extends Model implements OrganizationContract
{
    use CustomAttribute;
>>>>>>> upstream/main

    protected $casts = [
        'address' => 'array',
    ];

<<<<<<< HEAD
=======
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
>>>>>>> upstream/main
    protected $fillable = [
        'name',
        'address',
        'user_id',
    ];

<<<<<<< HEAD
=======
    /**
     * Get persons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
>>>>>>> upstream/main
    public function persons()
    {
        return $this->hasMany(PersonProxy::modelClass());
    }

<<<<<<< HEAD
=======
    /**
     * Get the user that owns the lead.
     */
>>>>>>> upstream/main
    public function user()
    {
        return $this->belongsTo(UserProxy::modelClass());
    }
<<<<<<< HEAD

    protected static function newFactory()
    {
        return \Database\Factories\OrganizationFactory::new();
    }
=======
>>>>>>> upstream/main
}
