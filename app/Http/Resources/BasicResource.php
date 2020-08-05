<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BasicResource extends V2Resource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
