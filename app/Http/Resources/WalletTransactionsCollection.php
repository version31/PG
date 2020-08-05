<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WalletTransactionsCollection extends V2Resource
{

    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "created_at" => $this->created_at,
//            "type" => $this->type,
            "amount" => $this->amount,
            "description" => $this->meta[0] ?? null,

        ];
    }
}
