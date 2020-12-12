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
            "type" => $this->getType(),
            "url" => $this->getUrl(),
        ];
    }


    private function getUrl()
    {
        $url = '/storage/';
        $url .= $this->id;
        $url .= '/';
        $url .= $this->file_name;
        return $url;
    }

    private function getType()
    {
        return explode("/", $this->mime_type)[0];

    }



}
