<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    //

    protected $table = 'shops';


    protected $fillable = [
        'type',
        'product_id',
        'day',
        'new_price',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\Shop());
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'SHOP';
        });

    }
}
