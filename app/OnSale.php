<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OnSale extends Shop
{

    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\OnSale());
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'ON_SALE';
            $model->day = 1;
        });

    }
}
