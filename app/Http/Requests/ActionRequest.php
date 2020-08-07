<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActionRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'act' => [
                'required'
            ],
            'id' => [
                'required'
            ]

        ];

    }

    public function messages()
    {
        return [

        ];
    }
}
