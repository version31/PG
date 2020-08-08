<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\Order;
use App\OrderItem;
use App\Payment;
use App\Paymentable;
use App\Plan;
use App\Product;
use App\Star;
use App\Storyable;
use App\User;
use App\Variable;
use Auth;
use Carbon\Carbon;
use Gateway;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Larabookir\Gateway\Mellat\Mellat;
use Result;


use Illuminate\Http\Request;

class PaymentController extends Controller
{

    private $amount;


    public function do(Request $request)
    {
        $amount = $request->get('amount');
        $this->amount = $amount;
        $gateway = Gateway::make(new Mellat());
        return $this->payment($gateway);
    }


    private function payment($gateway)
    {
        try {


            $gateway = \Gateway::make('mellat');

            $gateway->setCallback(url('/api/v2/callback')); // You can also change the callback
            $gateway->price($this->amount * 10)
                ->ready();


            $order['user_id'] = Auth::user()->id;
//            Payment::find($gateway->transactionId())->update($order);


            return new BasicResource($gateway->redirect());

        } catch (\Exception $e) {

            echo $e->getMessage();
        }

    }


    public function callback($paymentable_type, $paymentable_id)
    {

        try {

            $gateway = \Gateway::verify();
//            $trackingCode = $gateway->trackingCode();
//            $cardNumber = $gateway->cardNumber();
            $refId = $gateway->refId();
            $payment = Payment::where('ref_id', $refId)->first();
            $user = User::find($payment->user_id);

            $amount = ((int)$payment->price) / 10;

            $user->deposit($amount, ["افزایش موجودی کیف پول"]);


            $gift_amount = (int)Variable::getValue('GIFT_BUY_PERCENT') * $amount / 100;


            $user->deposit($gift_amount, [Variable::getValue('META_TRANSACTION_BUY_GIFT')]);

            $message = ' تراکنش با موفقیت سمت بانک تایید گردید';
            $url = 'parsiangram://status=success';
            return view('callback')->with(['message' => $message, 'url' => $url]);
            echo $e->getMessage();

        } catch (\Larabookir\Gateway\Exceptions\RetryException $e) {

            $message = 'تراکنش قبلا سمت بانک تاییده شده اس';
            $url = 'parsiangram://status=success';
            return view('callback')->with(['message' => $message, 'url' => $url]);

            echo $e->getMessage() . "<br>";

        } catch (\Exception $e) {

            $message = 'تراکنش با خطا روبرو شد';
            $url = 'parsiangram://status=success';
            return view('callback')->with(['message' => $message, 'url' => $url]);
            echo $e->getMessage();
        }
    }
}
