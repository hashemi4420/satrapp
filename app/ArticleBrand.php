<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleBrand extends Model
{
    protected $fillable = ['title','url_avatar','timestamp','active','accept'];

    public function article(){
        return $this->hasMany(ArticleCreator::class, 'brand_id', 'id');
    }

    public function users(){
        return $this->hasMany(User::class, 'articleBrand_id', 'id');
    }
}
