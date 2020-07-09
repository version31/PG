<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function users()
    {
        return $this->hasMany('App\Models\Users');
    }

    public function children()
    {
        return $this->hasMany('App\Models\City','parent_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\City','parent_id');
    }
}
