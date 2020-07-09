<?php

namespace App\Models;

use App\Notifications\MailResetPasswordToken;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;


    /*
     * send a password reset email to the user
     * */

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordToken($token));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'remember_token', 'shop_expired_at', 'city_id', 'role_id', 'first_name', 'last_name', 'phone', 'mobile', 'website', 'email', 'fax', 'limit_insert_product', 'limit_insert_video', 'count_like', 'shop_name', 'password', 'address', 'bio', 'gender', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function directs()
    {
        return $this->hasMany('App\Models\Direct');
    }

    public function services()
    {
        return $this->hasMany('App\Models\Service');
    }

    public function Bookmarkables()
    {
        return $this->morphMany('App\Models\Bookmarkable', 'bookmarkable');
    }

    public function requests()
    {
        return $this->hasMany('App\Models\Request');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function star()
    {
        return 3;
        return $this->hasOne('App\Models\Star');
    }

    public function likes()
    {
        return $this->morphMany('App\Models\Likable', 'likeable');
    }

    public function storyable()
    {
        return $this->morphOne('App\Models\Storyable', 'storyable');
    }

    public function gateway_transactions()
    {
        return $this->hasMany('App/GatewayTransaction');
    }


    public function scopeIsProvider($query)
    {
        return $query->where('role_id', 2)
            ->leftJoin(\DB::raw("(SELECT star,user_id FROM `stars` WHERE star_expired_at > '" . Carbon::now() . "') AS S"), 'user_id', '=', 'users.id')
            ->orderBy('star', 'Desc');

    }

    public function getShopExpiredAtAttribute($value)
    {
        if ($value == '0000-00-00 00:00:00')
            return null;
        else
            return $value;

    }


    public function links()
    {
        return $this->hasMany('App\Link');
    }

}
