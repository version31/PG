<?php

namespace App\Http\Controllers\API_V2;

use App\Category;
use App\Http\Resources\ProviderCollection;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Result;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $providers = User::select('*')
            ->isProvider()
            ->shopIsActive()
            ->isActive()
            ->with('stars');


        if ($request->get('q'))
            $providers = $providers->where('shop_name', 'like', '%' . $request->get('q') . '%');

        if ($request->get('star'))
            $providers = $providers->where('star', '>=', $request->get('star'));

        if ($request->get('cat_id')) {
            $providers = $providers->whereHas('products', function ($q) {
                $q->where('category_id', $request->get('cat_id'));
            });

            $data['category'] = Category::select('id', 'name')->find($request->get('cat_id'));
        }


//        return $providers->get();


        return ProviderCollection::collection($providers->get());


    }
}
