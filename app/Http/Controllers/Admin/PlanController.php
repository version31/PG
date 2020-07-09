<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PlanController extends Controller
{
    public function validator($request){
        $request->validate([
            'day' => ' nullable | numeric',
            'price' => 'required | numeric',
            'limit_insert_product' => 'numeric | nullable | required_if:type,product,category',
            'limit_insert_video' => 'numeric | nullable | required_if:type,category',
            'type' => 'required | string | in:BUY_PROMOTE_PRODUCT,EXTEND_PROVIDER_PLAN,BUY_PROVIDER_PLAN,BUY_STORY,BUY_PROVIDER_STAR,INCREASE_INSERT_PRODUCT',
            'extra' => ' integer | nullable | required_if:type,star',
        ]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plans = Plan::all();
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.plans.create');
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
        $plan = Plan::create($request->all());
        if ($plan) {
            Session::flash('alert-info', 'success,پلن شما با موفقیت افزوده شد');
            return redirect()->route('admin.plans.index');
        } else {
            Session::flash('alert-info', 'error,متاسفانه هم اکنون قادر به افزودن پلن نیستیم، لطفا بعدا امتحان نمایید');
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
        $plan = Plan::find($id);
        return view('admin.plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $plan = Plan::find($id);
        return view('admin.plans.edit', compact('plan'));
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

        $data = $request->all();
        if ($request->input('type') == 'BUY_STORY'){
            $data['limit_insert_product'] = null;
            $data['limit_insert_video'] = null;
            $data['extra'] = null;
        }else if ($request->input('type') == 'BUY_PROMOTE_PRODUCT'){
            $data['limit_insert_video'] = null;
            $data['extra'] = null;
        }else if ($request->input('type') == 'BUY_PROVIDER_STAR'){
            $data['limit_insert_video'] = null;
            $data['limit_insert_product'] = null;
        }else if ($request->input('type') == 'BUY_PROVIDER_PLAN' || $request->input('type') == 'EXTEND_PROVIDER_PLAN'){
            $data['extra'] = null;
        }
        $this->validator($request);
        $plan = Plan::find($id)->update($request->all());
        if ($plan) {
            Session::flash('alert-info', 'success,پلن شما با موفقیت به روزرسانی شد');
            return redirect()->route('admin.plans.index');
        } else {
            Session::flash('alert-info', 'error,متاسفانه هم اکنون قادر به به روزرسانی پلن نیستیم، لطفا بعدا امتحان نمایید');
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
        if (Plan::destroy($id)) {
            die(true);
        }
        die(false);
    }
}
