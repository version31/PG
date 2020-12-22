<?php

namespace App;

use App\Helpers\Sh4Helper;
use Illuminate\Database\Eloquent\Model;

use Auth;

class Post extends Model
{
    protected $hidden = ['pivot'];

    protected $appends = ['bookmarked', 'liked' ,  'thumbnail' , 'body_html'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function addables()
    {
        return $this->morphMany('App\Addable','addable');
    }

    public function likeables()
    {
        return $this->morphMany('App\Likeable','likeable');
    }

    public function getMediaPathAttribute($value)
    {
        return config('app.url') . $value;
    }

    public function getBookmarkedAttribute($value)
    {


        $bookmarked = Bookmarkable::where('user_id', Auth::user()->id)->where('bookmarkable_type', Post::class)->where('bookmarkable_id', $this->id)->count();

        if ($bookmarked)
            return true;
        else
            return false;
    }

    public function getLikedAttribute($value)
    {

        $liked = Likeable::where('user_id', Auth::user()->id)->where('likeable_type', Post::class)->where('likeable_id', $this->id)->count();

        if ($liked)
            return true;
        else
            return false;
    }

    public function getThumbnailAttribute()
    {
        return str_replace('media', 'thumbnail', $this->media_path);
    }

    public function getBodyAttribute($value)
    {
        return $value;
        return Sh4Helper::removeTag($value);
    }

    public function getBodyHtmlAttribute($value)
    {
        return $value;
    }


    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoriable');
    }

}

