<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'parent_id' => [
                'nullable',
                'exists:comments,id'
            ],
            'title' => [
                'nullable',
                'string',
                'min:6',
                'max:60',
            ],
            'body' => [
                'required',
                'string',
                'min:12',
                'max:600',
            ],
            'positive_items' => [
                'nullable',
                'array'
            ],
            'negative_items' => [
                'nullable',
                'array'
            ],
            'score' => [
                'integer',
                'min:1',
                'max:5',
            ],

        ];

    }

}
