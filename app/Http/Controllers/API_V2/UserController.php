<?php

namespace App\Http\Controllers\API_V2;

use App\Category;
use App\Facades\ResultData;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\ProviderCollection;
use App\Http\Resources\ShowStatusResource;
use App\Link;
use App\Sh4\sh4Action;
use App\Sh4\sh4Auth;
use App\Sh4\sh4Tools;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Result;
use Validator;


class UserController extends Controller
{
    use sh4Auth, sh4Tools, sh4Action;

    public function showStatus()
    {
        $query = User::where('id', Auth::id())->select([
            'id',
            "count_product",
            "limit_insert_product",
            "shop_expired_at",
            "created_at",
        ])
            ->with('wallet')
            ->first();


//        dd($query);

//        return $query;
        return new ShowStatusResource($query);

    }


    public function currentUser()
    {
        $query = User::where('id', Auth::id())->with(['city', 'role', 'favoritePosts', 'favoriteProducts', 'links', 'products' => function ($query) {
            $query->orderBy('id', 'Desc')->with('addables');
        }])
            ->first();

        return new BasicResource($query);

    }


    public function updateProfile(UserRequest $request)
    {
        $id = Auth::id();

        return $this->update($request, $id);
    }


    public function catalogs($id)
    {
        if ($id == "current")
            $id = Auth::id();

        $query = User::select('id', 'first_name', 'last_name')->where('id', $id)->with('catalogs')->first();
        return new BasicResource($query);
    }


    public function update(Request $request, $id)
    {

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


        if ($request->hasFile('avatar'))
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
        $query = User::isProvider()
            ->select('id', 'first_name', 'last_name')
            ->where('id', $id)
            ->with('stories')
            ->first();


        return new BasicResource($query);
    }


    public function index(Request $request)
    {
        $query = User::select('*')
            ->shopIsActive()
            ->isActive()
            ->orderBy('id','Desc')
            ->with('stars');


        if ($request->get('role') == "provider")
            $query=$query->isProvider();


        $p['page'] = $request->get('page') ?? 1;
        $p['per'] = $request->get('per') ?? 1;
        $p['offset'] = ($p['page'] - 1) * $p['per'];

        if ($request->get('q'))
            $query = $query->where('shop_name', 'like', '%' . $request->get('q') . '%');

        if ($request->get('star'))
            $query = $query->where('star', '>=', $request->get('star'));

        if ($request->get('cat_id')) {
            $query = $query->whereHas('products', function ($q) use ($request) {
                $q->where('category_id', $request->get('cat_id'));
            });
            $data['category'] = Category::select('id', 'name')->find($request->get('cat_id'));
        }


        if ($p['per'] && $p['page'])
            $query = $query->offset($p['offset'])
                ->limit($p['per']);


        return ProviderCollection::collection($query->get());


    }

    public function show($id)
    {


        $user = User::where('id', $id)->first();

        $select = '*';

        if ($user->presentable_fields)
            $select = $user->presentable_fields;

        $query = User::where('id', $id)->select($select)
            ->with(['city', 'role', 'links', 'products' => function ($q) {
                return $q->with('addables');
            }])
            ->first();


        return new BasicResource($query);
    }


}
