<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = ['user_id','service_id'];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function service()
    {
        return $this->belongsTo('App\Models\Service');
    }
}
