<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

class Category extends Model
{

    protected $appends = [ 'liked'];


    public function users()
    {
        return $this->morphedByMany(User::class, 'categoriable');
    }



    public function posts()
    {
        return $this->morphedByMany(Post::class, 'categoriable');
    }






    public function getAvatarAttribute()
    {

            $pattern = '(uploads/media)';
            $replacement = 'uploads/thumbnail';
            return preg_replace($pattern, $replacement, $this->media_path);




    }




    public function getMediaPathAttribute($value)
    {
        return config('app.url') . $this->pathUpload . $value;
    }


    public function getLikedAttribute($value)
    {

        $liked = Likeable::where('user_id', Auth::user()->id)->where('likeable_type', Category::class)->where('likeable_id', $this->id)->count();

        if ($liked)
            return true;
        else
            return false;
    }

}
