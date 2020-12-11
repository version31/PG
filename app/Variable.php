<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    //

    protected $fillable = ['value'];

    public $temp = [
        'OnSaleOpenTime'=> '00:00:01', //تاریخ شروع حراجستان
        'OnSaleLimitProduct'=> 100, //تعداد کالاهای حراجستان در هر روز
    ];


    public function scopeGetValue($query, $key)
    {
        if ($query->where('key', $key)->first())
            return $query->where('key', $key)->first()->value;
        else
            return null;
    }


    public function scopeVal($query, $key)
    {
        if ($query->where('key', $key)->first())
            return $query->where('key', $key)->first()->value;
        else
            return $this->temp[$key];

    }

    public function getTempValue($key)
    {
            return $this->temp[$key];
    }


}
