<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Category;
use App\Product;
use App\Sh4\sh4Action;
use Auth;
use Illuminate\Support\Facades\Input;
use Result;

class CategoryController extends Controller
{
    use sh4Action;

    public function index()
    {

        $data['categories'] = Category::orderBy('order', 'Desc')->get();


        return Result::setData($data)->get();
    }

    public function show($id)
    {
        $p['page'] = Input::get('page');
        $p['per'] = Input::get('per');
        $p['q'] = Input::get('q');
        $p['offset'] = ($p['page'] - 1) * $p['per'];

        $category = Category::where('id', $id)
            ->with(['products' => function ($query) use ($p) {

                $query = $query->whereHas('user', function ($query) {
                    $query->shopIsActive();
                });

                $query = $query->with('user')
                    ->orderBy('promote_expired_at', 'Desc')
                    ->orderBy('id', 'Desc')
                    ->where('status', '>', 0);

                if ($p['q'])
                    $query = $query->where('title', 'like', '%' . Input::get('q') . '%');

                if ($p['per'] && $p['per'])
                    $query = $query->with('user')->offset($p['offset'])
                        ->limit($p['per'])
                        ->get();
                return $query;
            }])
            ->first();

        $category['count_product'] = Product::countActive($id);


        $data = ['category' => $category];

        return Result::setData($data)->get();
    }


}
