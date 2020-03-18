<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\AssignOp\Concat;

class Customer extends Model
{
    protected $fillable = [
        'phone', 'number', 'name', 'family', 'cash', 'email', 'active', 'state_id', 'city_id', 'district_id'
    ];

    public function callHistory(){
        return $this->hasMany(CallHistory::class, 'customer_id', 'id');
    }

    public function rateCustomer()
    {
        return $this->hasMany(RateCustomerToReseller::class, 'customer_id', 'id');
    }

    public function sign()
    {
        return $this->hasMany(Sign::class, 'customer_id', 'id');
    }

    public function report()
    {
        return $this->hasMany(Report::class, 'customer_id', 'id');
    }

    public function concat()
    {
        return $this->hasMany(Concat::class, 'customer_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
}
