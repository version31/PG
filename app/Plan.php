<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{

    protected $fillable = ['price' , 'day' , 'insert_limit_product' , 'extra'];

    protected $hidden = ['created_at' , 'updated_at'];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
