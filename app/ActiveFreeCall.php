<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActiveFreeCall extends Model
{
    protected $fillable = ['user_id', 'pey', 'active'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
