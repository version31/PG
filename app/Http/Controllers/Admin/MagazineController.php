<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MagazineController extends Controller
{
    protected function validator($request){




        $request->validate([
            'name' => 'string|required',
            'count_video' => 'integer|nullable',
            'count_like' => 'integer|nullable',
            'count_product' => 'integer|nullable',
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $magazines = Category::all();
        return view('admin.magazines.index',compact('magazines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.magazines.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {



        $request->validate([
            'name' => 'string|required',
            'media_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8000000'
        ]);
        $data = [];
        if ($request->hasFile('media_path')) {
            $data['media_path']= $this->storeMedia($request->file('media_path'),'picture');
            $data['name']= $request->get('name');
        }
        if (Category::create($data)){
            Session::flash('alert-info','success,مجله با موفقیت افزوده شد،');
            return redirect()->route('admin.magazines.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $magazine = Category::find($id);
        return view('admin.magazines.show',compact('magazine'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $magazine = Category::find($id);
        return view('admin.magazines.edit',compact('magazine'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string|required',
            'media_path.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8000000'
        ]);
        $data = $request->only('name');
//        $data = $request->all();
        if ($request->hasFile('media_path')) {
            $data['media_path']= $this->storeMedia($request->file('media_path')[0],'picture');
        }

        if (Category::find($id)->update($data)){
            Session::flash('alert-info','success,مجله با موفقیت به روزرسانی شد،');
            return redirect()->route('admin.magazines.index');
        }
        Session::flash('alert-info','قادر به به روزرسانی مجله نشدیم');
        return back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Category::destroy($id)){
            die(true);
        }
        die(false);
    }
}
