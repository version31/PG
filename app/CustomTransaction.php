<?php

namespace App;

use App\Sh4\Sh4HasPagination;
use Bavix\Wallet\Models\Transaction;

class CustomTransaction extends Transaction
{
    use Sh4HasPagination;


    public function scopeSelected($query)
    {
        return $query->select([
            'transactions.created_at','transactions.id','transactions.type','transactions.amount','transactions.meta',
//            'users.mobile','users.first_name','users.last_name',
            'users.id as user__id',
            'transfers.to_id','transfers.from_id','payable_id'
        ]);
    }
}
