<?php

namespace App\Http\Requests;


class ReportRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'type' => [
                'required',
                'string'
            ],
            'mobile' => [
                'string'
            ],
            'body' => [
                'string'
            ],
            'id' => [
                'required'
            ]

        ];

    }

}
