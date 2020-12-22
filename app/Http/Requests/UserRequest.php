<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

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
                'in:MALE,FEMALE,NOT-SELECTED',
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
                //                'unique:users,'.\Auth::id()
                Rule::unique('users')->ignore(Auth::id())
            ],
            "mobile" => [
                'required',
                //                'iran_mobile',
                //                'unique:users,'.\Auth::id()
                Rule::unique('users')->ignore(Auth::id())
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
                'string',
            ],
            'latitude' => [
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'
            ],
            'longitude' => [
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'
            ]
            ,
            'categories' => [
                'array',
                'max:3'
            ],
            'categories.*' => [
                'exists:categories,id'
            ],


        ];

    }

    public function messages()
    {
        return [

        ];
    }
}
