<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['user_id','headline','title','body','avatar','media_path'];
    /*user relationship with services*/
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function requests()
    {
        return $this->belongsTo('App\Models\Request');
    }

    public function storyable()
    {
        return $this->morphOne('App\Models\Storyable','storyable');
    }
}
