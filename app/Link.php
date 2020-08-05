<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Link
 *
 * @property int $id
 * @property int $user_id
 * @property string $value
 * @property string $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Link whereValue($value)
 * @mixin \Eloquent
 */
class Link extends Model
{
    //
    protected $fillable = ['type' , 'value'];

    public $timestamps = false;

    protected $hidden = ['user_id'];
}
