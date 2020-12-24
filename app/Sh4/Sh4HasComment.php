<?php


namespace App\Sh4;


use App\BannerRequest;
use App\Comment;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\Request;

trait Sh4HasComment
{

    public $commentModels = [
        'App\Http\Controllers\API_V2\PostController' => 'App\Post',
        'App\Http\Controllers\API_V2\ProductController' => 'App\Product',
    ];

    public function commentStore(CommentRequest $request, $id)
    {
        $comment = new Comment($request->all());
        $new = $this->commentModels[get_class($this)]::find($id)->comments()->save($comment);
        return new SuccessResource($new);
    }

    public function commentIndex(Request $request, $id)
    {
        $query =Comment::query()
            ->where('commentable_id', $id)
            ->where('commentable_type', $this->commentModels[get_class($this)])
            ;

        $query->hasPagination($request , 3);


        return new BasicResource($query->get());
    }

}
