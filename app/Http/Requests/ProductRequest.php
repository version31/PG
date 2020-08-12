<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends ApiRequest
{

    public function rules()
    {

        $addition = [];

        $media_path = [
            'required',
            'max:8000000',
        ];

        switch ($this->type) {
            case "video":

                $addition = [
                    'mimes:mp4,mov,ogg,qt'
                ];
                break;
            case "picture":
                $addition = [
                    'mimes:jpeg,png,jpg,gif,svg',
                ];
                break;
        }

        return [
            'type' => [
                'required',
                'in:picture,video'
            ],
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
            'media_path' => array_merge($media_path, $addition),

            'addables.*' => $addition,
        ];

    }


}
