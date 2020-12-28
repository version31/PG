<?php

namespace App;

use App\Sh4\Sh4HasPagination;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    //

    use Sh4HasPagination;

    protected $table = 'shops';


    protected $fillable = [
        'type',
        'product_id',
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
            $model->published_at =  Carbon::today();
        });

    }
}
