<?php

namespace App\Http\Requests;

use App\Rules\MultipleRule;
use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends ApiRequest
{
    public function rules()
    {
        return [
            "mobile" => [
                'required',
                'iran_mobile',
                'exists:users'
            ],
            "price" => [
                'required',
                'numeric',
                new MultipleRule(10000)
            ],
        ];
    }
}
