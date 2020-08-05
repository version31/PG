<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    //

    protected $fillable = ['value'];


    public function scopeGetValue($query, $key)
    {
        if ($query->where('key', $key)->first())
            return $query->where('key', $key)->first()->value;
        else
            return null;
    }
}
