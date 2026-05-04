<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    use HasUuid;

    protected $fillable = [
        'time_start',
        'time_limit',
        'status',
        'certificate_no',
    ];

    protected $casts = [
        'time_start' => 'datetime',
        'time_limit' => 'datetime',
    ];

    const STATUS_NEW = 'new';
    const STATUS_ON_PROGRESS = 'on progress';
    const STATUS_FAILED = 'failed';
    const STATUS_EXPIRED = 'expired';
    const STATUS_COMPLETED = 'completed';

    const STATUS_COLOR = [
        self::STATUS_NEW => 'light-info',
        self::STATUS_ON_PROGRESS => 'light-warning',
        self::STATUS_FAILED => 'light-danger',
        self::STATUS_EXPIRED => 'light-danger',
        self::STATUS_COMPLETED => 'light-success',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute()
    {
        return '<span class="badge bg-' . self::STATUS_COLOR[$this['status']] . '">' . e(ucwords($this['status'])) . '</span>';
    }

    public function progresses()
    {
        return $this->hasMany(CourseEnrollmentProgress::class,'course_enrollment_id','id');
    }

    public function quizCompletions()
    {
        return $this->hasMany(QuizCompletion::class);
    }
}
