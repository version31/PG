<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'gateway_transactions';

    protected $fillable = [
        'port' ,
        'price' ,
        'ref_id',
        'status',
        'ip',
        'user_id',
        ];

    protected $casts = [
        'details' => 'array',
    ];


}
