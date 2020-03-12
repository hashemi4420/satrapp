<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{

    protected $fillable = ['title', 'area_id', 'group_id','timestamp','active','accept'];

    public function area()
    {
        return $this->belongsTo(ServiceArea::class, 'area_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(ServiceGroup::class, 'group_id', 'id');
    }

    public function services(){
        return $this->hasMany(ServiceCreator::class, 'category_id', 'id');
    }
}
