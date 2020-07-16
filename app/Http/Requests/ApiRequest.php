<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class ApiRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }



    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new JsonResponse([
            'status' => false,
            'errors' => $validator->errors(),
            'data' => null,
        ] , 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
