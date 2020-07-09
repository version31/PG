<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addable extends Model
{

    protected $hidden= ['created_at' , 'updated_at' , 'addable_type' , 'type'];

    protected $appends = ['thumbnail'];


    protected $fillable = [
        'addable_id','addable_type', 'media_path', 'type',
    ];



    public function addable()
    {
        return $this->morphTo();
    }

    public function getMediaPathAttribute($value)
    {
        return config('app.url') . $value;
    }

    public function getThumbnailAttribute()
    {
        if ($this->type == "picture") {
            $pattern = '(uploads/media)';
            $replacement = 'uploads/thumbnail';
            return preg_replace($pattern, $replacement, $this->media_path);

        }
        else
            return $this->media_path;
    }



}
