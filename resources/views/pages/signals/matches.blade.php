<?php

use App\Models\Signal;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::auth')] class extends Component
{
    #[Computed]
    public function matches()
    {
        return Signal::with(['sender', 'receiver'])
            ->where('status', 'accepted')
            ->where(function ($q) {
                $q->where('sender_id', Auth::id())->orWhere('receiver_id', Auth::id());
            })
            ->latest('responded_at')
            ->get();
    }
}; ?>

<div class="max-w-lg mx-auto mt-10 space-y-4 px-4">
    <h2 class="text-xl font-bold mb-4">매칭된 인연</h2>

    @forelse ($this->matches as $signal)
        @php
            $isSender = $signal->sender_id === auth()->id();
            $partner = $isSender ? $signal->receiver : $signal->sender;
            $disclosed = $isSender ? ($signal->receiver_disclosed_fields ?? []) : null;
        @endphp

        <div wire:key="match-{{ $signal->id }}" class="border rounded p-4 space-y-1">
            <div class="font-semibold">{{ $partner->name }}</div>

            @if ($isSender)
                @if (in_array('phone', $disclosed ?? []))
                    <div class="text-sm">전화번호: {{ $partner->phone }}</div>
                @endif
                @if (in_array('instagram_handle', $disclosed ?? []))
                    <div class="text-sm">인스타그램: {{ $partner->instagram_handle }}</div>
                @endif
                @if (in_array('job', $disclosed ?? []))
                    <div class="text-sm">직업: {{ $partner->job }}</div>
                @endif
                @if (empty($disclosed))
                    <div class="text-sm text-gray-400">아직 공개된 정보가 없어요.</div>
                @endif
            @else
                <div class="text-sm">전화번호: {{ $partner->phone }}</div>
                <div class="text-sm">인스타그램: {{ $partner->instagram_handle }}</div>
                <div class="text-sm">직업: {{ $partner->job }}</div>
            @endif
        </div>
    @empty
        <p class="text-gray-500">아직 매칭된 인연이 없어요.</p>
    @endforelse
</div>
