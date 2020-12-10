<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends ApiRequest
{

    public function rules()
    {

        switch ($this->method()) {
            case 'POST':
                return [
                    'title' => [
                        'required',
                        'string',
                        'min:6',
                        'max:200',
                    ],
                    'description' => [
                        'required',
                        'string',
                        'min:6',
                        'max:1000',
                    ],
                    'category_id' => [
                        'required',
                        'exists:categories,id'
                    ],
                    'media_path' => [
                        'required',
                        'max:8000000',
                        'mimes:jpeg,png,jpg,gif,svg',
                    ],

                    'audio' => [
//                        'mimes:mp4,ogg,qt,wav,mp3',
                    ],
                    'media.*' => [
                        'max:8000000',
                        'mimes:mp4,mov,ogg,jpeg,png,jpg,gif,svg',
                    ]


                ];
            case 'PUT':
                return [
                    'description' => [
                        'required',
                        'string',
                        'min:6',
                        'max:1000',
                    ],
                    'title' => [
                        'required',
                        'string',
                        'min:6',
                        'max:200',
                    ],

                ];
            default:
                break;
        }


    }


}
