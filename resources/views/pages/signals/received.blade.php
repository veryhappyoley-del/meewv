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

<div>
    <a href="/mypage" class="back-link">← 마이페이지로</a>
    <div class="mv-page-head">
        <h1>받은 시그널</h1>
        <p>수락하면 공개할 내 정보를 직접 선택할 수 있어요.</p>
    </div>

    @forelse ($this->pendingSignals as $signal)
        @php $senderName = $signal->sender->nickname ?: $signal->sender->name; @endphp
        <div wire:key="signal-{{ $signal->id }}" class="mv-card-block">
            <div class="head">{{ $senderName }}님이 시그널을 보냈어요</div>
            <div class="sub">{{ $signal->sender->hobbies_interests }} · {{ $signal->event->location?->name }}</div>

            @if ($respondingTo === $signal->id)
                <div class="mv-divider">
                    <p style="font-size:13.5px;font-weight:600;color:var(--text-mid);margin:0 0 10px;">공개할 내 정보를 선택하세요</p>
                    <label class="check-row">
                        <input type="checkbox" wire:model="disclose" value="name"> 실명
                    </label>
                    <label class="check-row">
                        <input type="checkbox" wire:model="disclose" value="phone"> 전화번호
                    </label>
                    <label class="check-row">
                        <input type="checkbox" wire:model="disclose" value="instagram_handle"> 인스타그램
                    </label>
                    <label class="check-row">
                        <input type="checkbox" wire:model="disclose" value="job"> 직업
                    </label>

                    <div style="display:flex;gap:8px;margin-top:14px;">
                        <button wire:click="confirmAccept({{ $signal->id }})" class="btn btn-primary">공개하고 수락</button>
                        <button wire:click="cancelAccept" class="btn btn-outline">취소</button>
                    </div>
                </div>
            @else
                <div style="display:flex;gap:8px;">
                    <button wire:click="startAccept({{ $signal->id }})" class="btn btn-primary">수락</button>
                    <button wire:click="reject({{ $signal->id }})" class="btn btn-outline">거절</button>
                </div>
            @endif
        </div>
    @empty
        <div class="empty">받은 시그널이 없어요.</div>
    @endforelse
</div>