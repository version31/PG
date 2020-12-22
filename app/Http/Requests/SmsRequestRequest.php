<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmsRequestRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id'
            ],

            'category_id' => [
                'required',
                'exists:categories,id'
            ],

            'operator_id' => [
                'required',
                'exists:sms_operators,id'
            ],

            'count' => [
                'required',
                'integer'
            ],

            'body' => [
                'required',
                'string',
                'min:6',
                'max:1000',
            ],

            'file_path' => [
                'nullable',
                'max:50000',
                'mimetypes:application/csv,application/excel,
        application/vnd.ms-excel, application/vnd.msexcel,
        text/csv, text/anytext, text/plain, text/x-c,
        text/comma-separated-values,
        inode/x-empty,
        application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ],


            'send_at' => [
                'required',
                'date_format:Y-m-d H:i:s',
            ]
        ];
    }
}
