<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Direct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $directs = Direct::all();
        return view('admin.directs.index',compact('directs'));
    }

    /*
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $direct = Direct::find($id);
        return response($direct->body);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Direct::destroy($id)){
            die(true);
        }
        die(false);
    }
}
