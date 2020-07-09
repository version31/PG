<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\City;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Hash;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('role_id', 3)->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $userFields = [
            'first_name' => 'required | string',
            'last_name' => 'required | string',
            'mobile' => 'numeric | required | unique:users',
            'email' => 'email | required | unique:users',
            'website' => 'string | nullable',
            'fax' => 'nullable | string',
            'phone' => 'string | nullable',
            'gender' => 'required | in:male,female',
            'bio' => 'nullable | string',

        ];

        $providerFields = array_merge($userFields, [
                'shop_expired_at' => 'required_with:star-plan | integer',
                'address' => 'required | string | min:10 | max:200 '
            ]
        );

        $request->validate($userFields);

        $data = $request->all();
        if ($request->input('role_id') == 2 && $request->has('star-plan')) {
            $shop_expired_at = date('Y-m-d H:i:s', substr($request->input('shop_expired_at'), 0, -3));
            $data['shop_expired_at'] = $shop_expired_at;
        }
        $data['password'] = bcrypt($request->input('password'));
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->storeMedia($request->file('avatar')[0], 'picture');
        }

        $data = array_filter($data);

        $data['role_id'] = 3;
        $user = User::create($data);
        if ($user) {
//            if ($request->input('role_id') == 2)
//                $star = $user->star()->create([
//                'user_id' => $user->id,
//                'star' => $request->input('star-plan'),
//                'star_expired_at' => $shop_expired_at,
//            ]);
            Session::flash('alert-info', 'success,کاربر شما با موفقیت افزوده شد');
            return redirect()->route('admin.users.index');
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
        $user = User::find($id);
        $roles = Role::all();
        $cities = City::all();
        $starPlans = Plan::where('type', 'BUY_PROVIDER_STAR')->get();
        return view('admin.users.show', compact('roles', 'cities', 'starPlans', 'user'));
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
        $roles = Role::all();
        $cities = City::all();
        $starPlans = Plan::where('type', 'BUY_PROVIDER_STAR')->get();
        return view('admin.users.edit', compact('roles', 'cities', 'starPlans', 'user'));
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

//        return $id;
        $isAdmin = false;

        if ($id == 'admin') {
            $id = 1;
            $isAdmin = true;

        }

        $request->validate([
            'first_name' => 'required | string',
            'last_name' => 'required | string',
            'mobile' => 'required | numeric  | unique:users,mobile,' . $id,
            'website' => 'string | nullable',
            'email' => 'required | string | email | unique:users,email,' . $id,
            'fax' => 'nullable | string',
            'phone' => 'string | nullable',
            'gender' => 'required | in:male,female',
            'bio' => 'nullable | string',
        ]);

        $data = $request->only(['first_name', 'last_name', 'mobile', 'website', 'email', 'fax', 'phone', 'city_id', 'gender', 'bio', 'fax', 'address']);


//        dd($data);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->storeMedia($request->file('avatar')[0], 'picture');
        }


        $data['role_id'] = 3;

        if ($isAdmin) {
            $data['role_id'] = 1;
        }

        $user = User::find($id)->update($data);

        if ($user) {

            Session::flash('alert-info', 'success,کاربر شما با موفقیت به روزرسانی شد');
            return redirect()->route('admin.users.index');

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


    public function editRole($id)
    {

        $user = User::find($id);

        return view('admin/users/change_role', [
            'user' => $user
        ]);

    }


    public function updateRole(Request $request, $id)
    {

        $request->validate([
            'shop_expired_at' => 'required',
            'limit_insert_product' => 'required',
        ]);

        $data = $request->only(['limit_insert_product']);


        $data['shop_expired_at'] = date('Y-m-d H:i:s', substr($request->input('shop_expired_at'), 0, -3));
        $data['role_id'] = 2;


        $user = User::find($id)->update($data);

        if ($user) {
            Session::flash('alert-info', 'success,کاربر شما با موفقیت به روزرسانی شد');
            return redirect()->route('admin.users.index');

        }
        Session::flash('alert-info', 'error,متاسفانه هم اکنون قادر به روزرسانی کاربر نیستیم، لطفا بعدا امتحان نمایید');
        return redirect()->back();
    }


    public function editAdmin()
    {
        $user = User::find(1);
        return view('admin.users.edit_admin', compact('user'));
    }


    public function createPassword()
    {
        return view('admin.users.password');
    }

    public function updatePassword(Request $request)
    {
        if(!Hash::check($request->previous_password, Auth::user()->password)){
            return redirect()->back()->withErrors([
                'previous_password' => 'رمز عبور فعلی صحیح وارد نشده است'
            ]);
        }else{
            $request->validate([
                'password' => 'required|string|min:6|confirmed|different:previous_password'
            ]);

            $update = Auth::user()->update(['password' => Hash::make($request->password)]);

            if ($update){
                Session::flash('alert-info', 'success,' . 'رمز عبور با موفقیت تغییر یافت.');
            }

            return redirect()->back();
        }
    }
}

