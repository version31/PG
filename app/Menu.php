<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Menu extends Model implements HasMedia
{

    use InteractsWithMedia ;
    protected $fillable = [
        'name',
        'href',
        'icon',
        'parent_id',
        'group',
        'order',
        'permission',
    ];
}
