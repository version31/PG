<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $plans = Plan::select('id', 'day', 'price', 'type');


        $type = $request->get("type");

        if ($type) {
            $plans = $plans->where('type', $type);

            if ($type == 'BUY_PROVIDER_PLAN' or $type == 'EXTEND_PROVIDER_PLAN' or $type == 'INCREASE_INSERT_PRODUCT')
                $plans = $plans->addSelect('limit_insert_product');
            if ($type == 'BUY_PROVIDER_STAR')
                $plans = $plans->addSelect('extra as name');
        }


        return new BasicResource($plans->get());

    }
}
