<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookmarkable extends Model
{
    protected $hidden= ['created_at' , 'updated_at' , 'bookmarkable_type'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function bookmarkable()
    {
        return $this->morphTo();
    }

}
