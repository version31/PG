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
        return $this->data;
    }


    public function with($request)
    {
        return [
            'message' => $this->message,
        ];
    }
}
