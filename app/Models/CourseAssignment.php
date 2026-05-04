<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAssignment extends Model
{
    use HasUuid;

    protected $fillable = [
        'subject',
        'message',
        'day_completion',
        'start_date',
        'is_expired',
        'is_assigned',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'day_completion' => 'integer',
        'is_expired' => 'boolean',
        'is_assigned' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function courseEnrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }
}
