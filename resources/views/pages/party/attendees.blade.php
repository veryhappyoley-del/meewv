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
        .att-avatar{width:46px;height:46px;border-radius:50%;object-fit:cover;border:1px solid var(--line);flex-shrink:0;}
        .att-avatar-fallback{width:46px;height:46px;border-radius:50%;flex-shrink:0;
            background:linear-gradient(135deg,var(--spark-orange),var(--spark-pink));
            display:flex;align-items:center;justify-content:center;color:var(--void-1);font-weight:800;font-size:16px;}
        .att-name{font-family:var(--font-display);font-weight:800;font-size:19px;flex:1;min-width:0;}
        .att-tags{display:flex;gap:6px;margin-bottom:18px;flex-wrap:wrap;}
        .att-tag{font-size:11.5px;font-weight:700;padding:4px 12px;border-radius:999px;
            background:rgba(246,242,251,.06);color:var(--text-mid);border:1px solid var(--line);}
        .att-tag.badge{background:rgba(255,138,61,.14);color:var(--spark-orange);border-color:rgba(255,138,61,.3);}
        .att-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
        .att-tile{background:rgba(246,242,251,.03);border:1px solid var(--line);border-radius:14px;padding:14px 10px;text-align:center;}
        .att-tile-icon{font-size:18px;margin-bottom:5px;}
        .att-tile-label{font-size:10px;color:var(--text-lo);margin-bottom:3px;font-weight:600;}
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
                $photos = $attendee->user->photos ?? [];
                $age = $attendee->user->birth_date ? \Carbon\Carbon::parse($attendee->user->birth_date)->age : null;
                $genderLabel = $attendee->user->gender === 'male' ? '남성' : ($attendee->user->gender === 'female' ? '여성' : '-');
            @endphp
            <div wire:key="attendee-{{ $attendee->id }}" class="att-card">
                <div class="att-head">
                    @if (count($photos))
                        <img src="{{ asset('storage/' . $photos[0]) }}" class="att-avatar" alt="{{ $attendee->user->name }}">
                    @else
                        <div class="att-avatar-fallback">{{ mb_substr($attendee->user->name, 0, 1) }}</div>
                    @endif

                    <div class="att-name">{{ $attendee->user->name }}</div>

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
                    <span class="att-tag badge">{{ $attendee->badge_no ?? '--' }}번</span>
                </div>

                <div class="att-grid">
                    <div class="att-tile">
                        <div class="att-tile-icon">💼</div>
                        <div class="att-tile-label">직업</div>
                        <div class="att-tile-value">{{ $attendee->user->job ?: '비공개' }}</div>
                    </div>
                    <div class="att-tile">
                        <div class="att-tile-icon">🎯</div>
                        <div class="att-tile-label">관심사</div>
                        <div class="att-tile-value">{{ $attendee->user->hobbies_interests ?: '비공개' }}</div>
                    </div>
                    <div class="att-tile">
                        <div class="att-tile-icon">📸</div>
                        <div class="att-tile-label">인스타그램</div>
                        <div class="att-tile-value">{{ $attendee->user->instagram_handle ?: '비공개' }}</div>
                    </div>
                    <div class="att-tile">
                        <div class="att-tile-icon">💬</div>
                        <div class="att-tile-label">한줄소개</div>
                        <div class="att-tile-value">{{ $attendee->user->bio ?: '비공개' }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty">아직 체크인한 다른 참석자가 없어요.</div>
        @endforelse
    @endif
</div>