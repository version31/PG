<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends ApiRequest
{
    public function rules()
    {
        return [
            "mobile" => [
                'required',
                'iran_mobile',
                'unique:users'
            ],
            "password" => [
                'required',
                'string',
            ]
        ];
    }
}
