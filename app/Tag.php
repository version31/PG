<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $fillable = ['title'];


    public function products()
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }


    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
