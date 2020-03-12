<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = ['title','timestamp','active','accept'];

    public function cities()
    {
        return $this->hasMany(City::class, 'state_id', 'id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'state_id', 'id');
    }

    public function customer()
    {
        return $this->hasMany(Customer::class, 'state_id', 'id');
    }
}
