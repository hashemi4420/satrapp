<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceArea extends Model
{
    protected $fillable = ['title','color','timestamp','active','accept'];

    public function groups()
    {
        return $this->hasMany(ServiceGroup::class, 'group_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany(ServiceCategory::class, 'group_id', 'id');
    }

    public function services()
    {
        return $this->hasMany(ServiceCreator::class, 'group_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'workFieldService', 'id');
    }
}
