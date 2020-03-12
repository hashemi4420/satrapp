<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleProvider extends Model
{
    protected $fillable = ['article_id','price','status', 'atless','user_id','timestamp','active','accept'];

    public function article()
    {
        return $this->belongsTo(ArticleCreator::class, 'article_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
