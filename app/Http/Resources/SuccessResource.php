<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{

    public $message;
    public $data;

    public function __construct($data = [], $message = "عملیات با موفقیت انجام شد")
    {
        $this->message = $message;
        $this->data = $data;
    }


    public function toArray($request)
    {
        $result = [];

        if (isset($this->data->id))
            $result['id'] = $this->data->id;

        if (isset($this->data->created_at))
            $result['created_at'] = $this->data->created_at;

        return $result;
    }


    public function with($request)
    {
        return [
            'message' => $this->message,
        ];
    }
}
