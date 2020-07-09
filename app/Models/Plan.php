<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['extra','day','price','type', 'limit_insert_video', 'limit_insert_product'];

    public function gateway_transactions()
    {
        return $this->hasMany('App\Models\GatewayTransaction');
    }
}
