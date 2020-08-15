<?php

namespace App\Http\Controllers\API_V2;

use App\Addable;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product2Request;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\ErrorResource;
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
        }, 'addables'])->first();


        if ($query)
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


    public function explorer()
    {
        return $this->index(true);
    }

    public function store(ProductRequest $request)
    {


        $catId = $request->get('category_id');

        $i = 0; //first number of counter

        $addables = [];

        $fields = [
            "title",
            "description",
            "category_id",
            "type",
        ];

        $columns = $request->only($fields);


        if ($request->hasFile('media_path'))
            $columns['media_path'] = $this->storeMedia($request->file('media_path'), $request->get('type'));


        $columns['user_id'] = Auth::id();
        $productId = Product::insertGetId($columns);


        if ($request->hasFile('addables'))
            foreach ($request->file('addables') as $media2) {
                $addables['media_path'] = $this->storeMedia($media2, $request->get('type'));
                $addables['addable_type'] = Product::class;
                $addables['type'] = $request->get('type');
                $addables['addable_id'] = $productId;
                Addable::insert($addables);
                ++$i;

                if ($i >= $this->maxAllowedVideos)
                    break;
            }


        if ($productId) {
            Auth::user()->decrement('limit_insert_product');
            User::find(1)->increment('count_product');
            Category::find($catId)->increment('count_product');
            Auth::user()->increment('count_product');
        }

        return new SuccessResource();
    }

    public function update(ProductRequest $request, $id)
    {
        $fields = $request->only(['title', 'description']);


        if (Product::where('user_id', Auth::user()->id)->where('id', $id)->update($fields))
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
