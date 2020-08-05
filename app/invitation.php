<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class invitation extends Model
{
    //

    protected $fillable = [
        'gift_status',
        'sms_status',
        'register_status',
        'mobile',
        'user_id',
    ];
}
