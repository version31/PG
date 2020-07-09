<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\GatewayTransaction;
use App\Models\GatewayTransactionsLog;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PriorityPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $priorityPages =  GatewayTransaction::
        select(
            'users.first_name as first_name' ,'users.last_name as last_name' ,
            'gateway_transactions.created_at' , 'gateway_transactions.user_id' , 'plans.day', 'plans.extra as star',
            'gateway_transactions.related_id as product_id','gateway_transactions.status','gateway_transactions.id',
            'gateway_transactions.price'
        )
            ->leftJoin('plans', 'plans.id', '=', 'gateway_transactions.plan_id')
            ->leftJoin('users', 'users.id', '=', 'gateway_transactions.user_id')
            ->where('plans.type' , 'BUY_PROVIDER_STAR')
            ->get()
        ;




        return view('admin.priorityPages.index',compact('priorityPages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provider_role_id = Role::where('name','provider')->first()->id;
        $providers = User::where('role_id',$provider_role_id)->get();
        $products = Product::all();
        $plans = Plan::where('type','BUY_PROMOTE_PRODUCT')->get();
        return view('admin.priorityPages.create',compact('products','plans','providers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $product = Product::find($data['product_id']);
        $plan = Plan::find($data['plan_id']);
        $details = ([
            'type' => $product,
            'plan' => $plan
        ]);
        $data['detail'] = $details;
        $data['user_id'] = $product->user_id;

        if (GatewayTransaction::create($data)){
            GatewayTransactionsLog::create([
                'transaction'
            ]);
            Session::flash('alert-info', 'success,ارتقا محصول شما با موفقیت ایجاد گردید');
            return redirect()->route('admin.priorityPages.index');
        }
        else{
            Session::flash('alert-info', 'error,ارتقا محصول شما با موفقیت ایجاد گردید');
            return back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PriorityProduct  $priorityPage
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $provider_role_id = Role::where('name','provider')->first()->id;
        $providers = User::where('role_id',$provider_role_id)->get();
        $products = Product::all();
        $plans = Plan::where('type','BUY_PROMOTE_PRODUCT')->get();
        return view('admin.priorityPages.show',compact('products','plans','providers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PriorityProduct  $priorityPage
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $provider_role_id = Role::where('name','provider')->first()->id;
        $providers = User::where('role_id',$provider_role_id)->get();
        $products = Product::all();
        $plans = Plan::where('type','BUY_PROMOTE_PRODUCT')->get();
        $priorityPage = GatewayTransaction::find($id);
        return view('admin.priorityPages.update',compact('products','plans','providers','priorityPage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PriorityProduct  $priorityPage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PriorityProduct $priorityPage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PriorityProduct  $priorityPage
     * @return \Illuminate\Http\Response
     */
    public function destroy(PriorityProduct $priorityPage)
    {
        //
    }
}
