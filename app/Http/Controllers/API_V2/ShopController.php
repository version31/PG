<?php

namespace App\Http\Controllers\API_V2;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShopRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\SuccessResource;
use App\Models\Category;
use App\OnSale;
use App\Product;
use App\Sh4\Sh4Withdraw;
use App\Shop;
use App\User;
use Illuminate\Http\Request;
use Auth;

class ShopController extends Controller
{
    use Sh4Withdraw;


    public function index(Request $request)
    {
        $query = Shop::hasPagination($request , 1)->get();


        return new BasicResource($query);
    }


    public function store(ShopRequest $request)
    {

        Auth::user()->shops()->delete();

        foreach ($request->get('products') as $field) {
            Shop::create($field);
        }

        return new SuccessResource();
    }




}
