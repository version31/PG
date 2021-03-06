<?php

namespace App\Http\Controllers\API_V2;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Plan;
use App\Product;
use App\Sh4\Sh4Withdraw;
use App\ShopPlan;
use App\Star;
use App\Storyable;
use App\User;
use App\Variable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use Symfony\Component\Console\Input\Input;

class PaymentPlanController extends Controller
{

    use Sh4Withdraw;



    public function payment($planId, Request $request)
    {
        $user = Auth::user();

        $plan = ShopPlan::find($planId);

        $this->withDraw($plan->price);


        $start = isset($user->shop_expired_at) && Carbon::create($user->shop_expired_at) > Carbon::today() ? Carbon::create($user->shop_expired_at) : Carbon::today();

//        return $start;

        $shop_expired_at = $start->addDays($plan->day);
        $user->update([
            'maximum_product_on_shop' => $plan->maximum_product_on_shop,
            'shop_expired_at' => $shop_expired_at,
            'status' => 1,
        ]);

        return new SuccessResource();

    }



}
