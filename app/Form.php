<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['title', 'url', 'active'];

    public function logHistory(){
        return $this->hasMany(LogHistory::class, 'form_id', 'id');
    }
}
