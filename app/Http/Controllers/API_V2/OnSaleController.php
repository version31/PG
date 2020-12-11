<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnSaleRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\OnSaleCollection;
use App\Http\Resources\SuccessResource;
use App\OnSale;
use App\Variable;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;

class OnSaleController extends Controller
{

    public function index(Request $request)
    {
        $onSaleTime = new Carbon(Carbon::now()->toDateString() . Variable::val('OnSaleOpenTime'));

        if (Carbon::now()->format('H') < date('H', strtotime(Variable::val('OnSaleOpenTime'))))
            $onSaleTime->subDay();

        $query = OnSale::limit(Variable::val('OnSaleLimitProduct'))->orderBy('shops.id', 'desc')
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
