<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\SuccessResource;
use App\Post;
use App\Sh4\sh4Action;
use App\Sh4\Sh4HasComment;
use App\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    use sh4Action , Sh4HasComment;

    public function index(Request $request)
    {

        $p['page'] = $request->get('page') ?? 1;
        $p['per'] = $request->get('per') ?? 1;
        $p['offset'] = ($p['page'] - 1) * $p['per'];

        $query = Post::select('id', 'title', 'media_path', 'count_like', 'study_time', 'body')
            ->orderBy('id', 'Desc');

        if ($request->get('q'))
            $query = $query->where('title', 'like', '%' . Input::get('q') . '%');


        if ($p['per'] && $p['page'])
            $query = $query->offset($p['offset'])
                ->limit($p['per']);


        $query = $query->with('categories');

        return new BasicResource($query->get());
    }


    public function show($id)
    {
        $query = Post::select('id', 'title', 'media_path', 'count_like', 'body', 'study_time')
            ->where('id', $id)
            ->with(['categories','tags']);

        return new BasicResource($query->first());
    }


    public function store(PostRequest $request)
    {

        $fields = array_merge($request->except(['categories','tags']), [
            'user_id' => \Auth::id()
        ]);
        $new = Post::create($fields);

        $categories = $request->get('categories') ?? [];
        $tags = $request->get('tags') ?? [];

        $new->categories()->sync($categories);
        $new->tags()->sync($tags);

        return new SuccessResource($new);
    }


    public function update(PostRequest $request, $id)
    {

        $fields = $request->except(['categories','tags']);

        Post::where('id', $id)->update($fields);

        $categories = $request->get('categories') ?? [];
        $tags = $request->get('tags') ?? [];

        Post::find($id)->categories()->sync($categories);
        Post::find($id)->tags()->sync($tags);


        return new SuccessResource();
    }


}
