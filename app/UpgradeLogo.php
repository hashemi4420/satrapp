<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UpgradeLogo extends Model
{
    protected $fillable = ['user_id', 'page', 'pey', 'active', 'timestamp'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
