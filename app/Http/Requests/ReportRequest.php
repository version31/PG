<?php

namespace App\Http\Requests;


class ReportRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'body' => [
                'required',
                'string'
            ],
            'id' => [
                'required'
            ]

        ];

    }

}
