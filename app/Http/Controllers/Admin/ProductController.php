<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Addable;
use App\Category;
use App\Product;
use App\Role;
use App\User;
use App\Rules\Timestamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    protected function validator($request, $type = 'picture')
    {
        $mediaValidation = 'required';

        if ($type == 'video') {
            $mediaValidation = 'required|mimetypes:video/avi,video/mp4,video/mpeg,video/quicktime|max:8000000';
        } elseif ($type == 'picture')
            $mediaValidation = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8000000';

        $request->validate([
            'media_path' => $mediaValidation,
            'additionalImage.*' => $mediaValidation,
            'user_id' => 'required | integer',
            'title' => 'required|string|min:6|max:200',
            'count_like' => 'numeric | nullable',
            'category_id' => 'required | integer',
            'description' => 'required|string|min:6|max:40000',
//            'promote_expired_at' => ['required', 'string', new Timestamp],
        ]);


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }


    public function changeStatus($id, $status)
    {

        $statuses = [
            'hide' => -1,
            'show' => 1
        ];


        \DB::table('products')->where('id', $id)->update(['status' => $statuses[$status]]);


        return back();


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provider_role_id = Role::where('name', 'provider')->first()->id;
        $providers = User::where('role_id', $provider_role_id)->get();
        $plans = Category::all();
        return view('admin.products.create', compact('providers', 'plans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $mediaType = $this->getTypeMedia($request->file('media_path'));

        $this->validator($request, $mediaType);


        $product = new product;
        $product->user_id = $request->input('user_id');
        $product->category_id = $request->input('category_id');
        $product->title = $request->input('title');
//        if ($request->has('count_like'))
//            $product->count_like = $request->input('count_like') ?? 0;
        $product->description = $request->input('description');
        ini_set('memory_limit', '10240M');
        if ($request->hasFile('media_path')) {
            $product->media_path = $this->storeMedia($request->file('media_path'), $mediaType);
        }
        if ($request->has('status')) {
            $product->status = 1;
        } else {
            $product->status = 0;
        }

        if ($request->has('promote_expired_at')) {
            $promote_expired_at = date('Y-m-d H:i:s', substr($request->input('promote_expired_at'), 0, -3));
            $product->promote_expired_at = $promote_expired_at;
        }


        if ($product->save()) {
            Session::flash('alert-info', 'success,محصول شما با موفقیت افزوده شد');
            if ($request->hasFile('additionalImage')) {
                foreach ($request->file('additionalImage') as $file) {
                    $media_path = $this->storeMedia($file, $mediaType);
//                    $tmp = explode('.', $media_path);
////                    $extensions = end($tmp);
////                    switch ($extensions) {
////                        case 'jpeg':
////                        case 'png':
////                        case 'gif':
////                            $type = 'picture';
////                            break;
////                        case 'mp4':
////                        case 'mov':
////                            $type = 'video';
////
////                    }
///

                    $mediaType = $this->getTypeMedia($file);

                    $product->addables()->create(['addable_type' => 'App\Product', 'media_path' => $media_path, 'type' => $mediaType]);





                }
            }



            $product = Product::where('id', $product->id)->first();
            $user_id = $product->user_id;
            $user = User::find($user_id);
            $catId = $product->category_id;
            $user->decrement('limit_insert_product');
            User::find(1)->increment('count_product');
            $user->increment('count_product');
            Category::find($catId)->increment('count_product');




            return redirect()->route('admin.products.index');
        } else {
            Session::flash('alert-info', 'error,متاسفانه هم اکنون قادر به افزودن محصول نیستیم، لطفا بعدا امتحان نمایید');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        $provider_role_id = Role::where('name', 'provider')->first()->id;
        $providers = User::where('role_id', $provider_role_id)->get();
        $plans = Category::all();
        return view('admin.products.show', compact('providers', 'plans', 'product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $provider_role_id = Role::where('name', 'provider')->first()->id;
        $providers = User::where('role_id', $provider_role_id)->get();
        $categories = Category::all();


        return view('admin.products.edit', compact('providers', 'categories', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


//        return $request->only('promote_expired_at');


        $mediaType = $this->getTypeMedia($request->file('media_path'));

        $mediaValidation = 'required';

        if ($mediaType == 'video') {
            $mediaValidation = 'required|mimetypes:video/avi,video/mp4,video/mpeg,video/quicktime|max:18000000';

        } elseif ($mediaType == 'picture')
            $mediaValidation = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8000000';


        $request->validate([
            'media_path.*' => $mediaValidation,
            'additionalImage.*' => $mediaValidation,
            'user_id' => 'required | integer',
            'title' => 'required|string|min:6|max:200',
            'count_like' => 'numeric | nullable',
            'category_id' => 'required | integer',
            'description' => 'required|string|min:6|max:40000',
//            'promote_expired_at' =>  new Timestamp(),
        ]);


        $product = product::findOrFail($id);
        if ($request->ajax() && $product) {
            $product->confirmed_at = date('Y-m-d H:i:s', time());
            dd($product->save());

        }
        $product->user_id = $request->input('user_id');
        $product->category_id = $request->input('category_id');
        $product->title = $request->input('title');
        if ($request->has('count_like'))
            $product->count_like = $request->input('count_like') ?? 0;
        $product->description = $request->input('description');
        if ($request->hasFile('media_path')) {
            $product->media_path = $this->storeMedia($request->file('media_path')[0], $mediaType);
        }

        if ($request->has('status')) {
            $product->status = 1;
        } else {
            $product->status = 0;
        }


        if ($request->has('promote_expired_at')) {
            $promote_expired_at = date('Y-m-d H:i:s', substr($request->input('promote_expired_at'), 0, -3));

            $expired_at = date('Y-m-d H:i:s', substr($request->input('expired_at'), 0, -3));
            $data['expired_at'] = $expired_at;

            $product->promote_expired_at = $promote_expired_at;
        }

        if ($product->save()) {
            if ($request->has('delete')) {
                if (is_array($request->input('delete'))) {
                    foreach ($request->input('delete') as $index) {
                        $value = explode('/', $index);
                        Storage::delete('public/' . end($value));
                        Addable::where('media_path', 'public/' . end($value))->delete();
                    }
                } else {
                    $value = explode('/', $request->input('delete'));
                    Storage::delete('public/' . end($value));
                    Addable::where('media_path', 'public/' . end($value))->delete();
                }
            }

            Session::flash('alert-info', 'success,محصول شما با موفقیت  به روزرسانی شد');
            if ($request->hasFile('additionalImage')) {

                $product->addables()->delete();


                foreach ($request->file('additionalImage') as $file) {
                    $media_path = $this->storeMedia($file, 'picture');
//                    $tmp = explode('.', $media_path);
//                    $extensions = end($tmp);
//                    switch ($extensions) {
//                        case 'jpeg':
//                        case 'png':
//                        case 'gif':
//                            $type = 'picture';
//                            break;
//                        case 'mp4':
//                        case 'mov':
//                            $type = 'video';
//
//                    }


                    $mediaType = $this->getTypeMedia($file);

                    $product->addables()->create(['addable_type' => 'App\Product', 'media_path' => $media_path, 'type' => $mediaType]);


                }
            }
            return redirect()->route('admin.products.index');
        } else {
            Session::flash('alert-info', 'error,متاسفانه هم اکنون قادر به به روزرسانی  محصول نیستیم، لطفا بعدا امتحان نمایید');
            return redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $product = Product::where('id', $id)->first();
        $user_id = $product->user_id;
        $user = User::find($user_id);
        $cateId = $product->category_id;

        if (Product::destroy($id)) {
            $user->increment('limit_insert_product');
            User::find(1)->decrement('count_product');
            $user->decrement('count_product');
            Category::find($cateId)->decrement('count_product');
            die(true);
        }
        die(false);
    }
}
