<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'gateway_transactions';

    protected $fillable = ['user_id' , 'details' , 'plan_id','related_id'];

    protected $casts = [
        'details' => 'array',
    ];


}
