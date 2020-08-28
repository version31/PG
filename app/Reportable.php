<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reportable extends Model
{


    protected $fillable = [
        "user_id",
        "body",
        "reportable_id",
        "reportable_type",
        "type",
        "mobile",
    ];
}
