<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\Variable;
use Illuminate\Http\Request;

class VariableController extends Controller
{
    //

    public function show($key)
    {
        $query = Variable::select('value')->where('key' , $key)->first();

        return new BasicResource($query);

    }

    public function index()
    {
        $query = Variable::select('key','value')->whereIn('key' , ['GIFT_REGISTER_ALERT','GIFT_REGISTER_PERCENT'])->get();

        return new BasicResource($query);
    }




}
