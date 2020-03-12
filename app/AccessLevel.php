<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessLevel extends Model
{
    protected $fillable = ['title', 'json'];

    public function users(){
        return $this->hasMany(User::class, 'userRole', 'id');
    }

}