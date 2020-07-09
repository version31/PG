<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Storyable extends Model
{
    public $timestamps = false;

    protected $appends = ['thumbnail'];

    protected $hidden = ['storyable_type' , 'expired_at' , 'storyable_id'];

    protected $fillable = ['expired_at' , 'status'];

    public function storyable()
    {
        return $this->morphTo();
    }

    public function getMediaPathAttribute($value)
    {
        return config('app.url') . $value;
    }

    public function getThumbnailAttribute()
    {
        return str_replace('media', 'thumbnail', $this->media_path);
    }



    public function scopeIsVisible($query)
    {
        return $query->where('status','>',0)->where('expired_at','>', Carbon::now());
    }
}
