<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvitationRequest extends ApiRequest
{
    public function rules()
    {
        return [
            "mobile" => [
                'required',
                'iran_mobile',
                'unique:users',
                'unique:invitations',
            ],
        ];

    }

    public function messages()
    {
        return [
            'mobile.unique' => 'شماره همراه قبلا ثبت نام یا دعوت شده است',
        ];
    }

}
