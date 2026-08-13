<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class EventAttendee extends Model
{
    protected $fillable = ['event_id', 'user_id', 'table_no', 'status', 'approval_status', 'badge_no', 'checked_in_at'];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (EventAttendee $attendee) {
            if (! $attendee->checkin_token) {
                $attendee->checkin_token = Str::random(24);
            }
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }
}