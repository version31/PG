<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsOperator extends Model
{
    //

    protected $fillable = ['title'];



    public function requests()
    {
        return $this->hasMany(SmsRequest::class);
    }
}
