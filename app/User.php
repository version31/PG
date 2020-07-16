<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_name', 'email', 'password', 'mobile', 'role_id', 'status', 'first_name', 'last_name', "shop_expired_at","fax"
    ];


    protected $appends = ['name', 'role','bio_html'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'city_id', 'role_id', 'created_at', 'updated_at'
    ];

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function directs()
    {
        return $this->hasMany('App\Direct');
    }

    public function services()
    {
        return $this->hasMany('App\Service');
    }


    public function favoritePosts()
    {
        return $this->morphedByMany('App\Post', 'bookmarkable')->select('id', 'title', 'media_path', 'count_like');
    }

    public function favoriteProducts()
    {
        return $this->morphedByMany('App\Product', 'bookmarkable')->select('id', 'title', 'media_path', 'count_like', 'type' , 'products.user_id')->with(['addables' , 'user']);
    }


    public function requests()
    {
        return $this->hasMany('App\Request');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);

    }




    public function likes()
    {
        return $this->hasMany('App\Likeable');
    }


    public function stories()
    {
        return $this->morphMany('App\Storyable', 'storyable')->where('status', '>', 0);
    }


    public function getRoleAttribute()
    {
        $roles = [
            1 => 'admin',
            2 => 'provider',
            3 => 'user'
        ];


        $roleId = $this->role_id ? $this->role_id : 3;

        return $roles[$roleId];
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


    public function getAvatarAttribute($value)
    {
        return config('app.url') . $this->pathUpload . $value;
    }


    public function scopeIsUser($query)
    {
        return $query->where('role_id', 3);
    }

    public function scopeIsAdmin($query)
    {
        return $query->where('role_id', 1);
    }

    public function scopeIsProvider($query)
    {

//        return $query->where('role_id', 2);

        return $query->where('role_id', 2)
            ->leftJoin(\DB::raw("(SELECT star,user_id FROM `stars` WHERE star_expired_at > '" . Carbon::now() . "') AS S"), 'user_id', '=', 'users.id')
            ->orderBy('star', 'Desc');



    }


//    public function getStarAttribute()
//    {
//        return 3;
//    }

    public function scopeIsActive($query)
    {
        return $query->where('status', '>', '0');

    }

    public function scopeCheckStatusShop($query)
    {
        return $query->where('shop_expired_at', '>', Carbon::now());

    }

    public function scopeIsMain($query)
    {
        return $query->where('id', 1);
    }

    public function scopeIsAdvert($query)
    {
        return $query->join('stars', 'users.id', '=', 'stars.user_id')->where('stars.star', 3);
    }

    public function links()
    {
        return $this->hasMany('App\Link');
    }


    public function scopeShopIsActive($query)
    {
        return $query->where('status','>',0)->where('shop_expired_at','>', Carbon::now());
    }

    public function stars()
    {
        return $this->hasOne(Star::class);

    }



    public function getBioAttribute($value)
    {
        return strip_tags($value);
    }

    public function getBioHtmlAttribute($value)
    {
        return $this->bio;
    }



}