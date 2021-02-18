<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * @property integer                                  id
 * @property string                                   username
 * @property uuid                                     uuid
 * @property string                                   bio
 * @property string                                   token
 * @property string                                   password
 * @property \Carbon\Carbon                           created_at
 * @property \Carbon\Carbon                           update_at
 * @property \Illuminate\Database\Eloquent\Collection followings Users who are followed by this user
 */
class User extends Model
{
    protected $fillable = [
        'username',
        'password',
        'uuid'
    ];

    protected $hidden = [
        'password',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user ->uuid =Uuid::uuid4();
        });
    }
}
