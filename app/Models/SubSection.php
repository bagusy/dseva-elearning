<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSection extends Model
{
    use HasFactory, HasUuid;

    const TYPE_VIDEO = 'video';
    const TYPE_QUIZ = 'quiz';

    const TYPE_COLOR = [
        self::TYPE_QUIZ => 'danger',
        self::TYPE_VIDEO => 'success'
    ];

    protected $fillable = [
        'type',
        'index',
    ];

    protected $casts = [
        'index' => 'integer',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
