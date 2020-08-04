<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MoneyRequests extends Model
{
    protected $fillable = [
        'user_id',
        'price',
        'card_number',
        'message',
        'status',
    ];
}
