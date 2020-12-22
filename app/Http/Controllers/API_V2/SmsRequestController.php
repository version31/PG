<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\SmsRequestRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\SuccessResource;
use App\SmsOperator;
use App\SmsRequest;
use Illuminate\Http\Request;

class SmsRequestController extends Controller
{
    public function store(SmsRequestRequest $request)
    {
        $new = SmsRequest::create($request->all());

        return new SuccessResource($new );
    }


    public function operators()
    {
        $query = SmsOperator::all();

        return BasicResource::collection($query);
    }
}
