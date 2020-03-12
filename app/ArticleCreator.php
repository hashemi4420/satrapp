<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleCreator extends Model
{
    protected $fillable = ['title','url_avatar','brand_id','area_id','group_id','category_id','user_id','timestamp','active','accept'];

    public function area()
    {
        return $this->belongsTo(ArticleArea::class, 'area_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(ArticleGroup::class, 'group_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(ArticleBrand::class, 'brand_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
