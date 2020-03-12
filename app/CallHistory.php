<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallHistory extends Model
{
    protected $fillable = ['user_id', 'customer_id', 'time', 'modat', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}
