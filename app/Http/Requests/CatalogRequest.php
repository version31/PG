<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CatalogRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'file' => [
                'required',
                'mimes:pdf',
                'max:10000',
            ],
            'title' => [
                'string'
            ],
            'description' => [
                'string'
            ],

        ];

    }

    public function messages()
    {
        return [

        ];
    }
}
