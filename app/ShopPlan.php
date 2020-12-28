<?php

namespace App;

use App\Sh4\Sh4HasPagination;
use Illuminate\Database\Eloquent\Model;

class ShopPlan extends Model
{
    use Sh4HasPagination;

    protected $fillable = [
        'day',
        'price',
        'maximum_product_on_shop',
    ];

    public function scopeSelected($query)
    {
        return $query->select([
            'id',
            'maximum_product_on_shop',
            'price',
            'day'
        ]);
    }
}
