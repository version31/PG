<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowStatusResource extends V2Resource
{
    public function toArray($request)
    {

        $created = new Carbon($this->created_at);

        return [
            "registered_product" => $this->count_product,
            "extant_product" => $this->limit_insert_product,
            "total_product" => $this->limit_insert_product + $this->count_product,
            "created_day" => $created->diffInDays(Carbon::now()),
            "balance" => (int) $this->wallet->balance,
        ];
    }
}
