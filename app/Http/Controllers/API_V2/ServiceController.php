<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\Service;
use App\Storyable;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $p['page'] = $request->get('page') ?? 1;
        $p['per'] = $request->get('per') ?? 1;
        $p['offset'] = ($p['page'] - 1) * $p['per'];


        $query = Service::orderBy('id', 'Desc')->with('stories');

        if ($p['per'] && $p['page'])
            $query = $query->offset($p['offset'])
                ->limit($p['per']);

        return new BasicResource($query->get());
    }

    public function show($id)
    {
        $query = Service::where('id', $id)->with(['addables', 'stories'])->first();
        return new BasicResource($query);
    }

}
