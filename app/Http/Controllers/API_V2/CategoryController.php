<?php

namespace App\Http\Controllers\API_V2;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\Product;
use App\Sh4\sh4Action;
use App\User;
use Illuminate\Http\Request;
use Lcobucci\JWT\Claim\Basic;

class CategoryController extends Controller
{
    //
    use sh4Action;



    public function index()
    {
        $categories = Category::orderBy('order', 'Desc')->limit(1)->get();

        #@todo کاربران باید بر اساس بیشترین محصولات فروشگاه اینجا نمایش داده شوند
        $topUsersByMostProducts = User::limit(1)->get();

        $data = [
            'categories' => $categories,
            'default_users' => $topUsersByMostProducts,
        ];

        return new BasicResource($data);

    }

    public function show(Request $request , $id)
    {
        $p['page'] = $request->get('page') ?? 1;
        $p['per'] = $request->get('per') ?? 1;
        $p['q'] = $request->get('q');
        $p['offset'] = ($p['page'] - 1) * $p['per'];

        $query = Category::where('id', $id)
            ->with(['users' => function ($query) use ($p , $request) {
                if ($p['q'])
                    $query = $query->where('title', 'like', '%' . $request->get('q') . '%');

                if ($p['per'] && $p['per'])
                    $query = $query->offset($p['offset'])
                        ->limit($p['per'])
                        ->get();
                return $query;
            }])
            ->first();



        return new BasicResource($query);

    }
}
