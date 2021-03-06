<?php

namespace App;

use Bavix\Wallet\Traits\HasWallets;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;

class User extends Authenticatable implements Wallet
{
    use HasApiTokens, Notifiable, HasRoles, HasWallet ,  HasWallets;


    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_name', 'email', 'password', 'mobile', 'avatar',
        'role_id', 'status', 'first_name', 'last_name',
        "fax", 'presentable_fields','agent_id','shop_expired_at','maximum_product_on_shop'
    ];


    protected $appends = ['name', 'role', 'bio_html','created_day','shop_expired_day'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'city_id', 'role_id', 'created_at', 'updated_at'
    ];


    protected $casts = [
        'verified' => 'boolean',
        'presentable_fields' => 'array'
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
        return $this->morphedByMany('App\Product', 'bookmarkable')->select('id', 'title', 'media_path', 'count_like', 'products.user_id')->with(['addables', 'user']);
    }





    public function following()
    {
        return $this->belongsToMany('App\User', 'bookmarkables', 'user_id', 'bookmarkable_id')
            ->select('id', 'first_name', 'last_name','avatar');
    }


    public function followers()
    {
        return $this->belongsToMany('App\User', 'bookmarkables', 'bookmarkable_id', 'user_id')
            ->select('id', 'first_name', 'last_name','avatar');
    }

    public function requests()
    {
        return $this->hasMany('App\Request');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    //    public function role()
    //    {
    //        return $this->belongsTo(Role::class);
    //
    //    }


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


    public function getCreatedDayAttribute()
    {
        $created = new Carbon($this->created_at);

        return $created->diffInDays(Carbon::now());
    }


    public function getShopExpiredDayAttribute()
    {
        $created = new Carbon($this->shop_expired_at);

        return $created->diffInDays(Carbon::now());
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
        return $query->role('provider')
            ->join('stars as providerStar', 'providerStar.user_id', '=', 'users.id')
            ->orderby('providerStar.star', 'desc');
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
//        return $query->where('shop_expired_at', '>', Carbon::now());

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
//        return $query->where('status', '>', 0)->where('shop_expired_at', '>', Carbon::now());
    }

    public function stars()
    {
        return $this->hasOne(Star::class)->where('star_expired_at', '>', Carbon::now());

    }


    public function getBioAttribute($value)
    {
        return strip_tags($value);
    }

    public function getBioHtmlAttribute($value)
    {
        return $this->bio;
    }

    public function catalogs()
    {
        return $this->hasMany(Catalog::class);
    }


    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoriable');
    }


    public function shops()
    {
        return $this->hasManyThrough(Shop::class, Product::class);
    }

    public function onSales()
    {
        return $this->hasManyThrough(OnSale::class, Product::class);
    }


    public function scopeSelected($query)
    {
        return $query->select([
            'id',
            'shop_name',
            'first_name',
            'last_name'
        ]);
    }

}
