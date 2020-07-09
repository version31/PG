<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direct extends Model
{
    protected $fillable = ['user_id','receiver_id','body','created_at'];
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function receiver()
    {
        return $this->belongsTo('App\Models\User','receiver_id');
    }
}
