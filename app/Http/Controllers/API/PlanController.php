<?php

namespace App\Http\Controllers\API;

use App\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Result;

class PlanController extends Controller
{
    #6
    public function index()
    {
        $plans = Plan::select('id','day','price','type');
        $type = Input::get('type');

        if ($type){
            $plans = $plans->where('type', $type);

            if($type == 'BUY_PROVIDER_PLAN' or $type == 'EXTEND_PROVIDER_PLAN' or $type == 'INCREASE_INSERT_PRODUCT' )
                $plans = $plans->addSelect('limit_insert_product');
            if($type == 'BUY_PROVIDER_STAR')
                $plans = $plans->addSelect('extra as name');
        }



        $data = ['plans' => $plans->get()];

        return Result::setData($data)->get();
    }
}
