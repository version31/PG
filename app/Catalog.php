<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    //


    protected $table = "catalogs";

    protected $fillable = [
        'title',
        'description',
        'path',
        'user_id'
    ];
}
