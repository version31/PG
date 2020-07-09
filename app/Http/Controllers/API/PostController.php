<?php

namespace App\Http\Controllers\API;

use App\Post;
use App\Sh4\sh4Action;
use Result;
use Illuminate\Support\Facades\Input;
use Auth;
use App\Http\Controllers\Controller;

class PostController extends Controller
{

    use sh4Action;

    /**
     * #2
     */
    public function index()
    {


        $posts = Post::select('id', 'title', 'media_path', 'count_like', 'study_time')->orderBy('id','Desc');

        if (Input::get('q'))
            $posts = $posts->where('title', 'like', '%' . Input::get('q') . '%');

        $data = ['posts' => $posts->get()];
        return Result::setData($data)->get();
    }


    public function show($id)
    {
        $post = Post::select('id', 'title', 'media_path', 'count_like', 'body', 'study_time')->where('id', $id)->get();
        $data = ['post' => $post];
        return Result::setData($data)->get();

    }


}
