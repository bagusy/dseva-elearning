<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizCompletionAnswer extends Model
{
    use HasUuid;

    protected $fillable = [
        'question',
        'answer',
        'answer_status',
    ];

    protected $casts = [
        'answer_status' => 'boolean',
    ];

    public function quizCompletion()
    {
        return $this->belongsTo(QuizCompletion::class);
    }
}
