<?php

namespace App;

use App\Sh4\Sh4HasPagination;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use Sh4HasPagination;
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

    protected $casts = [
        'positive_items' => 'json',
        'negative_items' => 'json',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
