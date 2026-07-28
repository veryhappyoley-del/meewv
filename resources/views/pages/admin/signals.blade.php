<?php

use App\Models\Signal;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => '시그널 현황'])] class extends Component
{
    #[Computed]
    public function stats()
    {
        return [
            'pending' => Signal::where('status', 'pending')->count(),
            'accepted' => Signal::where('status', 'accepted')->count(),
            'rejected' => Signal::where('status', 'rejected')->count(),
        ];
    }

    #[Computed]
    public function recentSignals()
    {
        return Signal::with(['sender', 'receiver', 'event.location'])
            ->latest()
            ->take(30)
            ->get();
    }
}; ?>

<div>
    <div class="mv-page-head">
        <h2>시그널 현황</h2>
        <p>전체 회차의 시그널 발송/수락/거절 현황이에요.</p>
    </div>

    <div class="stat-grid" style="grid-template-columns:repeat(3,1fr);">
        <div class="stat-card">
            <div class="label">응답 대기</div>
            <div class="value">{{ $this->stats['pending'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">수락(매칭)</div>
            <div class="value">{{ $this->stats['accepted'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">거절</div>
            <div class="value">{{ $this->stats['rejected'] }}</div>
        </div>
    </div>

    <table class="mv-table">
        <thead>
            <tr>
                <th>보낸 사람</th>
                <th>받은 사람</th>
                <th>회차</th>
                <th>상태</th>
                <th>시간</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($this->recentSignals as $signal)
                <tr wire:key="signal-{{ $signal->id }}">
                    <td>{{ $signal->sender?->name }}</td>
                    <td>{{ $signal->receiver?->name }}</td>
                    <td>{{ $signal->event?->location?->name }} · {{ $signal->event?->event_date }}</td>
                    <td>
                        @if ($signal->status === 'pending')
                            <span class="pill pill-pending">대기</span>
                        @elseif ($signal->status === 'accepted')
                            <span class="pill pill-success">수락</span>
                        @else
                            <span class="pill pill-muted">거절</span>
                        @endif
                    </td>
                    <td style="color:var(--text-lo);">{{ $signal->created_at?->format('m/d H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="empty">아직 시그널이 없어요.</div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
