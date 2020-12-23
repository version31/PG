<?php

namespace App\Http\Controllers\API_V2;

use App\BannerPlan;
use App\BannerRequest;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequestRequest;
use App\Http\Resources\SuccessResource;
use App\User;
use Illuminate\Http\Request;
use Auth;

class BannerController extends Controller
{

    private $totalPrice;


    public function plans()
    {
        return BannerPlan::all();
    }

    public function store(BannerRequestRequest $request)
    {
        $this->setTotalPrice($request);
        $this->checkBalanceWallet($request);


        $fields = array_merge($request->all() , ['total_price' => $this->totalPrice]);


        $new = BannerRequest::create($fields);

        if ($new)
            Auth::user()->withDraw($this->totalPrice, ["رزرو بنر"]);

        return new SuccessResource($new);
    }


    private function checkBalanceWallet($request, $errors = [])
    {
        if (Auth::user()->balance < $this->totalPrice)
            $errors[] = ['increase_balance' => 'موجودی شما کافی نیست. لطفا موجودی خود را افزایش دهید'];

        if (count($errors) > 0)
            throw new CustomException($errors);

    }


    private function setTotalPrice($request)
    {
        $dayPrice = BannerPlan::find($request->get('banner_plan_id'))->day_price;
        $day = $request->get('day');
        $this->totalPrice = $day * $dayPrice;
    }
}
