<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnSaleRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\OnSaleCollection;
use App\Http\Resources\SuccessResource;
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

    public function index(Request $request)
    {
        $onSaleTime = new Carbon(Carbon::now()->toDateString() . $this->openTime);

        if (Carbon::now()->format('H') < date('H', strtotime($this->openTime)))
            $onSaleTime->subDay();

        $query = OnSale::limit($this->limitProduct)->orderBy('shops.id', 'desc')
            ->where('shops.published_at', $onSaleTime->toDateString());




        $p['page'] = $request->get('page') ?? 1;
        $p['per'] = $request->get('per') ?? 1;
        $p['offset'] = ($p['page'] - 1) * $p['per'];


        if ($request->get('q'))
            $query = $query->where('title', 'like', '%' . $request->get('q') . '%');

        if ($p['per'] && $p['page'])
            $query = $query->offset($p['offset'])
                ->limit($p['per']);


        return OnSaleCollection::collection($query->get());
    }

    public function store(OnSaleRequest $request)
    {
        $fields = $request->only([
            'product_id',
            'new_price',
            'published_at',
        ]);

        OnSale::create($fields);

        return new SuccessResource();
    }
}
