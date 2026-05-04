<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'name',
        'report_email',
    ];

    const DEFAULT_LIST = [
        'No Department',
        'Marketing & Sales',
        'Manufacturing',
        'Logistics',
        'IT',
        'Finance',
        'Engineering'
    ];

    const COLOR_LIST = [
        '#556B2F',
        '#FF8C00',
        '#779ECB',
        '#03C03C',
        '#966FD6',
        '#C23B22',
        '#E75480',
        '#003399',
        '#872657',
        '#E9967A',
        '#560319',
        '#3C1414',
        '#2F4F4F',
        '#177245',
        '#918151',
        '#FFA812',
        '#CC4E5C',
        '#9400D3',
        '#555555',
        '#1560BD',
        '#C19A6B',
        '#EDC9AF',
        '#696969',
        '#85BB65',
        '#00009C',
        '#E1A95F',
        '#614051',
        '#50C878',
        '#E5AA70',
        '#FF1C00'
    ];

    const NO_DEPARTMENT = self::DEFAULT_LIST[0];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function courseAssignments()
    {
        return $this->hasMany(CourseAssignment::class);
    }

    public function courseEnrollments()
    {
        return $this->hasManyThrough(
            CourseEnrollment::class,
            CourseAssignment::class,
            'department_id',
            'course_assignment_id',
            'id',
            'id'
        );
    }
}
