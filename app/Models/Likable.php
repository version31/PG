<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Likable extends Model
{
    public function likable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
