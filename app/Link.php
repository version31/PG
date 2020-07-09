<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    //
    protected $fillable = ['type' , 'value'];

    public $timestamps = false;

    protected $hidden = ['user_id'];
}
