<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Video extends Model
{
    use HasFactory, HasUuid;

    const SOURCE_WISTIA = 'wistia';
    const SOURCE_YOUTUBE = 'youtube';

    const SOURCE_LIST = [
        self::SOURCE_YOUTUBE,
        self::SOURCE_WISTIA
    ];

    const CATEGORY_PRIVATE = 'Private';

    const CATEGORY_LIST = [
        'Security Awareness',
        'Compliance',
        'For Developers',
        'For Families',
        'Real-Life Stories'
    ];

    const TAG_LIST = [
        'Phishing',
        'Best Practices',
        'Wire Fraud',
        'Common Scams',
        'Developers',
        'Ransomware',
        'Insider Threat',
        'Mobile',
        'Misc',
        'Role Based',
        'HIPAA',
        'GDPR',
        'CCPA',
        'HR',
        'POPIA',
        'PCI',
        'PII',
        'GLBA',
        'UK Bribery Act 2010',
        'CJIS',
    ];

    protected $fillable = [
        'title',
        'link',
        'source',
        'category',
        'tag',
        'images',
    ];

    public function getThumbnailImgAttribute()
    {
        if (!empty($this['images'])) {
            return $this['images'];
        }

        if ($this['source'] === self::SOURCE_YOUTUBE && !empty($this['link'])) {
            $parts = explode('https://www.youtube.com/embed/', $this['link'], 2);
            if (count($parts) === 2 && !empty($parts[1])) {
                return 'https://img.youtube.com/vi/' . $parts[1] . '/mqdefault.jpg';
            }
        }

        return '';
    }

    public static function fetchWistiaThumbnail(string $link): ?string
    {
        try {
            $body = Http::timeout(5)->get($link)->body();
            $parts = explode('meta name="twitter:image"', $body, 2);
            if (count($parts) < 2) {
                return null;
            }
            $tail = explode('"', $parts[1]);
            return $tail[1] ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoriesAttribute()
    {
        return explode(',', $this['category']);
    }

    public function getTagsAttribute()
    {
        return explode(',', $this['tag']);
    }

    public static function getAllTag(): array
    {
        return array_unique(array_merge(explode(',', implode(',',Video::distinct('tag')->pluck('tag')->toArray())), self::TAG_LIST));
    }
}
