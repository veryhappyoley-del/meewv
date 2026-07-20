<?php

use App\Models\Signal;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::auth')] class extends Component
{
    public ?int $respondingTo = null;
    public array $disclose = [];

    #[Computed]
    public function pendingSignals()
    {
        return Signal::with(['sender', 'event.location'])
            ->where('receiver_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->get();
    }

    public function startAccept(int $signalId)
    {
        $this->respondingTo = $signalId;
        $this->disclose = [];
    }

    public function cancelAccept()
    {
        $this->respondingTo = null;
    }

    public function confirmAccept(int $signalId)
    {
        $signal = Signal::findOrFail($signalId);

        $signal->update([
            'status' => 'accepted',
            'responded_at' => now(),
            'receiver_disclosed_fields' => array_values($this->disclose),
        ]);

        $this->respondingTo = null;
        unset($this->pendingSignals);
    }

    public function reject(int $signalId)
    {
        $signal = Signal::findOrFail($signalId);
        $signal->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        unset($this->pendingSignals);
    }
}; ?>

<div class="max-w-lg mx-auto mt-10 space-y-4 px-4">
    <h2 class="text-xl font-bold mb-4">받은 시그널</h2>

    @forelse ($this->pendingSignals as $signal)
        <div wire:key="signal-{{ $signal->id }}" class="border rounded p-4 space-y-3">
            <div>
                <div class="font-semibold">{{ $signal->sender->name }}님이 시그널을 보냈어요</div>
                <div class="text-sm text-gray-500">{{ $signal->sender->job }} · {{ $signal->event->location?->name }}</div>
            </div>

            @if ($respondingTo === $signal->id)
                <div class="space-y-2 border-t pt-3">
                    <p class="text-sm font-medium">공개할 내 정보를 선택하세요</p>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model="disclose" value="phone"> 전화번호
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model="disclose" value="instagram_handle"> 인스타그램
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model="disclose" value="job"> 직업
                    </label>

                    <div class="flex gap-2 mt-2">
                        <button wire:click="confirmAccept({{ $signal->id }})" class="bg-black text-white px-4 py-2 rounded">공개하고 수락</button>
                        <button wire:click="cancelAccept" class="border px-4 py-2 rounded">취소</button>
                    </div>
                </div>
            @else
                <div class="flex gap-2">
                    <button wire:click="startAccept({{ $signal->id }})" class="bg-black text-white px-4 py-2 rounded">수락</button>
                    <button wire:click="reject({{ $signal->id }})" class="border px-4 py-2 rounded">거절</button>
                </div>
            @endif
        </div>
    @empty
        <p class="text-gray-500">받은 시그널이 없어요.</p>
    @endforelse
</div>
