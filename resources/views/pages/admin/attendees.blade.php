<?php

use App\Models\EventAttendee;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component
{
    #[Computed]
    public function pendingAttendees()
    {
        return EventAttendee::with(['event.location', 'event.category', 'user'])
            ->where('approval_status', 'pending')
            ->latest()
            ->get();
    }

    public function approve(int $attendeeId)
    {
        $attendee = EventAttendee::findOrFail($attendeeId);
        $attendee->update(['approval_status' => 'approved']);

        Log::info("[카톡 발송 예정] {$attendee->user->name}({$attendee->user->phone}) 승인 - 장소: {$attendee->event->location->name}, 드레스코드 안내");

        unset($this->pendingAttendees);
    }

    public function reject(int $attendeeId)
    {
        $attendee = EventAttendee::findOrFail($attendeeId);
        $attendee->update(['approval_status' => 'rejected']);

        Log::info("[카톡 발송 예정] {$attendee->user->name}({$attendee->user->phone}) 거절 - 환불 안내");

        unset($this->pendingAttendees);
    }
}; ?>

<div class="max-w-3xl mx-auto mt-10 space-y-4 px-4">
    <h2 class="text-xl font-bold mb-4">승인 대기 중인 신청자</h2>

    @if ($this->pendingAttendees->isEmpty())
        <p class="text-gray-500">대기 중인 신청이 없어요.</p>
    @endif

    @foreach ($this->pendingAttendees as $attendee)
        <div wire:key="attendee-{{ $attendee->id }}" class="border rounded p-4 flex justify-between items-center">
            <div>
                <div class="font-semibold">{{ $attendee->user->name }} ({{ $attendee->user->phone }})</div>
                <div class="text-sm text-gray-500">
                    {{ $attendee->event->category?->name }} · {{ $attendee->event->location?->name }} · {{ $attendee->event->event_date }}
                </div>
                <div class="text-sm text-gray-500">
                    {{ $attendee->user->gender === 'male' ? '남성' : '여성' }} · {{ $attendee->user->job }} · {{ $attendee->user->instagram_handle }}
                </div>
            </div>
            <div class="flex gap-2">
                <button wire:click="approve({{ $attendee->id }})" class="bg-black text-white px-4 py-2 rounded">승인</button>
                <button wire:click="reject({{ $attendee->id }})" class="border px-4 py-2 rounded">거절</button>
            </div>
        </div>
    @endforeach
</div>
