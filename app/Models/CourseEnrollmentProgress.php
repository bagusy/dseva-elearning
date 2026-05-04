<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollmentProgress extends Model
{
    use HasUuid;

    const TYPE_VIDEO = 'video';
    const TYPE_QUIZ = 'quiz';

    protected $fillable = [
        'index',
        'type',
        'is_done',
    ];

    protected $casts = [
        'index' => 'integer',
        'is_done' => 'boolean',
    ];

    public function courseEnrollment()
    {
        return $this->belongsTo(CourseEnrollment::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
