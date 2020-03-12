<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreeCallCustomer extends Model
{
    protected $fillable = ['user_id','phone','active'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
