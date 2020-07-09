<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id','star','star_expired_at'];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
