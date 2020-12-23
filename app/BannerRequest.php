<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannerRequest extends Model
{
    //

    protected $fillable = [
        'banner_plan_id',
        'day',
        'published_at',
        'approved_at',
        'total_price',
    ];


}
