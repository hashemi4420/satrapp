<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    protected $fillable = ['service_id','price','status','atless','user_id','timestamp','active','accept'];

    public function service()
    {
        return $this->belongsTo(ServiceCreator::class, 'service_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}