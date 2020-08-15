<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\Post;
use App\Sh4\sh4Action;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    use sh4Action;

    public function index(Request $request)
    {

        $p['page'] = $request->get('page') ?? 1;
        $p['per'] = $request->get('per') ?? 1;
        $p['offset'] = ($p['page'] - 1) * $p['per'];

        $query = Post::select('id', 'title', 'media_path', 'count_like', 'study_time')
            ->orderBy('id', 'Desc');

        if ($request->get('q'))
            $query = $query->where('title', 'like', '%' . Input::get('q') . '%');


        if ($p['per'] && $p['page'])
            $query = $query->offset($p['offset'])
                ->limit($p['per']);

        return new BasicResource($query->get());
    }


    public function show($id)
    {
        $query = Post::select('id', 'title', 'media_path', 'count_like', 'body', 'study_time')
            ->where('id', $id);

        return new BasicResource($query->first());
    }

}
