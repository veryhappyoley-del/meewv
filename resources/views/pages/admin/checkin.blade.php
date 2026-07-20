<?php

use App\Models\EventAttendee;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component
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

<div class="max-w-2xl mx-auto mt-10 space-y-4 px-4">
    <h2 class="text-xl font-bold mb-4">현장 체크인</h2>

    @forelse ($this->approvedAttendees as $attendee)
        <div wire:key="checkin-{{ $attendee->id }}" class="border rounded p-4 flex justify-between items-center">
            <div>
                <div class="font-semibold">{{ $attendee->user->name }}</div>
                <div class="text-sm text-gray-500">{{ $attendee->event->location?->name }} · {{ $attendee->event->event_date }}</div>
            </div>
            <button wire:click="checkIn({{ $attendee->id }})" class="bg-black text-white px-4 py-2 rounded">체크인</button>
        </div>
    @empty
        <p class="text-gray-500">체크인 대기 중인 승인자가 없어요.</p>
    @endforelse
</div>
