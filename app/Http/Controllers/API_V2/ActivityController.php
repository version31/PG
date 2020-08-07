<?php

namespace App\Http\Controllers\API_V2;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\Product;
use App\Storyable;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        $main = User::select('first_name', 'last_name', 'avatar', 'bio', 'phone', 'longitude', 'latitude', 'website', 'role_id', 'count_like')->isMain()->first()->toarray();
        $main['count_product'] = Product::countActive();
        $providers = User::select('id', 'first_name', 'avatar', 'last_name', 'role_id')->isAdvert()->with('stories')->get();
        $categories = Category::select('id', 'name', 'avatar', 'media_path', 'count_like', 'count_product')->get();
        $stories = Storyable::isVisible()->orderBy('id', 'Desc')->where('storyable_type', User::class)->with(['storyable' => function ($q) {
            $q->select('id', 'first_name', 'last_name', 'avatar', 'shop_name');
        }])->get();

        $data = ['main' => $main];
        $data['main']['categories'] = $categories;
        $data['main']['adverts'] = $providers;
        $data['main']['stories'] = $stories;
        $data['current'] = User::where('id', Auth::user()->id)->select('status', 'id', 'role_id', 'role_id')->first();

        $data['main']['last_version'] = $this->lastVersionAPI;
        $data['main']['minimum_version'] = $this->minimumVersionAPI;
        $data['main']['current_version'] = $this->getCurrentersion();
//        $data['main']['count_like'] = Likeable::count(); #todo sh4: behtare bad az amale like count ezafe beshe
//        $data['main']['count_product'] = Product::count(); #todo sh4:behtare bad az insert product like count ezafe beshe


        return $data;
    }


    public function showMain()
    {
        $main = User::select('first_name', 'last_name', 'avatar', 'bio', 'phone', 'longitude', 'latitude', 'website', 'role_id', 'count_like')->isMain()->first()->toarray();
        $main['count_product'] = Product::countActive();
        $providers = User::select('id', 'first_name', 'avatar', 'last_name', 'role_id')->isAdvert()->with('stories')->get();
        $categories = Category::select('id', 'name', 'avatar', 'media_path', 'count_like', 'count_product')->get();
        $stories = Storyable::isVisible()->orderBy('id', 'Desc')->where('storyable_type', User::class)->with(['storyable' => function ($q) {
            $q->select('id', 'first_name', 'last_name', 'avatar', 'shop_name');
        }])->get();

        $data = ['main' => $main];
        $data['main']['categories'] = $categories;
        $data['main']['adverts'] = $providers;
        $data['main']['stories'] = $stories;
        $data['current'] = User::where('id', Auth::user()->id)->select('status', 'id', 'role_id', 'role_id')->first();

        $data['main']['last_version'] = $this->lastVersionAPI;
        $data['main']['minimum_version'] = $this->minimumVersionAPI;
        $data['main']['current_version'] = $this->getCurrentersion();
//        $data['main']['count_like'] = Likeable::count(); #todo sh4: behtare bad az amale like count ezafe beshe
//        $data['main']['count_product'] = Product::count(); #todo sh4:behtare bad az insert product like count ezafe beshe


        return new BasicResource($data);

    }
}
