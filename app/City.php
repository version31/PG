<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    protected $hidden = ['parent_id' , 'created_at' , 'updated_at'];


    public function users()
    {
        return $this->hasMany('App\users');
    }

    public function children()
    {
        return $this->hasMany('App\City','parent_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\City','parent_id');
    }
}
