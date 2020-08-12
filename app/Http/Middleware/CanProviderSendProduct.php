<?php

namespace App\Http\Middleware;

use App\Http\Resources\ErrorResource;
use Carbon\Carbon;
use Closure;
use Auth;

class CanProviderSendProduct
{


    public function handle($request, Closure $next)
    {
        $errors = [];

        if (Auth::user()->shop_expired_at < Carbon::now())
            $errors[] = ['shop_expired' => 'فروشگاه شما منقضی شده است. لطفا فروشگاه خود را تمدید نمایید'];

        if (Auth::user()->limit_insert_product < 1)
            $errors[] = ['limit_insert_product' => 'لطفا بسته ی درج محصول را تمدید نمایید.'];


        if (Auth::user()->status < 1)
            $errors[] = ['status' => 'کاربری شما غیر فعال است. لظفا با مدیریت تماس بگیرید'];


        if (count($errors) > 0)
            return new ErrorResource($errors);


        return $next($request);
    }
}
