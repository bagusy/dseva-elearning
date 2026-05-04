<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Course extends Model
{
    use HasFactory, HasUuid, HasRelationships;

    const CATEGORY_LIST = [
        'Security Awareness',
        'Specialized',
        'Compliance',
        'Custom Training',
    ];

    const LEVEL_LIST = [
        'Beginner',
        'Intermediate',
        'Advanced',
        'Professional'
    ];
    const LEVEL_COLOR = [
        'Beginner' => 'success',
        'Intermediate' => 'info',
        'Advanced' => 'danger',
        'Professional' => 'dark'
    ];

    const STATUS_DRAFT = 'Draft';
    const STATUS_PUBLISHED = 'Published';
    const STATUS_DISABLED = 'Disabled';

    const STATUS_COLOR = [
        self::STATUS_DRAFT => 'warning',
        self::STATUS_PUBLISHED => 'success',
        self::STATUS_DISABLED => 'danger',
    ];

    protected $fillable = [
        'title',
        'description',
        'images',
        'category',
        'level',
        'price_in_usd',
        'status',
        'is_custom',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getThumbnailImgAttribute()
    {
        if (!empty($this['images'])) {
            return $this['images'];
        }

        if ($this->relationLoaded('subSections')) {
            $subSection = $this->subSections->firstWhere('type', SubSection::TYPE_VIDEO);
        } else {
            $subSection = $this->subSections()->where('type', SubSection::TYPE_VIDEO)->with('video')->first();
        }

        if (!is_null($subSection) && !is_null($subSection->video)) {
            return $subSection->video->thumbnail_img;
        }

        return null;
    }

    public function getStatusBadgeAttribute()
    {
        return '<span class="badge bg-' . self::STATUS_COLOR[$this['status']] . '">' . $this['status'] . '</span>';
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function subSections()
    {
        return $this->hasManyDeep(SubSection::class, [Section::class]);
    }

    public function courseAssignments()
    {
        return $this->hasMany(CourseAssignment::class);
    }
}
