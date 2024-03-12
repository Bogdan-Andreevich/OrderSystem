<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'question_description',
        'is_add_description',
        'comment',
        'price',
        'parent_id',
        'typeDocId',
        'answers',
        'subQuestions',
        'index'
    ];

    public function answers()
    {
        return $this->hasMany(Question::class,'parent_id')->with('answers');
    }
}
