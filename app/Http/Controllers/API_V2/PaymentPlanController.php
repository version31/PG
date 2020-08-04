<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Plan;
use App\Product;
use App\Star;
use App\Storyable;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use Symfony\Component\Console\Input\Input;

class PaymentPlanController extends Controller
{
    //

    public function payment($planId, Request $request)
    {

        $userId = Auth::user()->id;
        $related_id = $request->get('related_id');
        $plan = Plan::where('id', $planId)->first();
        $user = User::where('id', $userId)->first();

        $user->deposit($plan->price );
        $user->withdraw($plan->price);

        switch ($plan->type) {
            case "BUY_PROVIDER_PLAN": #1
                $limit_insert_product = $user->limit_insert_product + $plan->limit_insert_product;
//                            $start = new \Carbon\Carbon($user->shop_expired_at);  @todo it has bug. if a user expired last year package time will added to the time
                $start = Carbon::now();
                $shop_expired_at = $start->addDays($plan->day);
                $user->where('id', $userId)->update([
                    'limit_insert_product' => $limit_insert_product,
                    'shop_expired_at' => $shop_expired_at,
                    'status' => 1,
                ]);

                $user->syncRoles(['provider']);
                break;
            case "EXTEND_PROVIDER_PLAN": #2
                $limit_insert_product = $user->limit_insert_product + $plan->limit_insert_product;
                //$start = new \Carbon\Carbon($user->shop_expired_at);  @todo it has bug. if a user expired last year package time will added to the time
                $start = Carbon::now();
                $shop_expired_at = $start->addDays($plan->day);
                $user->where('id', $userId)->update(['limit_insert_product' => $limit_insert_product, 'shop_expired_at' => $shop_expired_at]);
                break;
            case "BUY_PROVIDER_STAR": #3
                $star = Star::where('user_id', $userId)
//                                ->where('star', $plan->extra)
                    ->first();
                if ($star) {
                    $start = new \Carbon\Carbon($star->star_expired_at);
                    $star_expired_at = $start->addDays($plan->day);
                    Star::where('user_id', $userId)->where('star', $plan->extra)->update(['star_expired_at' => $star_expired_at]);
                } else {
                    $start = Carbon::now();
                    $star_expired_at = $start->addDays($plan->day);
                    Star::insert([
                        'user_id' => $userId,
                        'star_expired_at' => $star_expired_at,
                        'star' => $plan->extra,
                    ]);
                }
                break;
            case "BUY_STORY": #4
                $start = Carbon::now();
                $expired_at = $start->addDays($plan->day);
                # @todo  Mr Farbod should send me related_id : related_id is story_id
                Storyable::where('id', $related_id)
                    ->update([
                        'expired_at' => $expired_at,
                        'status' => 0,
                    ]);
                break;
            case "BUY_PROMOTE_PRODUCT": #5
                Log::emergency($related_id);
                Product::where('id', $related_id)->first();
                $start = Carbon::now();
                $promote_expired_at = $start->addDays($plan->day);
                Product::where('id', $related_id)->update(['promote_expired_at' => $promote_expired_at]);
                break;
            case "INCREASE_INSERT_PRODUCT": #6
                $user->increment('limit_insert_product', $plan->limit_insert_product);
                break;
            default:
                return new ErrorResource(["unsupported" => "برای این پلن چیزی در بک-اند هندل نشده است"]);
        }

        return new SuccessResource("پلن با موفقیت خریداری شد");

    }
}
