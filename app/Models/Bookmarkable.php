<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Bookmarkable extends Model
{
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function bookmarkable()
    {
        return $this->morphTo();
    }

}
