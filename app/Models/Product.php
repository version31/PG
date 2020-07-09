<?php

namespace App\Models;

use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function addables()
    {
        return $this->morphMany('App\Models\Addable', 'addable');
    }

    public function bookmarkables()
    {
        return $this->morphMany('App\Models\Bookmarkable', 'bookmarkable');
    }

    public function likables()
    {
        return $this->morphMany('App\Models\Likable', 'likable');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


//    public function getPromoteExpiredAtAttribute($value)
//    {
//       if($value == '0000-00-00 00:00:00')
//           $value = null;
//
//
//        if ($value) {
//            $v = new Verta($value);
//            return $v;
//        }
//
//    }


    public function getCreatedAtAttribute($value)
    {
        if($value == '0000-00-00 00:00:00')
            $value = null;

        if ($value) {
            $v = new Verta($value);
            return $v;
        }
    }

}
