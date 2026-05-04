<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Section extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'title',
        'additional_url',
        'index',
    ];

    protected $casts = [
        'index' => 'integer',
    ];

    public function subSections()
    {
        return $this->hasMany(SubSection::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($section) {
            if (!isset($section['index']) || $section['index'] === 0 || $section['index'] === null) {
                $courseId = $section['course_id'];
                if (!is_null($courseId)) {
                    $maxIndex = DB::table('sections')
                        ->where('course_id', $courseId)
                        ->lockForUpdate()
                        ->max('index');
                    $section['index'] = ((int) $maxIndex) + 1;
                }
            }
        });
    }
}
