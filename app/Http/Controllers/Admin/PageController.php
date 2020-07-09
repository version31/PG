<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Addable;
use App\Page;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Auth;

class PageController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $count = Page::count();
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $admin_users = User::where('role_id', 1)->get();
        return view('admin.pages.create', compact('admin_users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $request->validate([
            'title' => 'required | string',
            'slug' => 'required | string',
            'body' => 'string | required ',
            'media_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8000000',
//            'additionalImage.*' => 'image|max:1000',
        ]);


        $page = new Page;
        $page->author_id = Auth::user()->id;
        $page->title = $request->input('title');
        $page->slug = $request->input('slug');
        $page->body = $request->input('body');
        ini_set('memory_limit', '10240M');
        if ($request->hasFile('media_path')) {
            $page->media_path = $this->storeMedia($request->file('media_path'), 'picture');
        }

        if ($page->save()) {
            Session::flash('alert-info', 'success,پست شما با موفقیت افزوده شد');
//            if ($request->hasFile('additionalImage')) {
//                foreach ($request->file('additionalImage') as $file) {
//                    $media_path = $this->storeMedia($file,'picture');
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
//                    $addable = $page->addables()->create(['addable_type' => 'Page', 'media_path' => $media_path, 'type' => $type]);
//                }
//            }
            return redirect()->route('admin.pages.index');
        } else {
            Session::flash('alert-info', 'error,متاسفانه هم اکنون قادر به افزودن پست نیستیم، لطفا بعدا امتحان نمایید');
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
        $admin_users = User::where('role_id', 1)->get();
        $page = Page::find($id);


        return view('admin.pages.show', compact('admin_users', 'page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin_users = User::where('role_id', 1)->get();
        $page = Page::find($id);


        return view('admin.pages.edit', compact('admin_users', 'page'));
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


        $request->validate([
            'title' => 'required | string',
            'slug' => 'string | required',
            'body' => 'string | required ',
            'media_path.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8000000',
        ]);

        $page = Page::find($id);
        $page->author_id = Auth::user()->id;
        $page->title = $request->input('title');
        $page->slug = $request->input('slug');
        $page->body = $request->input('body');
        if ($request->hasFile('media_path')) {
            $page->media_path = $this->storeMedia($request->file('media_path')[0], 'picture');
        }

        if ($page->save()) {
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

            Session::flash('alert-info', 'success,پست شما با موفقیت  به روزرسانی شد');
            if ($request->hasFile('additionalImage')) {
                foreach ($request->file('additionalImage') as $file) {
                    $media_path = $this->storeMedia($file, 'picture');
                    $tmp = explode('.', $media_path);
                    $extensions = end($tmp);
                    switch ($extensions) {
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                            $type = 'picture';
                            break;
                        case 'mp4':
                        case 'mov':
                            $type = 'video';

                    }

                    $addable = $page->addables()->create(['addable_type' => 'Page', 'media_path' => $media_path, 'type' => $type]);

                }
            }
            return redirect()->route('admin.pages.index');
        } else {
            Session::flash('alert-info', 'error,متاسفانه هم اکنون قادر به به روزرسانی  پست نیستیم، لطفا بعدا امتحان نمایید');
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
        if (Page::destroy($id)) {
            Addable::where('addable_id', $id)->delete();
            die(true);
        }
        die(false);
    }
}
