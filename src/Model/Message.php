<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Message extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'body',
        'uuid'
    ];

    protected $hidden = [
        'id',
        'sender_id',
        'receiver_id',
        'uuid'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($message) {
            $message ->uuid =Uuid::uuid4();
        });
    }
}
