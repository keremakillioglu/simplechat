<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'message_id',
        'read_at',
    ];
}
