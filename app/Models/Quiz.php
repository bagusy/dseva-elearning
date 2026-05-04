<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'title',
        'min_score',
        'show_question',
        'is_custom',
    ];

    protected $casts = [
        'min_score' => 'integer',
        'show_question' => 'integer',
        'is_custom' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(QuizItem::class, 'quiz_id', 'id');
    }
}
