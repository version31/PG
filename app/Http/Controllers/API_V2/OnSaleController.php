<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnSaleRequest;
use App\OnSale;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;

class OnSaleController extends Controller
{

    public $openTime;
    public $limitProduct;

    public function __construct()
    {
        $this->openTime = '00:00:01';
        $this->limitProduct = 100;
    }

    public function index()
    {
        $onSaleTime = new Carbon(Carbon::now()->toDateString() . $this->openTime);

        if (Carbon::now()->format('H') < date('H', strtotime($this->openTime)))
            $onSaleTime->subDay();

        $query = OnSale::limit($this->limitProduct)->orderBy('shops.id', 'desc')
            ->where('shops.published_at', $onSaleTime->toDateString());

        return $query->get();
    }

    public function store(OnSaleRequest $request)
    {
        $fields = $request->only([
            'product_id',
            'new_price',
            'published_at',
        ]);

        OnSale::create($fields);
    }
}
