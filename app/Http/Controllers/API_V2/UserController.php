<?php

namespace App\Http\Controllers\API_V2;

use App\Category;
use App\Facades\ResultData;
use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\Http\Resources\ShowStatusResource;
use App\Likeable;
use App\Link;
use App\Direct;
use App\Product;
use App\Sh4\sh4Action;
use App\Sh4\sh4Auth;
use App\Sh4\sh4Tools;
use App\Storyable;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Image;
use Result;
use Validator;


class UserController extends Controller
{
    use sh4Auth, sh4Tools, sh4Action;

    public function showStatus()
    {
        $query = User::where('id', Auth::id())->select([
            'id',
            "count_product",
            "limit_insert_product",
            "shop_expired_at",
            "created_at",
        ])
            ->with('wallet')
            ->first();


//        dd($query);

//        return $query;
        return new ShowStatusResource($query);

    }


    public function currentUser()
    {

        $query = User::where('id', Auth::id())->with(['city', 'role', 'favoritePosts', 'favoriteProducts', 'links', 'products' => function ($query) {
            $query->orderBy('id', 'Desc')->with('addables');
        }])
            ->first();


        return new BasicResource($query);

    }


    public function catalogs($id)
    {
        if ($id == "current")
            $id = Auth::id();

        $query = User::select('id', 'first_name', 'last_name')->where('id', $id)->with('catalogs')->first();
        return new BasicResource($query);
    }


    public function show($id)
    {

        if ($id == 'current')
            $id = Auth::id();





        $query = User::where('id', $id)->with(['city', 'role', 'products' => function ($query) {
//        $query = User::where('id', $id)->with(['city', 'role', 'star', 'products' => function ($query) {
            $query->with('addables');
        }])->first();




        return new BasicResource($query);
    }


}
