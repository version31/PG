<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OnSaleRequest extends ApiRequest
{

    public function rules()
    {

        switch ($this->method()) {
            case 'POST':
                return [
                    'new_price' => [
                        'integer',
                    ],

                    'published_at' => [
                        'required',
                        'date_format:"Y-m-d"',
                        "after:yesterday"
                    ],

                    'product_id' => [
                        'required',
//                        'exists:products',
                    ],

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
