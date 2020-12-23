<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannerPlan extends Model
{
    //
    protected $fillable = [
        'title',
        'station',
        'day_price',
    ];


}
