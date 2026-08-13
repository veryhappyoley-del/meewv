<?php

use App\Models\Signal;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::auth')] class extends Component
{
    #[Computed]
    public function sentSignals()
    {
        return Signal::with(['receiver', 'event.location'])
            ->where('sender_id', Auth::id())
            ->latest()
            ->get();
    }

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

<div>
    <a href="/mypage" class="back-link">← 마이페이지로</a>
    <div class="mv-page-head">
        <h1>매칭 현황</h1>
        <p>내가 보낸 시그널과 매칭된 인연을 확인하세요.</p>
    </div>

    <div style="font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--spark-pink);margin-bottom:14px;">보낸 시그널</div>

    @forelse ($this->sentSignals as $signal)
        @php $recvName = $signal->receiver?->nickname ?: $signal->receiver?->name; @endphp
        <div wire:key="sent-{{ $signal->id }}" class="item-card">
            <div class="item-main">
                <div class="item-name">
                    {{ $recvName }}
                    @if ($signal->status === 'pending')
                        <span class="pill pill-pending" style="margin-left:6px;">대기중</span>
                    @elseif ($signal->status === 'accepted')
                        <span class="pill pill-success" style="margin-left:6px;">수락됨</span>
                    @else
                        <span class="pill pill-muted" style="margin-left:6px;">거절됨</span>
                    @endif
                </div>
                <div class="item-meta">{{ $signal->event?->location?->name }} · {{ $signal->created_at?->format('m/d H:i') }}</div>
            </div>
        </div>
    @empty
        <div class="empty">아직 보낸 시그널이 없어요.</div>
    @endforelse

    <div style="font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--spark-pink);margin:32px 0 14px;">매칭된 인연</div>

    @forelse ($this->matches as $signal)
        @php
            $isSender = $signal->sender_id === auth()->id();
            $partner = $isSender ? $signal->receiver : $signal->sender;
            $disclosed = $isSender ? ($signal->receiver_disclosed_fields ?? []) : null;
            $partnerDisplay = in_array('name', $disclosed ?? []) ? $partner->name : ($partner->nickname ?: $partner->name);
        @endphp

        <div wire:key="match-{{ $signal->id }}" class="mv-card-block">
            <div class="head">{{ $partnerDisplay }}</div>

            @if ($isSender)
                @if (in_array('phone', $disclosed ?? []))
                    <div class="item-meta">전화번호: {{ $partner->phone }}</div>
                @endif
                @if (in_array('instagram_handle', $disclosed ?? []))
                    <div class="item-meta">인스타그램: {{ $partner->instagram_handle }}</div>
                @endif
                @if (in_array('job', $disclosed ?? []))
                    <div class="item-meta">직업: {{ $partner->job }}</div>
                @endif
                @if (empty($disclosed))
                    <div class="item-meta" style="color:var(--text-lo);">아직 공개된 정보가 없어요.</div>
                @endif
            @else
                <div class="item-meta">전화번호: {{ $partner->phone }}</div>
                <div class="item-meta">인스타그램: {{ $partner->instagram_handle }}</div>
                <div class="item-meta">직업: {{ $partner->job }}</div>
            @endif
        </div>
    @empty
        <div class="empty">아직 매칭된 인연이 없어요.</div>
    @endforelse
</div>