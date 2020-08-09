<?php

namespace App\Http\Controllers\API_V2;

use App\City;
use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use Illuminate\Http\Request;

class CityController extends Controller
{

    public function index()
    {
        $query = City::where('parent_id', '<>', null)->get();
        return new BasicResource($query);
    }
}
