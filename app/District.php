<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
   protected $fillable = ['title', 'state_id', 'city_id','timestamp','active','accept'];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'district_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function customer()
    {
        return $this->hasMany(Customer::class, 'district_id', 'id');
    }
}
