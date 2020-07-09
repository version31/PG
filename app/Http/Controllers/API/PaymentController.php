<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Order;
use App\OrderItem;
use App\Payment;
use App\Paymentable;
use App\Plan;
use App\Product;
use App\Star;
use App\Storyable;
use App\User;
use Auth;
use Carbon\Carbon;
use Gateway;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Larabookir\Gateway\Mellat\Mellat;
use Result;

class PaymentController extends Controller
{
    private $callback;
    private $order;
    private $relatedId;

    /**
     * PaymentController constructor.
     * @param $callback
     */
    public function __construct()
    {
        $this->order = new Order();
    }


    private function setCallback($type, $id, $related_id = null, $refer = null): void
    {
        $this->callback = env('APP_URL') . '/api/v1/payments/' . $type . '/' . $id . '/callback?related_id=' . $related_id . '&refer=' . $refer;
    }


    public function getCallback()
    {
        return $this->callback;
    }


    public function index()
    {
//        Auth::loginUsingId(266); //todo for tes
        $type = Input::get('type');
        $id = Input::get('id');
        $relatedId = Input::get('related_id');
        $this->relatedId = $relatedId;
        $refer = Input::get('refer');
        $model = Plan::class;

        $this->setCallback($type, $id, $relatedId, $refer);
        $gateway = Gateway::make(new Mellat());
        $this->order->setItems(new OrderItem($model, $id, $relatedId));

        return $this->payment($gateway);

    }


    public function payment($gateway)
    {
        try {
            try {
                $gateway->setCallback(url($this->getCallback()));
                $gateway->price($this->order->getTotal())->ready();

                $order['user_id'] = Auth::user()->id;
                $order['details'] = $this->order->getItems();
                $order['plan_id'] = $this->order->getItems()['items'][0]['paymentable_id'];
                $order['related_id'] = $this->order->getItems()['items'][0]['related_id'];

                Payment::find($gateway->transactionId())->update($order);

                $data = $gateway->redirect();


                return Result::setData($data)->get();

            } catch (\Exception $e) {

                echo $e->getMessage();
            }

        } catch (\Exception $e) {

            echo $e->getMessage();
        }

    }


    public function callback($paymentable_type, $paymentable_id)
    {

        $refer = Input::get('refer');

        try {
            $gateway = \Gateway::verify();
            $trackingCode = $gateway->trackingCode();
            $refId = $gateway->refId();
            $userId = Payment::where('ref_id', $refId)->first()->user_id;
            $cardNumber = $gateway->cardNumber();



//            echo $trackingCode;
//            echo "<br/>";
//            echo $refId;
//            echo "<br/>";
//            echo $cardNumber;


            if ($trackingCode) {
                if ($paymentable_type == 'plan') {
                    $plan = Plan::where('id', $paymentable_id)->first();
                    $user = User::where('id', $userId)->first();


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
                                'role_id' => 2
                            ]);
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
                            Storyable::where('id', Input::get('related_id'))
                                ->update([
                                    'expired_at' => $expired_at,
                                    'status' => 0,
                                ]);


                            break;
                        case "BUY_PROMOTE_PRODUCT": #5
                            Log::emergency(Input::get('related_id'));
                            Product::where('id', Input::get('related_id'))->first();
                            $start = Carbon::now();
                            $promote_expired_at = $start->addDays($plan->day);
                            Product::where('id', Input::get('related_id'))->update(['promote_expired_at' => $promote_expired_at]);
                            break;

                        case "INCREASE_INSERT_PRODUCT": #6
                            $user->increment('limit_insert_product', $plan->limit_insert_product);
                            break;
                        default:
                            echo "Something Wrong!";
                    }

                    $message = 'پرداخت با موفقیت انجام شد';
                    if ($refer == 'pwa')
                        $url = 'https://parsiangram.com/profile';
                    else
                        $url = 'parsiangram://status=success';
                    return view('callback')->with(['message' => $message, 'url' => $url]);
                }
            }


        } catch (\Larabookir\Gateway\Exceptions\RetryException $e) {


            // تراکنش قبلا سمت بانک تاییده شده است و
            // کاربر احتمالا صفحه را مجددا رفرش کرده است
            // لذا تنها فاکتور خرید قبل را مجدد به کاربر نمایش میدهیم

            $message = 'تراکنش قبلا سمت بانک تاییده شده است';

            if ($refer == 'pwa')
                $url = 'https://parsiangram.com/profile';
            else
                $url = 'parsiangram://status=cancel';
            return view('callback')->with(['message' => $message, 'url' => $url]);


            echo $e->getMessage() . "<br>";

        } catch (\Exception $e) {

            // نمایش خطای بانک

            Log::emergency($e->getMessage());

            $message = 'پرداخت شما با خطا روبرو شد';

//            echo $e->getMessage();

            if ($refer == 'pwa')
                $url = 'https://parsiangram.com/profile';
            else
                $url = 'parsiangram://status=cancel';
            return view('callback')->with(['message' => $message, 'url' => $url]);


        }
    }
}
