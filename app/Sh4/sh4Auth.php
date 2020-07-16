<?php

namespace App\Sh4;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AfterLoginResource;
use App\SMSLog;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ipecompany\Smsirlaravel\Smsirlaravel;
use Result;
use Spatie\Permission\Models\Role;
use Validator;


trait sh4Auth
{
    public $successStatus = 200;

    protected $limitLog = [
        'deviceId' => 400,
        'mobile' => 400,
        'ip' => 400,
    ];

    public $limitMinute = 5;


    public $first_limit_insert_product = 10;


    public function loginWithPassword(Request $request)
    {
        if (Auth::attempt(['mobile' => request('mobile'), 'password' => request('password')])) {
            $user = Auth::user();
            $result = $this->setResultAfterLogin($user);
        } else {
            $result = Result::setErrors(['unauthorised' => ["اطلاعات وارد شده صحیح نمی باشد"]]);
        }
        return $result->get();


//
//        $credentials = request(['mobile', 'password']);
//
//        if (!Auth::attempt($credentials))
//            return response()->json([
//                'message' => 'Unauthorized'
//            ], 401);
//        $user = $request->user();
//        $tokenResult = $user->createToken('Personal Access Token');
//        $token = $tokenResult->token;
//        if ($request->remember_me)
//            $token->expires_at = Carbon::now()->addWeeks(1);
//        $token->save();
//        return response()->json([
//            'access_token' => $tokenResult->accessToken,
//            'token_type' => 'Bearer',
//            'expires_at' => Carbon::parse(
//                $tokenResult->token->expires_at
//            )->toDateTimeString()
//        ]);


    }


    private function register(RegisterRequest $request)
    {
        $input['mobile'] = $request->get('mobile');
        $input['password'] = bcrypt($request->get('password'));


        $user = User::where('mobile', $input['mobile'])->first();

        if ($user) {
            $user->update($input);
        } else {
            $input['limit_insert_product'] = $this->first_limit_insert_product;
            $input['shop_expired_at'] = Carbon::now()->addYears(5);
            $user = User::create($input);

            $role = Role::where('name', 'user')->first();
            $user->assignRole($role);
        }


        return $this->setResultAfterLogin($user);

    }


    public function details()
    {
        $user = Auth::user();
        $result = $this->setResultAfterLogin($user);
        return $result->get();
    }

    #STEP01
    public function getMobile(Request $request)
    {

        $mobile = $request->input('mobile');
        $device_id = $request->input('device_id');
        $ip = \Request::ip();


        $validator = Validator::make($request->all(), [
            'mobile' => 'required|regex:/(09)[0-9]{9}/',
        ]);

        $limitSmsSend = Carbon::now()->subMinutes($this->limitMinute);

        $countSmsInLimitationTime = SMSLog::where('mobile', $mobile)
            ->where('created_at', '>=', $limitSmsSend)
            ->count();

        if ($validator->fails()) {
            $result = Result::setErrors($validator->errors());

        } elseif ($countSmsInLimitationTime) {

            $result = Result::setErrors(['sms-limitation' => 'کد فعال سازی برای شما ارسال شده است']);

        } else {

            $error = $this->setErrorInResultIfLimitSms($mobile, $device_id);
            if ($error) {

                $result = Result::setErrors($error);
            } else {

                SMSLog::setLog($mobile, $ip, $device_id);
                $code = SMSLog::where('mobile', $mobile)->orderBy('id', 'Desc')->value('code');
                //$option = new OptionController();
                //$option->sendSmsCode($code, $mobile);
                $this->sendSMSCode($code, $mobile);

                $result = Result::setData([
                    'mobile' => $mobile,
                    'code' => $code #todo
                ]);
            }
        }


        return $result->get();

    }


    #STEP02
    public function getCode(Request $request)
    {

        $result = null;
        $code = $request->only('code');
        $mobile = $request->only('mobile');
        $log = SMSLog::validFromLog($code, $mobile);

        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'code' => 'required',
            'device_id' => 'required',
        ]);


        if ($validator->fails()) {
            $result = Result::setErrors([$validator->errors()]);
            $user = false;
        } elseif (!$log) {
            $result = Result::setErrors(['wrong_code' => ['wrong code']]);
        } else {
            $user = User::select('*')
                ->where('mobile', $request->only('mobile'))
                ->first();
        }

        if (isset($user) && !$validator->fails() && $log) {
            return $this->setResultAfterLogin($user);
        } else if (!$validator->fails() && $log) {
            $data = [
                'user_registered' => false,
                'mobile' => $request->get('mobile'),
            ];

            $result = Result::setData($data);
        }
        return $result->get();
    }


    #STEP03
    protected function registerWithCode(RegisterRequest $request)
    {

        $code = $request->only('code');
        $mobile = $request->only('mobile');
        $log = SMSLog::validFromLog($code, $mobile);
        if ($log) {
//            $incomplete_user = User::where('mobile', $mobile)->first();
//
//            if ($incomplete_user)
//                User::find($incomplete_user->id)->delete();

            return $this->register($request);
        } else {
            $error['wrong_code'] = ["wrong code"];
            $result = Result::setErrors($error);
        }

        return $result->get();
    }


    private function setErrorInResultIfLimitSms($mobile, $device_id)
    {
        $error = null;
        $countMobile = SMSLog::where('mobile', $mobile)->count();
        $countIP = SMSLog::where('ip', \Request::ip())->count();
        $countDeviceID = SMSLog::where('device_id', $device_id)->count();

        if ($countMobile > $this->limitLog['mobile']) {
            $error["countMobile"] = ["count-mobile"];
        }
        if ($countIP > $this->limitLog['ip']) {
            $error['countIP'] = ["count-ip"];

        }
        if ($countDeviceID > $this->limitLog['deviceId']) {
            $error['countDeviceId'] = ["countDeviceId"];
        }
        return $error;
    }


    public function setResultAfterLogin($user)
    {

        $user = User::where('id', $user->id)->first();


        $data = new \stdClass();

        $data->token = $user->createToken('MP')->accessToken;
        $data->user_registered = true;
        $data->status = $user->status;
        $data->mobile = $user->mobile;
        $data->roles = $user->getRoleNames();
        $data->name = $user->name;
        $data->id = $user->id;


        return new AfterLoginResource($data);
//        return Result::setData($data);
    }


    public function sendSMSCode($code, $mobile)
    {
//        $username = "beigi";
//        $password = '09369659219';
//        $from = "+9850002620000606";
//        $pattern_code = "120";
//        $to = [$mobile];
//        $input_data = array("confirmation-code" => $code);
//        $url = "http://37.130.202.188/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
//        $handler = curl_init($url);
//        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
//        curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
//        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
//        $response = curl_exec($handler);
//        return $response;


        $message = 'کد فعال سازی شما: ' . $code . '  پارسیانگرام  ';
//
//        Smsirlaravel::send($message, $mobile);

        Smsirlaravel::ultraFastSend(['VerificationCode' => $code], 9606, $mobile);

        return true;
    }


    public function deleteLog()
    {
        \DB::table('sms_logs')->delete();
        return "logs dropped successfully!";
    }



//    public function v1_setResultAfterLogin($user)
//    {
//        $user = User::where('id', $user->id)->first();
//
//        $data['token'] = $user->createToken('MP')->accessToken;
//        $data['user_registered'] = true;
//        $data['status'] = $user->status;
//        $data['mobile'] = $user->mobile;
//        $data['role'] = $user->role;
//        $data['name'] = $user->name;
//        $data['id'] = $user->id;
//        return Result::setData($data);
//    }

//    private function v1_register(Request $request)
//    {
//
//
//        Log::emergency($request->all()); #todo test
//
//        $validator = Validator::make($request->all(), [
//            'mobile' => 'required',
//            'password' => 'required'
//        ]);
//
//        $role = $request->get('role');
//
//
//        switch ($role) {
//            case "user":
//                $input['role_id'] = 3;
//                break;
//            case "provider":
//                $input['role_id'] = 2;
//                $input['status'] = 0;
//                break;
//
//            default:
//                return Result::setErrors(['wrong-role'])->get();
//        }
//
//        if ($validator->fails()) {
//            $result = Result::setErrors([$validator->errors()]);
//        } else {
//            $input['mobile'] = $request->get('mobile');
//            $input['password'] = bcrypt($request->get('password'));
//
//
//            $user = User::where('mobile', $input['mobile'])->first();
//
//            if ($user)
//                $user->update($input);
//            else
//                $user = User::create($input);
//
//
//            $result = $this->setResultAfterLogin($user);
//        }
//
//        return $result->get();
//    }

}
