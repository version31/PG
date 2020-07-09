<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['avatar','media_path','name'];
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }


    public function getAvatarAttribute()
    {

        $pattern = '(uploads/media)';
        $replacement = 'uploads/thumbnail';
        return preg_replace($pattern, $replacement, $this->media_path);




    }
}
