<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AfterLoginResource extends V2Resource
{
    public function toArray($request)
    {

        return [
            "id" => $this->id,
            "token" => $this->token,
            "user_registered" => $this->user_registered,
            "status" => $this->status,
            "mobile" => $this->mobile,
            'roles' =>   $this->roles,
        ];
    }
}
