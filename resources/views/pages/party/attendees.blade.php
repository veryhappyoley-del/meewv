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

        $myGender = Auth::user()->gender;
        $oppositeGender = match ($myGender) {
            'male' => 'female',
            'female' => 'male',
            default => null,
        };

        return EventAttendee::with('user')
            ->where('event_id', $this->myCheckIn->event_id)
            ->whereNotNull('checked_in_at')
            ->where('user_id', '!=', Auth::id())
            ->when($oppositeGender, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('gender', $oppositeGender)))
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

<div>
    <style>
        .att-card{border:1px solid var(--line);background:var(--card);border-radius:20px;padding:20px 22px;margin-bottom:14px;}
        .att-head{display:flex;align-items:center;gap:12px;margin-bottom:14px;}
        .att-avatar-fallback{width:46px;height:46px;border-radius:50%;flex-shrink:0;
            background:linear-gradient(135deg,var(--spark-orange),var(--spark-pink));
            display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:16px;}
        .att-name{font-family:var(--font-display);font-weight:800;font-size:19px;flex:1;min-width:0;}
        .att-tags{display:flex;gap:6px;margin-bottom:18px;flex-wrap:wrap;}
        .att-tag{font-size:11.5px;font-weight:700;padding:4px 12px;border-radius:999px;
            background:rgba(58,36,24,.05);color:var(--text-mid);border:1px solid var(--line);}
        .att-tag.badge{background:rgba(255,122,61,.14);color:var(--spark-orange);border-color:rgba(255,122,61,.3);}
        .att-bio{font-size:13px;color:var(--text-hi);background:rgba(58,36,24,.02);border-radius:10px;
            padding:12px 14px;margin-bottom:14px;line-height:1.6;}
        .att-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
        .att-tile{background:rgba(58,36,24,.02);border:1px solid var(--line);border-radius:14px;
            padding:10px 12px;overflow:hidden;min-width:0;}
        .att-tile-top{display:flex;align-items:center;gap:5px;font-size:10px;color:var(--text-lo);
            font-weight:600;margin-bottom:4px;white-space:nowrap;}
        .att-tile-top span{flex-shrink:0;}
        .att-tile-value{font-size:12.5px;font-weight:700;color:var(--text-hi);
            overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
        .att-signal-btn{flex-shrink:0;}
    </style>

    <a href="/mypage" class="back-link">← 마이페이지로</a>
    <div class="mv-page-head">
        <h1>오늘 참석자</h1>
        <p>마음에 드는 사람에게 시그널을 보내보세요.</p>
    </div>

    @if (! $this->myCheckIn)
        <div class="empty">아직 체크인되지 않았어요. 현장 스태프에게 문의해주세요.</div>
    @else
        @forelse ($this->attendees as $attendee)
            @php
                $age = $attendee->user->birth_date ? \Carbon\Carbon::parse($attendee->user->birth_date)->age : null;
                $genderLabel = $attendee->user->gender === 'male' ? '남성' : ($attendee->user->gender === 'female' ? '여성' : '-');
                $displayName = $attendee->user->nickname ?: $attendee->user->name;
            @endphp
            <div wire:key="attendee-{{ $attendee->id }}" class="att-card">
                <div class="att-head">
                    <div class="att-avatar-fallback">{{ mb_substr($displayName, 0, 1) }}</div>
                    <div class="att-name">{{ $displayName }}</div>

                    <div class="att-signal-btn">
                        @if ($this->sentSignalUserIds->contains($attendee->user_id))
                            <span class="pill pill-muted">전송됨</span>
                        @else
                            <button wire:click="sendSignal({{ $attendee->user_id }})" class="btn btn-primary btn-sm">
                                시그널 보내기
                            </button>
                        @endif
                    </div>
                </div>

                <div class="att-tags">
                    <span class="att-tag">{{ $genderLabel }}</span>
                    @if ($age)
                        <span class="att-tag">{{ $age }}세</span>
                    @endif
                    @if ($attendee->user->height)
                        <span class="att-tag">{{ $attendee->user->height }}cm</span>
                    @endif
                    @if ($attendee->user->mbti)
                        <span class="att-tag">{{ strtoupper($attendee->user->mbti) }}</span>
                    @endif
                    <span class="att-tag badge">{{ $attendee->badge_no ?? '--' }}번</span>
                </div>

                @if ($attendee->user->bio)
                    <div class="att-bio">{{ $attendee->user->bio }}</div>
                @endif

                <div class="att-grid">
                    <div class="att-tile">
                        <div class="att-tile-top"><span>🎯</span>취미 및 관심사</div>
                        <div class="att-tile-value">{{ $attendee->user->hobbies_interests ?: '비공개' }}</div>
                    </div>
                    <div class="att-tile">
                        <div class="att-tile-top"><span>💕</span>연애 스타일</div>
                        <div class="att-tile-value">{{ $attendee->user->dating_style ?: '비공개' }}</div>
                    </div>
                    <div class="att-tile" style="grid-column:span 2;">
                        <div class="att-tile-top"><span>✨</span>이상형</div>
                        <div class="att-tile-value">{{ $attendee->user->ideal_type ?: '비공개' }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty">아직 체크인한 다른 참석자가 없어요.</div>
        @endforelse
    @endif
</div>