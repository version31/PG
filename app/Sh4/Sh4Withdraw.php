<?php


namespace App\Sh4;


use App\Exceptions\CustomException;
use Auth;

trait Sh4Withdraw
{

    private function withDraw($price, $errors = [])
    {

        if ($price > Auth::user()->balance)
            $errors[] = ['increase_balance' => 'موجودی شما کافی نیست. لطفا موجودی خود را افزایش دهید'];


        if (count($errors) > 0)
            throw new CustomException($errors);


        Auth::user()->withdraw($price, ["خرید یا تمدید پلن فروشگاهی"]);
    }

}
