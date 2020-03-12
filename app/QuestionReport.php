<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionReport extends Model
{
    protected $fillable = ['title'];

    public function report()
    {
        return $this->hasMany(Report::class, 'question_id', 'id');
    }
}
