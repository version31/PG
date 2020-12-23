<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequestRequest extends ApiRequest
{

    public function rules()
    {
        return [

            'banner_plan_id' => [
                'required',
                'exists:banner_plans,id',
            ],

            'day' => [
                'required',
                'integer',
                'min:7'
            ],

            'published_at' => [
                'required',
                'date_format:Y-m-d H:i:s',
            ],

        ];
    }


}
