<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhishingTemplate extends Model
{
    use HasUuid;

    protected $fillable = [
        'title',
        'subject',
        'body',
        'category',
    ];
}
