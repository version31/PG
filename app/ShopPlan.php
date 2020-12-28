<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopPlan extends Model
{
    //

    protected $fillable = [
        'day',
        'price',
        'maximum_product_on_shop',
    ];
}
