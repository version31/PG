<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    public $timestamps = true;

    protected $fillable = ['user_id','star','star_expired_at' , 'created_at' , 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
