<?php

namespace App\Models;

use App\Helpers\Sh4Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Storyable extends Model
{
    protected $fillable=['storyable_type','storyable_id','media_path','expired_at','title','status'];
    public $timestamps = false;

    public function storyable()
    {
        return $this->morphTo();
    }

    public function getExpiredAtAttribute($value)
    {
        if ($value == '0000-00-00 00:00:00' || $value == null )
            $value = Carbon::now();

        return $value;
    }
}
