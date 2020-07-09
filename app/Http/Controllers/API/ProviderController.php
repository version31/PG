<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Support\Facades\Input;
use Result;

class ProviderController extends Controller
{














//    public function profile()
//    {
//        /**/ #Todo For test
//        $user = User::find(2);
//        Auth::login($user);
//        /**/
//
//
//        $provider = User::isProvider()->where('id', Auth::user()->id)->with('favoriteProducts')->with('favoritePosts')->with(['products' => function ($q) {
//            return $q->with('addables');
//        }])->first();
//
//        $data = ['provider' => $provider];
//
//        return Result::setData($data)->get();
//    }


}
