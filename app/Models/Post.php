<?php

namespace App\Models;

use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['user_id','title','count_like','study_time','body','media_path'];




    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function addables()
    {
        return $this->morphMany('App\Models\Addable','addable');
    }

    public function likables()
    {
        return $this->morphMany('App\Models\Likable','likable');
    }


    public function getCreatedAtAttribute($value)
    {
        return  Verta::now();
    }
}

