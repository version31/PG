<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Sh4Helper;
use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\GatewayTransaction;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Service;
use App\Models\Storyable;
use App\Models\User;
use App\Rules\Timestamp;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class StoryController extends Controller
{

    public $types = [
        'provider' => 'App\User',
        'service' => 'App\Service',
        'product' => 'App\Product',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $select = [
            'storyables.id as id',
            'storyables.title' ,
            'storyables.expired_at' ,
            'storyables.status',
            'storyables.storyable_id',
            'storyables.storyable_type',
            'storyables.created_at',
            'gateway_transactions.user_id',
            'gateway_transactions.related_id',
            'gateway_transactions.price',
            'gateway_transactions.status as payment_status', # payment_status
            'users.first_name as first_name',
            'users.last_name as last_name',
            'plans.day',

        ];

//        $select = 'gateway_transactions.*';


        if (Route::currentRouteName() == 'admin.service-stories.index') {
            $stories = Storyable::where('storyable_type', 'App\Service')->get();
            return view('admin.stories.indexServiceStory', compact('stories'));
        } else {

            $stories = GatewayTransaction::
            select($select)
                ->rightJoin('storyables', 'storyables.id', '=', 'related_id')
                ->leftJoin('plans', 'plans.id', '=', 'gateway_transactions.plan_id')
                ->leftJoin('users', 'users.id', '=', 'gateway_transactions.user_id')
                ->groupBy('storyables.id')
                ->orderBy('storyables.id', 'Desc')
//                ->where('plans.type' , 'BUY_STORY')
//                ->whereNotNull('user_id')
//                ->whereNotNull('related_id')
                ->whereNotNull('storyables.id')
                ->get();


//            return $stories;
//            $storiesPushed = Storyable::where('storyable_type', 'App\User')->orWhere('storyable_type', 'App\Service')->orWhere('storyable_type', 'App\Product')->get();


//            return $stories;


            return view('admin.stories.index', compact('stories'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::all();
        $providers = \App\User::isProvider()->get();
        $products = \App\Product::all();
        return view('admin.stories.create', compact('services', 'providers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $storyableId_validate = 'required';

        $fields = $request->all();

        if ($fields['storyable_type'] === 'providers') {
            $storyableId_validate = 'required|exists:users,id';
        } elseif ($fields['storyable_type'] === 'service') {
            $storyableId_validate = 'nullable';
            $fields['storyable_id'] = 0;
        }
//        elseif ($fields['storyable_type'] === 'product')
//            $storyableId_validate = 'required|exists:products,id';


        $request->validate([
            'media_path.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8000000',
            'title' => 'required | string',
            'expired_at' => new Timestamp(),
            'storyable_id' => $storyableId_validate,

        ]);


        $currentRouteName = Route::currentRouteName();
        $request->validate([
            'media_path' => 'required',
        ]);
        /*edit request data and save in other array*/
        $data = $request->all();
        /*save storyable id*/
        if ($currentRouteName == 'admin.stories.store') {
            $data['storyable_id'] = $fields['storyable_id'];
            $data['storyable_type'] = $this->types[$fields['storyable_type']];
        } elseif ($currentRouteName == 'admin.service-stories.store') {
            $data['storyable_id'] = $request->input('select-service');
            $data['storyable_type'] = 'App\Models\Service';
        }
        $data['media_path'] = $this->storeMedia($request->file('media_path')[0], 'picture');

        $expired_at = date('Y-m-d H:i:s', substr($request->input('expired_at'), 0, -3));
        $data['expired_at'] = $expired_at;
        if (Auth::user()->role == 'admin') {
            $data['status'] = 2;
        }

        $store = Storyable::create($data);

        if ($store) {

            Session::flash('alert-info', 'success,استوری شما با موفقیت به ذخیره شد');
            if (Route::currentRouteName() == 'admin.service-stories.store') {
                return redirect()->route('admin.service-stories.index');
            }
            return redirect()->route('admin.stories.index');
        }
        Session::flash('alert-info', 'error,متاسفانه هم اکنون قادر به ذخیره استوری نیستیم، لطفا بعدا امتحان نمایید');
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
        $story = Storyable::find($id);
        $products = Product::all();
        $services = Service::all();
        if (Route::currentRouteName() == 'admin.service-stories.show') {
            return view('admin.service-stories.single', compact('products', 'services', 'story'));
        }
        return view('admin.stories.show', compact('products', 'services', 'story'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


        $story = Storyable::find($id);

//        return $story;
        $products = Product::all();
        $services = Service::all();
        if (Route::currentRouteName() == 'admin.service-stories.store') {
            return view('admin.service-stories.single', compact('products', 'services', 'story'));
        }

//        return $story;

        return view('admin.stories.edit', compact('products', 'services', 'story'));
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
//        return $request->all();

//        return $request->only('expired_at');
        #todo sh4rifi: ahmadian in method ha ro neveshte niaz be eslah dare bekhatere kamboode vaght sare hamesh kardam. badan ok she


        $currentRouteName = Route::currentRouteName();


        $request->validate([
            'media_path.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8000000',
            'title' => 'required | string',
            'expired_at' => new Timestamp(),
        ]);


        /*edit request data and save in other array*/
        $data = $request->all();

//        return $data;
        /*save storyable id*/
//        $data['storyable_id'] = $request->input('select-service');
//        $data['storyable_type'] = $this->types[$fields['storyable_type']];
        if ($request->hasFile('media_path')) {
            $data['media_path'] = $this->storeMedia($request->file('media_path')[0], 'picture');
        }


        $data['expired_at'] = $this->adaptDate($request->input('expired_at'));


        $store = Storyable::find($id)->update($data);

        if ($store) {
            Session::flash('alert-info', 'success,استوری شما با موفقیت به روزرسانی شد');
            if ($currentRouteName == 'admin.stories.update') {
                return redirect()->route('admin.stories.index');
            }
            return redirect()->route('admin.service-stories.index');
        }
        Session::flash('alert-info', 'error,متاسفانه هم اکنون قادر به روزرسانی استوری نیستیم، لطفا بعدا امتحان نمایید');
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
        if (Storyable::destroy($id)) {
            die(true);
        }
        die(false);
    }
}
