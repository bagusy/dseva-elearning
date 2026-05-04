<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'amount',
        'currency',
        'status',
        'reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    const STATUS_WAITING_FOR_PAYMENT = 'WAITING_FOR_PAYMENT';
    const STATUS_SUCCESS = 'SUCCESS';
    const STATUS_FAILED = 'FAILED';
    const STATUS_CANCELLED = 'CANCELLED';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
