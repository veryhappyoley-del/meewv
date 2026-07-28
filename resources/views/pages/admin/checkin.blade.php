<?php

use App\Models\EventAttendee;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => '현장 체크인'])] class extends Component
{
    #[Computed]
    public function approvedAttendees()
    {
        return EventAttendee::with(['event.location', 'user'])
            ->where('approval_status', 'approved')
            ->whereNull('checked_in_at')
            ->latest()
            ->get();
    }

    public function checkIn(int $attendeeId)
    {
        $attendee = EventAttendee::findOrFail($attendeeId);

        $nextBadge = EventAttendee::where('event_id', $attendee->event_id)
            ->whereNotNull('badge_no')
            ->count() + 1;

        $attendee->update([
            'checked_in_at' => now(),
            'badge_no' => str_pad((string) $nextBadge, 2, '0', STR_PAD_LEFT),
        ]);

        unset($this->approvedAttendees);
    }
}; ?>

<div>
    <div class="mv-page-head">
        <h2>현장 체크인</h2>
        <p>도착한 참가자를 체크인하면 배지번호가 자동으로 발급돼요.</p>
    </div>

    @forelse ($this->approvedAttendees as $attendee)
        <div wire:key="checkin-{{ $attendee->id }}" class="item-card">
            <div class="item-main">
                <div class="item-name">{{ $attendee->user->name }}</div>
                <div class="item-meta">{{ $attendee->event->location?->name }} · {{ $attendee->event->event_date }}</div>
            </div>
            <div class="item-actions">
                <button wire:click="checkIn({{ $attendee->id }})" class="btn btn-primary">체크인</button>
            </div>
        </div>
    @empty
        <div class="empty">체크인 대기 중인 승인자가 없어요.</div>
    @endforelse
</div>
