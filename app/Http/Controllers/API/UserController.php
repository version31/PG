<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Facades\ResultData;
use App\Http\Controllers\Controller;
use App\Likeable;
use App\Link;
use App\Direct;
use App\Product;
use App\Sh4\sh4Auth;
use App\Sh4\sh4Tools;
use App\Storyable;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Image;
use Result;
use Validator;


class UserController extends Controller
{
    use sh4Auth, sh4Tools;

    public function mainActivity()
    {

        $main = User::select('first_name', 'last_name', 'avatar', 'bio', 'phone', 'longitude', 'latitude', 'website', 'role_id','count_like')->isMain()->first()->toarray();
        $main['count_product'] = Product::countActive();
        $providers = User::select('id', 'first_name', 'avatar', 'last_name', 'role_id')->isAdvert()->with('stories')->get();
        $categories = Category::select('id', 'name', 'avatar', 'media_path', 'count_like' , 'count_product')->get();
        $stories = Storyable::isVisible()->orderBy('id','Desc')->where('storyable_type' , User::class)->with(['storyable' => function($q){
            $q->select('id','first_name','last_name' , 'avatar','shop_name');
        }])->get();

        $data = ['main' => $main];
        $data['main']['categories'] = $categories;
        $data['main']['adverts'] = $providers;
        $data['main']['stories'] = $stories;
        $data['current'] = User::where('id',Auth::user()->id)->select('status','id', 'role_id', 'role_id')->first();

        $data['main']['last_version'] = $this->lastVersionAPI;
        $data['main']['minimum_version'] = $this->minimumVersionAPI;
        $data['main']['current_version'] = $this->getCurrentersion();
//        $data['main']['count_like'] = Likeable::count(); #todo sh4: behtare bad az amale like count ezafe beshe
//        $data['main']['count_product'] = Product::count(); #todo sh4:behtare bad az insert product like count ezafe beshe


        return Result::setData($data)->get();
    }


    public function directs($audienceId)
    {

        $userId = Auth::user()->id;



        $user = User::select('id', 'first_name', 'last_name', 'avatar')->where('id', $audienceId)->first();

        $directs = Direct::select('*')
            ->where(function ($query) use ($userId, $audienceId) {
                $query->where("user_id", $userId)
                    ->where("receiver_id", $audienceId);
            })
            ->orWhere(function ($query) use ($userId, $audienceId) {
                $query->Where("user_id", $audienceId)
                    ->Where("receiver_id", $userId);
            })
            ->orderBy('created_at', 'Desc')
            ->get();

        $data = ['user' => $user];
        $data['user']['directs'] = $directs;

        return Result::setData($data)->get();
    }


    public function show($id)
    {
        return User::where('id', $id)->with(['city', 'role', 'star', 'products' => function ($query) {
            $query->with('addables');
        }])->first();
    }


    public function showProvider($id)
    {
        $provider = User::isProvider()->select('*')->where('id', $id)->with(['links','products' => function ($q) {
            return $q->with('addables');
        }])->first();

        $data = ['provider' => $provider];

        return Result::setData($data)->get();
    }


    public function profile()
    {
        $data['user'] = $this->currentUser();
        return Result::setData($data)->get();
    }


    private function currentUser()
    {
        $user = Auth::user();


        if (Auth::user()->role_id == 1)
            $current = $user->isAdmin();
        if (Auth::user()->role_id == 2)
            $current = $user->isProvider()->with(['city', 'role', 'favoritePosts', 'favoriteProducts', 'links', 'products' => function ($query) {
                $query->orderBy('id', 'Desc')->with('addables');
            }]);
        if (Auth::user()->role_id == 3)
            $current = $user->isUser()->with(['city', 'role', 'favoritePosts', 'favoriteProducts', 'links']);


        return $current->where('id', Auth::user()->id)->first();


    }


    public function updateProfile(Request $request)
    {
        $id = $this->currentUser()->id;

        return $this->update($request, $id);
    }


    public function update(Request $request, $id)
    {



//        return $request->all();

        Log::emergency($request->all()); #todo test

//        return 333;

        $fields = [
            "avatar",
            "first_name",
            "last_name",
            "gender",
            "bio",
            "website",
            "email",
            "mobile",
            "phone",
            "fax",
            "city_id",
            "address"
        ];


        $columns = $request->only(array_merge($fields, []));


        $columns['shop_name'] = $request->get('brand');



        if ($this->checkPassword($request->get('password')) instanceof ResultData)
            return $this->checkPassword($request->get('password'));
        elseif (is_string($this->checkPassword($request->get('password'))))
            $columns['password'] = bcrypt($this->checkPassword($request->get('password')));


//        $columns['password'] = bcrypt("password");


        if (Input::hasFile('avatar'))
            $columns['avatar'] = $this->storeAvatar($request->file('avatar'));


        if (empty($columns['avatar']))
            $columns['avatar'] = \DB::table('users')->where('id', $id)->first()->avatar;



        User::where('id', $id)->update($columns);


        $requestLinks = is_string($request->get('links')) ? json_decode($request->get('links'), true) : $request->get('links');
        $requestLinks = is_array($requestLinks) ? $requestLinks : [];

        $links = [];
        foreach ($requestLinks as $link)
            $links[] = new Link($link);
        User::find($id)->links()->delete();
        User::find($id)->links()->saveMany($links);


        $data['user'] = User::select(array_merge(['id', 'avatar'], $fields))->with('links')->where('id', $id)->first();
        return Result::setData($data)->get();
    }


    public function updateForTest(Request $request, $id)
    {



        Log::emergency($request->all()); #todo test

        $fields = [

            "shop_expired_at"
        ];


        $columns = $request->only("shop_expired_at");


            User::where('id', $id)->update($columns);


        return    User::find($id);
    }


    private function storeAvatar($originalMedia)
    {
//        $name = time() . $originalImage->getClientOriginalName();
//        $thumbnailImage = Image::make($originalImage);
//        $thumbnailPath = public_path() . $this->pathThumbnail;
//        $originalPath = public_path() . $this->pathMedia;
//        $thumbnailImage->save($originalPath . $name);
//        $thumbnailImage->resize(150, 150);
//        $thumbnailImage->save($thumbnailPath . $name);
//
//        return $name;
//


        $name = 'j' . time() . '.' . $originalMedia->getClientOriginalName() . '.' . $originalMedia->guessExtension();
        $thumbnailImage = Image::make($originalMedia);
        $thumbnailPath = public_path() . $this->pathThumbnail;
        $originalPath = public_path() . $this->pathMedia;
        $thumbnailImage->save($originalPath . $name, 90);
        $thumbnailImage->resize($this->cropHeightThumbnail, $this->cropWidthThumbnail);
        $thumbnailImage->save($thumbnailPath . $name, 90);
        return $this->pathMedia . $name;
    }


    public function updateLinks()
    {

    }


    private function checkPassword($password)
    {

        $result = null;

        if (!empty($password)) {


            $password = json_decode($password, true);

            if (!is_array($password))
                return Result::setErrors(["ساختار پسورد اشتباه است"])->get();


            $validator = Validator::make($password, [
//                'old' => 'required',
                'new' => 'required|string|min:8',
                'confirmation' => 'required|string|min:8|',
            ]);


            if ($validator->fails())
                return Result::setErrors([$validator->errors()])->get();


            if ($password['new'] !== $password['confirmation'])
                return Result::setErrors(["پسورد تازه با تکرار پسورد مطابقت ندارد"])->get();


            ## remove old password
//            if (!(Hash::check($password['old'], \Auth::user()->password)))
//                return Result::setErrors(["پسورد فعلی صحیح نمی باشد"])->get();
//            if (strcmp($password['old'], $password['new']) == 0)
//                return Result::setErrors(["لطفا یک پسورد جدید انتخاب کنید، شما نمی توانید پسورد قبلی خود را انتخاب کنید."])->get();

            return $password['new'];
        }


        return $result;
    }


    public function stories($id)
    {
        $provider = User::isProvider()->select('id', 'first_name', 'last_name')->where('id', $id)->with('stories')->first();

        $data = ['provider' => $provider];

        return Result::setData($data)->get();
    }


    public function indexProvider()
    {
        $data = [];
        $providers = User::isProvider()
            ->shopIsActive()
            ->isActive()
            ->orderBy('id' , 'Desc')
            ->with(['stars' => function($q) {
                return $q->orderBy('star','Desc');
            }])
            ->select('*');




        if (Input::get('q'))
            $providers = $providers->where('shop_name', 'like', '%' . Input::get('q') . '%');

        if (Input::get('star'))
            $providers = $providers->where('star', '>=', Input::get('star'));

        if (Input::get('cat_id')) {
            $providers = $providers->whereHas('products', function ($q) {
                $q->where('category_id', Input::get('cat_id'));
            });

            $data['category'] = Category::select('id', 'name')->find(Input::get('cat_id'));
        }


        $data['providers'] = $providers->get();

        return Result::setData($data)->get();
    }

    //    public function updateProfile(Request $request)
//    {
//
//        Auth::loginUsingId(2); #todo test
//
//        $id = Auth::User()->id;
//
//        return $this->update($request , $id);
//    }

}
