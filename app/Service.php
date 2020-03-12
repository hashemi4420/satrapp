<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['title','timestamp','active','accept'];

    public function services(){
        return $this->hasMany(ServiceCreator::class, 'service_id', 'id');
    }
}
