<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MediaCollection extends JsonResource
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
            "mime_type" => $this->mime_type,
            "url" => $this->getUrl(),
        ];
    }


    public function getUrl()
    {
        $url = '/storage/';
        $url .= $this->id;
        $url .= '/';
        $url .= $this->file_name;
        return $url;
    }



}
