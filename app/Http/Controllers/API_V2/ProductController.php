<?php

namespace App\Http\Controllers\API_V2;

use App\Addable;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product2Request;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SuccessResource;
use App\Product;
use App\Sh4\sh4Action;
use App\Sh4\sh4Report;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;

class ProductController extends Controller
{
    //
    use sh4Action, sh4Report;


    /**
     * ProductController constructor.
     */
    public function __construct()
    {

        $this->middleware('canProviderSendProduct', ['only' => ['store']]);
    }

    public function show($id)
    {

        $query = Product::where('id', $id)->with(['user' => function ($q) {
            return $q->with('links');
        }, 'media'])->first();


        if ($query)
            $query->increment('count_visit');

        return new ProductResource($query);

    }


    public function index(Request $request)
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


        $data = $products->get();

        if ($request->get('flag') == 'explorer')
            $data =
                [
                    'products' => $products->get(),
                    'categories' => Category::select('id', 'media_path', 'name')->get()
                ];


        return new BasicResource($data);
    }


    public function store(ProductRequest $request)
    {

        $catId = $request->get('category_id');


        $fields = [
            "title",
            "description",
            "category_id",
            "price",
            "shipping",
        ];

        $columns = $request->only($fields);


        if ($request->hasFile('media_path'))
            $columns['media_path'] = $this->storeMedia($request->file('media_path'), 'picture');


        $columns['user_id'] = Auth::id();
        $productId = Product::insertGetId($columns);
        $newRow = Product::create($columns);


        if ($request->has('media'))
            foreach ($request->file('media') as $media)
                $newRow
                    ->addMedia($media)
                    ->toMediaCollection();


        if ($request->has('audio'))
            $newRow
                ->addMedia($request->file('audio'))->toMediaCollection();



        if ($productId) {
//            Auth::user()->decrement('limit_insert_product');
            User::find(1)->increment('count_product');
            Category::find($catId)->increment('count_product');
            Auth::user()->increment('count_product');
        }

        return new SuccessResource();
    }

    public function update(ProductRequest $request, $id)
    {
        $fields = $request->only(['title', 'description']);

        if (Product::where('user_id', Auth::id())->where('id', $id)->update($fields))
            return new SuccessResource();

    }


    public function destroy($id)
    {
        $product = Product::where('user_id', Auth::user()->id)->where('id', $id)->first();
        if ($product->delete()) {
            //            Auth::user()->increment('limit_insert_product'); @todo Mr kanani wants us changing to this state that limit insert doesnt increase.
            User::find(1)->decrement('count_product');
            Auth::user()->decrement('count_product');
            if ($product->category_id)
                Category::find($product->category_id)->decrement('count_product');
            return new SuccessResource();
        }
    }


}
