<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['title', 'state_id','timestamp','active','accept'];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'city_id', 'id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'city_id', 'id');
    }

    public function customer()
    {
        return $this->hasMany(Customer::class, 'city_id', 'id');
    }
}
