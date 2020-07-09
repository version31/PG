<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Direct extends Model
{
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function receiver()
    {
        return $this->belongsTo('App\User','receiver_id');
    }
}
