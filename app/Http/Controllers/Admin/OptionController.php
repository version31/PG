<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*todo: write cat id in table*/
        $options = Post::find([1,2]);
        return view('admin.options.single',compact('options'));
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
        /*todo: write cat id in table*/
        $terms = Post::find(1);
        $guide = Post::find(2);
        if ($request->input('guide')){
            $guide->body = $request->input('guide');
            $guide->save();
        }
        if ($request->input('terms_and_conditions')){
            $terms->body = $request->input('terms_and_conditions');
            $terms->save();
        }
        Session::flash('alert-info', 'success,تنظیمات با موفقیت به روزرسانی شدند');
        return back();

    }

}
