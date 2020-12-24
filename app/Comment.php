<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //

    protected $fillable = [
        'title',
        'score',
        'body',
        'positive_items',
        'negative_items',
        'user_id',
        'parent_id',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function children()
    {
        return $this->hasMany(Comment::class,'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class,'parent_id');
    }
}
