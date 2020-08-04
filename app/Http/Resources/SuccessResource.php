<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{

    public $message;

    public function __construct( $message = "رکورد با موفقیت اضافه شد")
    {
        $this->message = $message;
    }



    public function with($request)
    {
        return [
            'message' => $this->message,
        ];
    }
}
