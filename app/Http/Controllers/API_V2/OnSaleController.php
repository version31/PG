<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\OnSale;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;

class OnSaleController extends Controller
{
    public function index()
    {
         $openTime = '17:00:00';
         $limitProduct = 100;

        $from = new Carbon(Carbon::now()->toDateString() . $openTime);

        if (Carbon::now()->format('H') < date('H', strtotime($openTime)))
            $from->subDay();


        $to = new Carbon($from);
        $to->addDay();


        $query = OnSale::limit($limitProduct)->orderBy('shops.id', 'desc')
            ->where('shops.published_at', '>', $from->toDateTimeString())
            ->where('shops.published_at', '<', $to->toDateTimeString());

        return $query->get();
    }
}
