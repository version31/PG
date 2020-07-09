<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addable extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'addable_id','addable_type', 'media_path', 'type',
    ];

    public function addable()
    {
        return $this->morphTo();
    }
}
