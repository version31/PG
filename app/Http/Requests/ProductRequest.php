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

                    'price' => [
                        'integer',
                    ],

                    'shipping_others_day' => [
                        'required',
                        'integer',
                    ],
                    'shipping_others_price' => [
                        'required',
                        'integer',
                    ],
                    'shipping_tehran_day' => [
                        'required',
                        'integer',
                    ],
                    'shipping_tehran_price' => [
                        'required',
                        'integer',
                    ],

                    'description' => [
                        'required',
                        'string',
                        'min:6',
                        'max:1000',
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
