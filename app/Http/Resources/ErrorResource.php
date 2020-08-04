<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{

    public $errors;
    public $statusCode;

    public function __construct( $errors, $statusCode = 422)
    {
        $this->errors = $errors;
        $this->statusCode = $statusCode;
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->statusCode, 'Precondition Required');
    }

    public function with($request)
    {
        return [
            'errors' => $this->errors,
        ];
    }
}
