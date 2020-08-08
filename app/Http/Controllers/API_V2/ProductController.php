<?php

namespace App\Http\Controllers\API_V2;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\Product;
use App\Sh4\sh4Action;
use App\Sh4\sh4Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    use sh4Action;

    public function show($id)
    {

        $query = Product::where('id', $id)->with(['user' => function ($q) {
            return $q->with('links');
        }, 'addables'])->first();


        $query->increment('count_visit');

        return new BasicResource($query);

    }


    public function index(Request $request, $withCategory = false)
    {
        $products = new Product();
        $products = $products->with(['user', 'category'])
            ->orderBy('promote_expired_at', 'Desc')
            ->orderBy('id', 'Desc')
            ->where('status', '>', 0)
            ->whereHas("user", function ($q) {
                $q->where("status", ">", 0)->where('shop_expired_at', ">", Carbon::now());
            });


        $p['page'] = $request->get('page') ?? 1;
        $p['per'] = $request->get('per') ?? 1;
        $p['offset'] = ($p['page'] - 1) * $p['per'];


        if ($request->get('q'))
            $products = $products->where('title', 'like', '%' . $request->get('q') . '%');

        if ($request->get('cat_id'))
            $products = $products->where('category_id', $request->get('cat_id'));


        if ($p['per'] && $p['page'])
            $products = $products->offset($p['offset'])
                ->limit($p['per']);

        if ($withCategory)
            $data['categories'] = Category::select('id', 'media_path', 'name')->get();


        $data['products'] = $products->get();


        return new BasicResource($products->get());
    }
}
