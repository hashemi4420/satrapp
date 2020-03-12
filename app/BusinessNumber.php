<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessNumber extends Model
{
    protected $fillable = ['user_id', 'number', 'timestamp', 'active'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
