<?php

namespace App\Http\Controllers\API;

use App\Addable;
use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use App\Sh4\sh4Action;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Image;
use Result;
use Validator;


class ProductController extends Controller
{

    use sh4Action;

    #7
    public function index($withCategory = false)
    {
        $products = new Product();
        $products = $products->with(['user', 'category'])
            ->orderBy('promote_expired_at','Desc')
            ->orderBy('id', 'Desc')
            ->where('status', '>', 0)
            ->whereHas("user", function ($q) {
                $q->where("status", ">", 0);
            });


        $p['page'] = Input::get('page');
        $p['per'] = Input::get('per');
        $p['offset'] = ($p['page'] - 1) * $p['per'];


        if (Input::get('q'))
            $products = $products->where('title', 'like', '%' . Input::get('q') . '%');

        if (Input::get('cat_id'))
            $products = $products->where('category_id', Input::get('cat_id'));


        if ($p['per'] && $p['page'])
            $products = $products->offset($p['offset'])
                ->limit($p['per']);

        if ($withCategory)
            $data['categories'] = Category::select('id', 'media_path', 'name')->get();


        $data['products'] = $products->get();

        return Result::setData($data)->get();
    }

    public function show($id)
    {
//        return 1;

        $product = Product::where('id', $id)->with(['user' => function($q) {
            return $q->with('links');
        }, 'addables'])->first();
        return Result::setData(['product' => $product])->get();

    }

    public function explorer()
    {
        return $this->index(true);
    }

    public function store(Request $request)
    {


        $catId = $request->get('category_id');
        Log::emergency($request->all()); #todo test
        $i = 0; //first number of counter


        $addables = [];

        $fields = [
            "title",
            "description",
            "category_id",
            "type",
            //"user_id",
        ];

        if ($request->get('type') == 'video')
            $mediaValidation = 'required|max:15000000۰۰۰';
        elseif ($request->get('type') == 'picture')
            $mediaValidation = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10000000';
        else
            return Result::setErrors(['wrong type'])->get();


        $validator = Validator::make($request->all(), [
            'media_path' => $mediaValidation,
            'addables.*' => $mediaValidation,
            'type' => 'required',
            'title' => 'required|string|min:6|max:200',
            'description' => 'required|string|min:6|max:400',
            'category_id' => 'required',
            'description' => 'required|string|min:6|',
        ]);


        if ($validator->fails())
            return Result::setErrors([$validator->errors()])->get();


        if (Auth::user()->limit_insert_product < 1)
            return Result::setErrors(['limit_insert_product' => 'لطفا بسته ی درج محصول را تمدید نمایید.'])->get();


        if (Auth::user()->status < 1)
            return Result::setErrors(['status' => 'کاربری شما غیر فعال است. لظفا با مدیریت تماس بگیرید'])->get();


        if ($validator->fails())
            return Result::setErrors([$validator->errors()])->get();


        $columns = $request->only(array_merge($fields, []));


        if (Input::hasFile('media_path'))
            $columns['media_path'] = $this->storeMedia($request->file('media_path'), $request->get('type'));


        $columns['user_id'] = Auth::user()->id;
        $productId = Product::insertGetId($columns);


        if (Input::hasFile('addables'))
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


        return Result::setData(['product' => Product::where('id', $productId)->with('addables')->first()])->get();
    }

    public function update(Request $request, $id)
    {

        $fields = $request->only(['title', 'description']);

        Log::emergency($request->all()); #todo test


        if (Product::where('user_id', Auth::user()->id)->where('id', $id)->update($fields))
            return Result::setData(['product' => Product::where('id', $id)->with('addables')->first()])->get();
        else
            return Result::setErrors(['error' => 'Something is wrong'])->get();
    }

//    public function storePicture($originalMedia)
//    {
//        $name = 'j' . time() . '.' . $originalMedia->getClientOriginalName() . '.' . $originalMedia->guessExtension();
//        $thumbnailImage = Image::make($originalMedia);
//        $thumbnailPath = public_path() . $this->pathThumbnail;
//        $originalPath = public_path() . $this->pathMedia;
//        $thumbnailImage->save($originalPath . $name, 90);
//        $thumbnailImage->resize($this->cropHeightThumbnail, $this->cropWidthThumbnail);
//        $thumbnailImage->save($thumbnailPath . $name, 90);
//        return $this->pathMedia . $name;
//    }

//    public function storeVideo($originalMedia)
//    {
//        $name = time() . '.' . $originalMedia->getClientOriginalName() . '.' . $originalMedia->guessExtension();
//        $path = public_path() . $this->pathMedia;
//        $originalMedia->move($path, $name);
//
//        return $this->pathMedia . $name;
//    }

//    public function storeMedia($media, $type)
//    {
//        if ($type == 'video')
//            return $this->storeVideo($media);
//        elseif ($type == 'picture')
//            return $this->storePicture($media);
//
//    }

    public function destroy($id)
    {

//        return Auth::user();


        $product = Product::where('user_id', Auth::user()->id)->where('id', $id)->first();
        $cateId = $product->category_id;



        if ($product->delete()) {

//            Auth::user()->increment('limit_insert_product'); @todo Mr kanani wants us changing to this state that limit insert doesnt increase.
            User::find(1)->decrement('count_product');
            Auth::user()->decrement('count_product');
            Category::find($cateId)->decrement('count_product');
            return Result::setData(['message' => 'product successfully deleted.'])->get();
        } else
            return Result::setErrors(['error' => 'Something is wrong'])->get();
    }


}
