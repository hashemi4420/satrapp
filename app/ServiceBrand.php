<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceBrand extends Model
{
    protected $fillable = ['title','url_avatar','timestamp','active','accept'];

    public function services(){
        return $this->hasMany(ServiceCreator::class, 'brand_id', 'id');
    }

    public function users(){
        return $this->hasMany(User::class, 'serviceBrand_id', 'id');
    }
}
