<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OnSaleCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "count_like" => $this->count_like,
            "count_visit" => $this->count_visit,
            "media_path" => $this->media_path,
            "price" => $this->price,
            "new_price" => $this->new_price,
            "shipping" => $this->shipping,
            "media" => MediaCollection::collection($this->whenLoaded('media')),
            "user" => new UserResource($this->whenLoaded('user')),
        ];
    }
}
