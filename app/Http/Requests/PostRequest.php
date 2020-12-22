<?php

namespace App\Http\Requests;

use App\Rules\MultipleRule;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'title' => [
                'required',
                'string',
                'min:6',
                'max:200',
            ],

            'body' => [
                'required',
                'string',
                'min:6',
                'max:1000',
            ],

            'study_time' => [
                'integer',
            ],

            'categories' => [
                'array',
                'max:3'
            ],
            'categories.*' => [
                'exists:categories,id'
            ],

            'tags' => [
                'array'
            ],
            'tags.*' => [
                'exists:tags,id'
            ],
        ];
    }
}
