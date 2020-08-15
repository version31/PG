<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request)
    {

        $p['page'] = $request->get('page') ?? 1;
        $p['per'] = $request->get('per') ?? 1;
        $p['offset'] = ($p['page'] - 1) * $p['per'];


        $query =  Page::select('*');

        if ($p['per'] && $p['page'])
            $query = $query->offset($p['offset'])
                ->limit($p['per']);

        return new BasicResource($query->get());
    }


    public function show($slug)
    {
        $query = Page::where('slug', $slug)->first();
        return new BasicResource($query);
    }
}
