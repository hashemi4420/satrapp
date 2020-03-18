<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleArea extends Model
{
    protected $fillable = ['title','color','timestamp','active','accept'];

    public function groups(){
        return $this->hasMany(ArticleGroup::class, 'area_id', 'id');
    }

    public function categories(){
        return $this->hasMany(ArticleCategory::class, 'area_id', 'id');
    }

    public function articles()
    {
        return $this->hasMany(ArticleCreator::class, 'area_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'workFieldArticle', 'id');
    }
}