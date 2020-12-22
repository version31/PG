<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsRequest extends Model
{
    //
    protected $fillable = [
        'user_id',
        'category_id',
        'operator_id',
        'count',
        'body',
        'file_path',
        'send_at',
    ];

    public function operators()
    {
        return $this->hasMany(SmsOperator::class);
    }
}
