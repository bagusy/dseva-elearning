<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizCompletion extends Model
{
    use HasUuid;

    const STATUS_PASSED = 'passed';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'score',
        'status',
    ];

    protected $casts = [
        'score' => 'float',
    ];

    public function courseEnrollment()
    {
        return $this->belongsTo(CourseEnrollment::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizCompletionAnswer::class);
    }

    public function getStatusBadgeAttribute()
    {
        return '<span class="badge bg-light-' . ($this['status'] === self::STATUS_PASSED?'success':'danger') . '">' . e(strtoupper($this['status'])) . '</span>';
    }
}
