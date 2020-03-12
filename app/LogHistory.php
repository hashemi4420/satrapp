<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogHistory extends Model
{
    protected $fillable = ['user_id','form_id','timestamp', 'action'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }
}
