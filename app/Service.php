<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    protected $table = 'services';



    protected $hidden = ['created_at', 'updated_at' , 'user_id'];


    protected $appends = ['thumbnail','body_html'];

    public function user()
    {
        return $this->belongsTo('App\Users');
    }

    public function requests()
    {
        return $this->belongsTo('App\Request');
    }

    public function storyable()
    {
        return $this->morphOne('App\Storyable','storyable');
    }

    public function addables()
    {
        return $this->hasMany('App\Addable' , 'addable_id')->where('addable_type' , "App\Service");
    }

    public function stories()
    {
        return $this->hasMany('App\Storyable' , 'storyable_id')->where('storyable_type' , "App\Service");
    }

    public function getAvatarAttribute($value)
    {
        $pattern = '(uploads/media)';
        $replacement = 'uploads/thumbnail';
        return preg_replace($pattern, $replacement, $this->media_path);
    }

    public function getMediaPathAttribute($value)
    {
        return config('app.url') . $value;
    }

    public function getThumbnailAttribute()
    {
        if ($this->type == "picture") {
            $pattern = '(uploads/media)';
            $replacement = 'uploads/thumbnail';
            return preg_replace($pattern, $replacement, $this->media_path);

        } else
            return $this->media_path;
    }


    public function getBodyAttribute($value)
    {
        return strip_tags($value);
    }

    public function getBodyHtmlAttribute($value)
    {
        return $this->body;
    }



}
