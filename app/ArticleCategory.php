<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    protected $fillable = ['title', 'area_id', 'group_id','timestamp','active','accept'];


    public function area()
    {
        return $this->belongsTo(ArticleArea::class, 'area_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(ArticleGroup::class, 'group_id', 'id');
    }

    public function articles()
    {
        return $this->hasMany(ArticleCreator::class, 'category_id', 'id');
    }

}