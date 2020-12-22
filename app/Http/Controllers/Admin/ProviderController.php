<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Link;
use App\Models\City;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::isProvider()->get();


        return view('admin.providers.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.providers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $providerFields = [
            'shop_name' => 'required | string',
            'first_name' => 'required | string',
            'last_name' => 'required | string',
            'mobile' => 'numeric | required | unique:users,mobile',
            'website' => 'string | nullable',
            'email' => 'nullable | string | email | unique:users,email',
            'phone' => 'required | string',
            'default' => 'required | string',
            'fax' => 'nullable | string',
            'gender' => 'required | in:male,female',
            'password' => 'required | string',
            'city_id' => 'required ',
            'limit_insert_product' => 'required',
            'shop_expired_at' => 'required',
            'bio' => 'required | string',
            'shop_expired_at' => 'required_with:star-plan | integer',
            'address' => 'required | string | min:10 | max:200 ',
            'avatar.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2000000'
        ];


        $request->validate($providerFields);

        $data = $request->all();

        $data['password'] = bcrypt($request->input('password'));
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->storeMedia($request->file('avatar')[0], 'picture');
        }

        $data = array_filter($data);




        $data['role_id'] = 2;

        $user = User::create($data);

        if ($request->get('default') && $user) {
            $link = new Link();
            $link->type = 'default';
            $link->value = $request->get('default');
            $user->links()->save($link);
        }

        if ($user) {
            Session::flash('alert-info', 'success,کاربر شما با موفقیت افزوده شد');
            return redirect()->route('admin.providers.index');
        }
        Session::flash('alert-info', 'error,متاسفانه هم اکنون قادر به افزودن کاربر نیستیم، لطفا بعدا امتحان نمایید');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::isProvider()->where('id', $id)->first();
        $roles = Role::all();
        $cities = City::all();
        $starPlans = Plan::where('type', 'BUY_PROVIDER_STAR')->get();
        return view('admin.providers.show', compact('roles', 'cities', 'starPlans', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);


//        return $user;

        $roles = Role::all();
        $cities = City::all();
        $starPlans = Plan::where('type', 'BUY_PROVIDER_STAR')->get();
        return view('admin.providers.edit', compact('roles', 'cities', 'starPlans', 'user'));
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

//        return $request->all();
        if ($request->input('role_id') == 2) {
            $request->validate([
                'shop_expired_at' => 'required_with:star-plan | integer',
                'address' => 'required | string',
            ]);
        }
        $request->validate([
            'first_name' => 'required | string',
            'last_name' => 'required | string',
            'shop_name' => 'required | string',
            'mobile' => 'numeric | required | unique:users,mobile,' . $id,
            'website' => 'string | nullable',
            'email' => 'nullable | string | email | unique:users,email,' . $id,
            'fax' => 'nullable | string',
            'phone' => 'required | string',
            'gender' => 'required | in:male,female',
            'bio' => 'required | string',
            'shop_expired_at' => 'required',
            'limit_insert_product' => 'required',
        ]);

        $data = $request->only(['first_name', 'last_name', 'shop_name', 'mobile', 'website', 'email', 'fax', 'phone', 'city_id', 'gender', 'bio', 'fax', 'address', 'shop_expired_at', 'limit_insert_product']);



        $data['shop_expired_at'] = $this->adaptDate($request->input('shop_expired_at'));


        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->storeMedia($request->file('avatar')[0], 'picture');
        }
        if ($request->get('password') != null) {
            $data['password'] = bcrypt($request->input('password'));
        }


//        return $data;

        $data['role_id'] = 2;

        $user = User::find($id)->update($data);




        if ($user) {
            if ($request->get('default')) {
                Link::where('user_id', $id)->where('type', 'default')->delete();
                $link = new Link();
                $link->type = 'default';
                $link->value = $request->get('default');
                User::find($id)->links()->save($link);
            }

            Session::flash('alert-info', 'success,کاربر شما با موفقیت به روزرسانی شد');
            return redirect()->route('admin.providers.index');

        }
        Session::flash('alert-info', 'error,متاسفانه هم اکنون قادر به روزرسانی کاربر نیستیم، لطفا بعدا امتحان نمایید');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (User::destroy($id)) {
            die(true);
        }
        die(false);
    }

    /*extra user controller resource action that update role user to admin*/



}
