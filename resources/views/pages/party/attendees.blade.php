<?php

use App\Models\EventAttendee;
use App\Models\Signal;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::auth')] class extends Component
{
    #[Computed]
    public function myCheckIn()
    {
        return EventAttendee::where('user_id', Auth::id())
            ->whereNotNull('checked_in_at')
            ->latest('checked_in_at')
            ->first();
    }

    #[Computed]
    public function attendees()
    {
        if (! $this->myCheckIn) {
            return collect();
        }

        return EventAttendee::with('user')
            ->where('event_id', $this->myCheckIn->event_id)
            ->whereNotNull('checked_in_at')
            ->where('user_id', '!=', Auth::id())
            ->get();
    }

    #[Computed]
    public function sentSignalUserIds()
    {
        if (! $this->myCheckIn) {
            return collect();
        }

        return Signal::where('event_id', $this->myCheckIn->event_id)
            ->where('sender_id', Auth::id())
            ->pluck('receiver_id');
    }

    public function sendSignal(int $receiverId)
    {
        Signal::firstOrCreate([
            'event_id' => $this->myCheckIn->event_id,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
        ]);

        unset($this->sentSignalUserIds);
    }
}; ?>

<div class="max-w-lg mx-auto mt-10 space-y-4 px-4">
    <h2 class="text-xl font-bold mb-4">오늘 참석자</h2>

    @if (! $this->myCheckIn)
        <p class="text-gray-500">아직 체크인되지 않았어요. 현장 스태프에게 문의해주세요.</p>
    @else
        @forelse ($this->attendees as $attendee)
            <div wire:key="attendee-{{ $attendee->id }}" class="border rounded p-4 flex justify-between items-center">
                <div>
                    <div class="font-semibold">{{ $attendee->badge_no ?? '번호 미배정' }}번 · {{ $attendee->user->name }}</div>
                    <div class="text-sm text-gray-500">{{ $attendee->user->job }}</div>
                </div>

                @if ($this->sentSignalUserIds->contains($attendee->user_id))
                    <span class="text-sm text-gray-400">전송됨</span>
                @else
                    <button wire:click="sendSignal({{ $attendee->user_id }})" class="bg-black text-white px-4 py-2 rounded">
                        시그널 보내기
                    </button>
                @endif
            </div>
        @empty
            <p class="text-gray-500">아직 체크인한 다른 참석자가 없어요.</p>
        @endforelse
    @endif
</div>
