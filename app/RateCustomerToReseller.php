<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RateCustomerToReseller extends Model
{
    protected $fillable = ['user_id', 'customer_id', 'stars'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
