<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class ShopRequest extends ApiRequest
{




    public function rules()
    {

        return [
            //
            'products' => [
                'required',
                'array',
                'max:' . \Auth::user()->maximum_product_on_shop
            ],
            'products.*.product_id' => [
                'required',
                'exists:products,id',
            ],
            'products.*.new_price' => [
                'required',
                'integer'
            ],
        ];
    }
}
