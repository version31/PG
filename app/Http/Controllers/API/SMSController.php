<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class SMSController extends Controller
{

    public function send($mobile = "09185257989")
    {
        $code = rand(1000, 9999);
        $username = "beigi";
        $password = '09369659219';
        $from = "+98100009";
        $patternCode = "120";
        $to = array($mobile);
        $inputData = array("confirmation-code" => $code);
        $url = "http://37.130.202.188/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($inputData)) . "&pattern_code=$patternCode";
        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $inputData);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handler);
        if ($response) {
            "send sms Code";
        }
    }


}
