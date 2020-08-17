<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresentableFieldRequest extends ApiRequest
{
    public function rules()
    {

        return [
            "presentable_fields" => [
                'required',
                'array',
                'min:1',
                'in:bio,website,email,mobile,phone,fax,address',
            ],
        ];

    }


}
