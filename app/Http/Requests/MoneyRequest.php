<?php

namespace App\Http\Requests;

use App\Rules\MultipleRule;
use Illuminate\Foundation\Http\FormRequest;

class MoneyRequest extends ApiRequest
{
    public function rules()
    {
        return [
            "card_number" => [
                'required',
                'card_number',
            ],
            "price" => [
                'required',
                'numeric',
                new MultipleRule(50000)
            ],

            "message" => [
                "string"
            ]
        ];
    }

}
