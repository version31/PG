<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\ShopPlan;
use Illuminate\Http\Request;

class ShopPlanController extends Controller
{
    public function index(Request $request)
    {
        $query = ShopPlan::all();

        return new BasicResource($query);
    }
}
