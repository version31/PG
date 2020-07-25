<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProviderCollection extends V2Resource
{

    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "email" => $this->email,
            "mobile" => $this->mobile,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "name" => $this->first_name.' '.$this->last_name,
            "shop_name" => $this->shop_name,
            "avatar" => $this->avatar,
            "website" => $this->website,
            "bio" => $this->bio,
            "gender" => $this->gender,
            "phone" => $this->phone,
            "fax" => $this->fax,
            "address" => $this->address,
            "count_product" => $this->count_product,
            "count_like" => $this->count_like,
            "longitude" => $this->longitude,
            "latitude" => $this->latitude,
            "shop_expired_at" => $this->shop_expired_at,
            "stars" => $this->stars,
        ];
    }
}
