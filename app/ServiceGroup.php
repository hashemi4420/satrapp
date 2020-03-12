<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceGroup extends Model
{
    protected $fillable = ['title', 'area_id','timestamp','active','accept'];

    public function area()
    {
        return $this->belongsTo(ServiceArea::class, 'area_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany(ServiceCategory::class, 'group_id', 'id');
    }

    public function services()
    {
        return $this->hasMany(ServiceCreator::class, 'group_id', 'id');
    }
}
