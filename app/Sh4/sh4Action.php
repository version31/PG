<?php

namespace App\Sh4;

use App\Bookmarkable;
use App\Category;
use App\Http\Requests\ActionRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Likeable;
use App\Product;
use App\User;
use Auth;
use Bavix\Wallet\Services\CommonService;
use Illuminate\Http\Request;
use Result;

trait sh4Action
{

    public $errors = [];


    public function act(ActionRequest $request)
    {


        $detail = null;

        $id = $request->get('id');


        $models = [
            'App\Http\Controllers\API_V2\PostController' => 'App\Post',
            'App\Http\Controllers\API_V2\CategoryController' => 'App\Category',
            'App\Http\Controllers\API_V2\ProductController' => 'App\Product',
            'App\Http\Controllers\API_V2\UserController' => 'App\User',
        ];


        $like = Likeable::where('user_id', Auth::id())->where('likeable_type', $models[get_class($this)])->where('likeable_id', $id);

        $bookmark = Bookmarkable::where('user_id', Auth::id())->where('bookmarkable_type', $models[get_class($this)])->where('bookmarkable_id', $id);


        $act = $request->get('act');


        switch ($act) {
            case "like":
                $exists = Likeable::where(['user_id' => Auth::id(), 'likeable_type' => $models[get_class($this)], 'likeable_id' => $id])->exists();
                if (!$exists)
                    $liked = Likeable::insert(['user_id' => Auth::id(), 'likeable_type' => $models[get_class($this)], 'likeable_id' => $id]);
                else
                    $liked = false;

                if ($liked) {
                    $models[get_class($this)]::where('id', $id)->increment('count_like');
                    User::find(1)->increment('count_like');


                    if ($models[get_class($this)] == 'App\Product') {
                        $userId = Product::find($id)->user_id;
                        User::find($userId)->increment('count_like');


                        $categoryId = Product::find($id)->category_id;

                        if ($liked)
                            Category::find($categoryId)->increment('count_like');
                    }
                }
                $message = $liked ? 'liked successfully' : 'Something is wrong';

                break;
            case "unlike":
                $unliked = $like->delete();
                if ($unliked) {
                    $models[get_class($this)]::where('id', $id)->decrement('count_like');
                    User::find(1)->decrement('count_like');

                    if ($models[get_class($this)] == 'App\Product') {
                        $userId = Product::find($id)->user_id;
                        User::find($userId)->decrement('count_like');

                        $categoryId = Product::find($id)->category_id;

                        if ($unliked)
                            Category::find($categoryId)->decrement('count_like');
                    }
                }
                $message = $unliked ? 'unliked successfully' : 'Something is wrong';
                break;
            case "bookmark":
                $exists = Bookmarkable::where(['user_id' => Auth::id(), 'bookmarkable_type' => $models[get_class($this)], 'bookmarkable_id' => $id])->exists();

                if (!$exists)
                    $bookmarked = Bookmarkable::insert(['user_id' => Auth::id(), 'bookmarkable_type' => $models[get_class($this)], 'bookmarkable_id' => $id]);
                else
                    $bookmarked = false;

                $message = $bookmarked ? 'bookmarked successfully' : 'Something is wrong';
                break;
            case "unbookmark":
                $unbookmark = $bookmark->delete();
                $message = $unbookmark ? 'unbookmarked successfully' : 'Something is wrong';
                break;


            case "follow":
                $exists = Bookmarkable::where(['user_id' => Auth::id(), 'bookmarkable_type' => $models[get_class($this)], 'bookmarkable_id' => $id])->exists();

                if (!$exists)
                    $bookmarked = Bookmarkable::insert(['user_id' => Auth::id(), 'bookmarkable_type' => $models[get_class($this)], 'bookmarkable_id' => $id]);
                else
                    $bookmarked = false;

                $message = $bookmarked ? 'followed successfully' : 'Something is wrong';

                if ($bookmarked && $models[get_class($this)] == 'App\User') {
                    $models[get_class($this)]::where('id', $id)->increment('count_follower');
                    $models[get_class($this)]::where('id', Auth::id())->increment('count_following');
                }

                break;
            case "unfollow":
                $unbookmark = $bookmark->delete();
                $message = $unbookmark ? 'unfollowed successfully' : 'Something is wrong';

                if ($unbookmark && $models[get_class($this)] == 'App\User') {
                    $models[get_class($this)]::where('id', $id)->decrement('count_follower');
                    $models[get_class($this)]::where('id', Auth::id())->decrement('count_following');
                }
                break;
            default:
                return new ErrorResource(['wrong act' => 'wrong act']);
        }

        return new SuccessResource(null, $message);

    }


}
