<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GatewayTransaction extends Model
{
    protected $casts = [
        'detail' => 'array',
    ];

    protected $fillable = ['detail','user_id', 'plan_id', 'port', 'price', 'ref_id', 'tracking_code', 'card_number', 'status', 'ip', 'payment_date'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function plan()
    {
        return $this->belongsTo('App\Models\Plan');
    }

    public function gateway_transaction_log()
    {
        return $this->hasOne('App/GatewayTransactionsLog');
    }
}
