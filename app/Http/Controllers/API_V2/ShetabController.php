<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Paymentable;
use App\User;
use App\Variable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

use Auth;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;

class ShetabController extends Controller
{

    private $amount = 1010;


    public function do(Request $request)
    {
        $amount = $request->get('amount');
        $this->amount = $amount;
        return $this->payment();
    }


    public function payment()
    {

        $invoice = (new Invoice)->amount($this->amount);

        $pay = Payment::callbackUrl('http://dev.parsiangram.com/api/v2/callback')->purchase(
            $invoice,
            function ($driver, $transactionId) {


                \App\Payment::create([
                    'port' => 'MELLAT',
                    'price' => $this->amount,
                    'ref_id' => $transactionId,
                    'status' => 'INIT',
                    'ip' => \Request::ip(),
                    'user_id' => Auth::id() ?? null,
                ]);

                return response()
                    ->json([
                        'url' => 'https://bpm.shaparak.ir/pgwchannel/payment.mellat?RefId=' . $transactionId,
                        'method' => 'GET',
                        'target' => '_self',
                    ]);

            }
        )->pay()->toJson();


        $pay = json_decode($pay, true);


        $data['data']['url'] = "https://bpm.shaparak.ir/pgwchannel/payment.mellat?RefId=" . $pay['inputs']['RefId'];
        $data['data']['method'] = 'GET';
        $data['data']['target'] = '_self';


        return response()->json($data);
    }


    public function callback(Request $request)
    {

        $transactionId = $request->get('RefId');


        $payment = \App\Payment::where('ref_id', $transactionId);


        $amount = (int)$payment->first()->price;
        $userId = $payment->first()->user_id;
        $user = User::find($userId);


        try {
            $receipt = Payment::amount($amount)->transactionId($transactionId)->verify();

            // You can show payment referenceId to the user.


            $user->deposit($amount, ["افزایش موجودی کیف پول"]);


            $gift_amount = (int)Variable::getValue('GIFT_BUY_PERCENT') * $amount / 100;


            $user->deposit($gift_amount, ["هدیه ی افزایش موجودی"]);

            $message = ' تراکنش با موفقیت سمت بانک تایید گردید';
            $message .= '    ';
            $message .= 'کد پیگیری:';
            $message .= $receipt->getReferenceId();
            $url = 'parsiangram://status=success';


            $payment->update([
                'status' => 'SUCCEED',
                'tracking_code' => $receipt->getReferenceId(),
            ]);
            return view('callback')->with(['message' => $message, 'url' => $url]);


        } catch (InvalidPaymentException $exception) {
            /**
             * when payment is not verified, it will throw an exception.
             * We can catch the exception to handle invalid payments.
             * getMessage method, returns a suitable message that can be used in user interface.
             **/


            if ($exception->getMessage() != 43) {
                $payment->update([
                    'status' => 'FAILED',
                ]);
                $message = 'تراکنش با خطا روبرو شد';
                $message .= '    ';
                $message .= 'کد خطا: ' . $exception->getMessage();
            } else {
                $message = 'تراکنش قبلا از طرف بانک تایید شده است';
                $message .= '    ';
                $message .= 'کد پیگیری:';
                $message .= $payment->first()->tracking_code;

            }


            $url = 'parsiangram://status=success';
            return view('callback')->with(['message' => $message, 'url' => $url]);


        }


    }
}
