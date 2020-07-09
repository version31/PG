<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Direct;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDirectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $directs = Direct::groupBy('user_id')->where('receiver_id',Auth::id())->orderBy('created_at','desc')->get();
        return view('admin.adminDirects.index',compact('directs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currentDirect = Direct::find($request->input('id'));
        if ($currentDirect->user_id == Auth::id()){
            $sender_id = $currentDirect->receiver_id;
        }else{
            $sender_id = $currentDirect->user_id;
        }
        $direct = Direct::create([
            'receiver_id' => $sender_id,
            'user_id' => Auth::id(),
            'body' => $request->input('body'),
            'created_at' => date('Y-m-d H:i:s',time()),
        ]);
        if ($direct){
            die(true);
        }
        die(false);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $current_direct = Direct::find($id);
        if ($current_direct->user_id == Auth::id()){
            $sender_id = $current_direct->receiver_id;
        }else{
            $sender_id = $current_direct->user_id;
        }
        $allDirectSender = Direct::where([
            ['user_id', Auth::id()],
            ['receiver_id', $sender_id],
        ])->orWhere(
            [
                ['receiver_id', Auth::id()],
                ['user_id', $sender_id],
            ]
        )
            ->orderBy('created_at','asc')->get();
        return view('admin.adminDirects.directs',compact('allDirectSender'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
