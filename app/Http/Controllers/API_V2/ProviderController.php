<?php

namespace App\Http\Controllers\API_V2;

use App\Category;
use App\Http\Resources\BasicResource;
use App\Http\Resources\ProviderCollection;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $providers = User::select('*')
            ->isProvider()
            ->shopIsActive()
            ->isActive()
            ->with('stars');


        $p['page'] = $request->get('page') ?? 1;
        $p['per'] = $request->get('per') ?? 1;
        $p['offset'] = ($p['page'] - 1) * $p['per'];

        if ($request->get('q'))
            $providers = $providers->where('shop_name', 'like', '%' . $request->get('q') . '%');

        if ($request->get('star'))
            $providers = $providers->where('star', '>=', $request->get('star'));

        if ($request->get('cat_id')) {
            $providers = $providers->whereHas('products', function ($q) use ($request) {
                $q->where('category_id', $request->get('cat_id'));
            });
            $data['category'] = Category::select('id', 'name')->find($request->get('cat_id'));
        }


        if ($p['per'] && $p['page'])
            $providers = $providers->offset($p['offset'])
                ->limit($p['per']);


        return ProviderCollection::collection($providers->get());


    }


    public function show($id)
    {
        $user = User::find($id);

        $select = '*';

        if ($user->presentable_fields)
            $select = $user->presentable_fields;

        $query = $user->isProvider()
            ->select($select)
            ->with(['links', 'products' => function ($q) {
                return $q->with('addables');
            }])
            ->first();


        return new BasicResource($query);
    }


    public function stories($id)
    {
        $query = User::isProvider()
            ->select('id', 'first_name', 'last_name')
            ->where('id', $id)
            ->with('stories')
            ->first();


        return new BasicResource($query);
    }
}
