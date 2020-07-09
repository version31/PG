<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ServiceController extends Controller
{
    protected function validator($request){


        $mediaValidation = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8000000';


        $request->validate([
//            'avatar.*' => $mediaValidation,
            'media_path.*' => $mediaValidation,
            'title' => 'string|required',
            'headline' => 'string|required',
            'body' => 'string|required',
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::get()->all();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $admin_users = User::all();
        return view('admin.services.create', compact('admin_users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {



        $this->validator($request);
        /*add new role to validation*/
        $request->validate([
//            'avatar' =>'required',
            'media_path' =>'required',
        ]);
        $data = $request->all();
        $data['user_id'] = Auth::id();
        if ($request->hasFile('avatar')) {
            $data['avatar']= $this->storeMedia($request->file('avatar')[0],'picture');
        }
        if ($request->hasFile('media_path')) {
            $data['media_path']= $this->storeMedia($request->file('media_path')[0],'picture');
        }
        $service = Service::create($data);
        if ($service) {
            Session::flash('alert-info', 'success,سرویس شما با موفقیت افزوده شد');
            return redirect()->route('admin.services.index');
        } else {
            Session::flash('alert-info', 'error,متاسفانه هم اکنون قادر به افزودن سرویس نیستیم، لطفا بعدا امتحان نمایید');
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
        $admin_users = User::all();
        $service = Service::find($id);
        return view('admin.services.show',compact('service','admin_users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin_users = User::all();
        $service = Service::find($id);
        return view('admin.services.edit',compact('service','admin_users'));
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






//            $mediaValidation = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8000000';
//
//
//
//
//
//
//        $request->validate([
//            'avatar.*' => $mediaValidation,
//            'media_path.*' => $mediaValidation,
//            'user_id' => 'required | integer',
//            'title' => 'required|string|min:6|max:200',
//            'count_like' => 'numeric | nullable',
//            'category_id' => 'required | integer',
//            'description' => 'required|string|min:6|max:400',
//            'promote_expired_at' => ['required', 'string', new Timestamp],
//        ]);



        $this->validator($request);
        $data = $request->all();
        $data['user_id'] = Auth::id();
        if ($request->hasFile('avatar')) {
            $data['avatar']= $this->storeMedia($request->file('avatar')[0],'picture');
        }
        if ($request->hasFile('media_path')) {
            $data['media_path']= $this->storeMedia($request->file('media_path')[0],'picture');
        }
        $service = Service::find($id)->update($data);
        if ($service) {
            Session::flash('alert-info', 'success,سرویس شما با موفقیت به روزرسانی شد');
            return redirect()->route('admin.services.index');
        } else {
            Session::flash('alert-info', 'error,متاسفانه هم اکنون قادر به به روزرسانی سرویس نیستیم، لطفا بعدا امتحان نمایید');
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
        if (Service::destroy($id)) {
            die(true);
        }
        die(false);
    }
}
