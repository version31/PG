<?php

namespace App\Http\Controllers\API_V2;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\Product;
use App\Sh4\sh4Action;
use Illuminate\Http\Request;
use Lcobucci\JWT\Claim\Basic;

class CategoryController extends Controller
{
    //
    use sh4Action;



    public function index()
    {

        $query = Category::orderBy('order', 'Desc')->get();

        return new BasicResource($query);

    }

    public function show(Request $request , $id)
    {
        $p['page'] = $request->get('page') ?? 1;
        $p['per'] = $request->get('per') ?? 1;
        $p['q'] = $request->get('q');
        $p['offset'] = ($p['page'] - 1) * $p['per'];

        $query = Category::where('id', $id)
            ->with(['products' => function ($query) use ($p , $request) {

                $query = $query->whereHas('user', function ($query) {
                    $query->shopIsActive();
                });

                $query = $query->with('user')
                    ->orderBy('promote_expired_at', 'Desc')
                    ->orderBy('id', 'Desc')
                    ->where('status', '>', 0);

                if ($p['q'])
                    $query = $query->where('title', 'like', '%' . $request->get('q') . '%');

                if ($p['per'] && $p['per'])
                    $query = $query->with('user')->offset($p['offset'])
                        ->limit($p['per'])
                        ->get();
                return $query;
            }])
            ->first();

        $query['count_product'] = Product::countActive($id);


        return new BasicResource($query);

    }
}
