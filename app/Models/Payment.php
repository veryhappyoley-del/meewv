<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'event_attendee_id',
        'amount',
        'currency',
        'provider',
        'method',
        'transaction_id',
        'status',
        'depositor_name',
        'cash_receipt_requested',
        'cash_receipt_type',
        'cash_receipt_number',
        'paid_at',
        'refunded_at',
    ];

    protected $casts = [
        'cash_receipt_requested' => 'boolean',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function eventAttendee(): BelongsTo
    {
        return $this->belongsTo(EventAttendee::class);
    }

    public function methodLabel(): string
    {
        return match ($this->method) {
            'kakaopay' => '카카오페이',
            'naverpay' => '네이버페이',
            'tosspay' => '토스페이',
            'bank_transfer' => '무통장입금',
            default => '미정',
        };
    }
}
