<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends ApiRequest
{
    public function rules()
    {

        return [
            "first_name" => [
                'required',
                'string',
            ],
            "last_name" => [
                'required',
                'string',
            ],
            "gender" => [
                'required',
                'in:male,female,not-selected',
            ],
            "bio" => [
                'string',
            ],
            "website" => [
                'url',
            ],
            "email" => [
                'required',
                'email',
                'unique:users'.\Auth::id()
            ],
            "mobile" => [
                'required',
                'iran_mobile',
                'unique:users'.\Auth::id()
            ],
            "phone" => [
                'iran_phone',
            ],
            "fax" => [
                'iran_phone',
            ],

            "city_id" => [
                'integer',
                'exists:cities,id',
            ],
            "address" => [
                'required',
                'string',
            ],
            'latitude' => [
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'
            ],
            'longitude' => [
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'
            ]
        ];

    }

    public function messages()
    {
        return [

        ];
    }
}
