<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleGroup extends Model
{
    protected $fillable = ['title', 'area_id','timestamp','active','accept'];

    public function area()
    {
        return $this->belongsTo(ArticleArea::class, 'area_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany(ArticleCategory::class, 'group_id', 'id');
    }

    public function articles()
    {
        return $this->hasMany(ArticleCreator::class, 'group_id', 'id');
    }

}