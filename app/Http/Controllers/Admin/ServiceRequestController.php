<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ServiceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $serviceRequests = \App\Models\Request::whereNotIn('service_id', ['null'])->get();
        return view('admin.serviceRequests.index', compact('serviceRequests'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $users = User::all();
        $services = Service::all();
        $serviceRequest = \App\Models\Request::find($id);
        return view('admin.serviceRequests.edit',compact('users','services','serviceRequest'));
    }

    public function update(Request $request,$id)
    {
        $serviceRequest = \App\Models\Request::find($id)->update($request->all());
        if ($serviceRequest) {

            Session::flash('alert-info', 'success,درخواست سرویس شما با موفقیت به روزرسانی شد');
            return redirect()->route('admin.serviceRequests.index');

        }
        Session::flash('alert-info', 'error,متاسفانه هم اکنون قادر به روزرسانی درخواست سرویس نیستیم، لطفا بعدا امتحان نمایید');
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
        if (\App\Models\Request::destroy($id)) {
            die(true);
        }
        die(false);
    }
}
