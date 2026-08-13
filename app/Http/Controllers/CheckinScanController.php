<?php

namespace App\Http\Controllers;

use App\Models\EventAttendee;
use Illuminate\View\View;

class CheckinScanController extends Controller
{
    public function show(string $token): View
    {
        $attendee = EventAttendee::with(['user', 'event.location'])
            ->where('checkin_token', $token)
            ->first();

        if (! $attendee) {
            return view('checkin-scan', ['state' => 'invalid', 'attendee' => null]);
        }

        if ($attendee->approval_status !== 'approved') {
            return view('checkin-scan', ['state' => 'not_approved', 'attendee' => $attendee]);
        }

        if ($attendee->checked_in_at) {
            return view('checkin-scan', ['state' => 'already', 'attendee' => $attendee]);
        }

        $nextBadge = EventAttendee::where('event_id', $attendee->event_id)
            ->whereNotNull('checked_in_at')
            ->count() + 1;

        $attendee->update([
            'checked_in_at' => now(),
            'badge_no' => str_pad((string) $nextBadge, 2, '0', STR_PAD_LEFT),
        ]);

        $attendee->refresh();

        return view('checkin-scan', ['state' => 'welcome', 'attendee' => $attendee]);
    }
}