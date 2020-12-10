<?php

namespace App;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Product extends Model implements HasMedia
{

    use InteractsWithMedia;

    protected $hidden = [
        'created_at', 'updated_at', 'confirmed_at', 'priority_expired_at', 'user_id', 'category_id', 'pivot'];


    protected $fillable = [
        "category_id",
        "confirmed_at",
        "count_like",
        "count_visit",
        "created_at",
        "description",
        "id",
        "media_path",
        "promote_expired_at",
        "status",
        "title",
        "type",
        "updated_at",
        "user_id",
        'price',
        'shipping',
    ];

    protected $appends = ['bookmarked', 'liked', 'thumbnail', 'is_yours', 'description_html'];


    public function addHidden($attributes = null)
    {
        $this->hidden = $attributes;
    }


    public function addables()
    {
        return $this->morphMany('App\Addable', 'addable');
    }

    public function bookmarkables()
    {
        return $this->morphMany('App\Bookmarkable', 'bookmarkable');
    }

    public function likeables()
    {
        return $this->morphMany('App\Likeable', 'likeable');
    }

    public function category()
    {
        return $this->belongsTo('App\Category')->select('id', 'name');
    }

    public function user()
    {
        return $this->belongsTo('App\User')->select('id', 'first_name', 'last_name', 'avatar', 'mobile', 'website', 'phone', 'role_id', 'status', 'shop_name');
    }




    public function getThumbnailAttribute()
    {
        if ($this->type == "picture") {
            $pattern = '(uploads/media)';
            $replacement = 'uploads/thumbnail';
            return preg_replace($pattern, $replacement, $this->media_path);

        } else
            return $this->media_path;
    }


    public function getBookmarkedAttribute($value)
    {


        $bookmarked = Bookmarkable::where('user_id', Auth::user()->id)->where('bookmarkable_type', Product::class)->where('bookmarkable_id', $this->id)->count();

        if ($bookmarked)
            return true;
        else
            return false;
    }


    public function getLikedAttribute($value)
    {

        $liked = Likeable::where('user_id', Auth::user()->id)->where('likeable_type', Product::class)->where('likeable_id', $this->id)->count();

        if ($liked)
            return true;
        else
            return false;
    }


    /**
     * @return bool
     * Mr Farbod asked me to place edit icon
     */
    public function getISYoursAttribute()
    {
        if (Auth::user())
            $userId = Auth::user()->id;

        if ($this->user_id == $userId)
            return true;
        else
            return false;
    }


    public function getDescriptionAttribute($value)
    {
        return strip_tags($value);
    }

    public function getDescriptionHtmlAttribute($value)
    {
        return $this->description;
    }


    public function scopeCountActive($builder, $categoryId = null)
    {

        $products = $builder->where('status', '>', 0)
            ->whereHas('user', function ($query) {
                $query->where('shop_expired_at', '>', Carbon::now());
            });

        if ($categoryId) {
            $products = $products->where('category_id', $categoryId);
        }


        return $products->count();

    }


}
