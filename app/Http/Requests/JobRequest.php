<?php

namespace App\Http\Requests;


class JobRequest extends ApiRequest
{
    public function rules()
    {
        return [
            "title" => [
                'required',
                'string',
            ],
            "description" => [
                'required',
                'string',
            ],
            "type" => [
                'required',
                'in:FREELANCE,FULL-TIME,INTERNSHIP,PART-TIME,TEMPORARY,VOLUNTEER',
            ],
            "category_id" => [
                'required',
                'exists:categories,id',
            ],
            "offered_salary" => [
                'required',
                'integer'
            ],
            "level" => [
                'required',
                'integer'
            ],
            "minimum_experience" => [
                'required',
                'integer'
            ],
            "gender" => [
                'gender' => 'required',
                'in:NOT-SELECTED,MALE,FEMALE',
            ],
            "expired_at" => [
                'required',
                'date_format:Y-m-d',
                'after:today'
            ],
            "tags.*" => [
                'required',
                'exists:tags,id',
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
}
