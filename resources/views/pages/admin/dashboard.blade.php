<?php

use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\Signal;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => '대시보드'])] class extends Component
{
    #[Computed]
    public function stats()
    {
        return [
            'pending' => EventAttendee::where('approval_status', 'pending')->count(),
            'upcoming_events' => Event::where('event_date', '>=', now()->toDateString())->count(),
            'total_users' => User::count(),
            'matches' => Signal::where('status', 'accepted')->count(),
        ];
    }

    #[Computed]
    public function genderRatio()
    {
        $male = User::where('gender', 'male')->count();
        $female = User::where('gender', 'female')->count();

        return ['male' => $male, 'female' => $female];
    }

    #[Computed]
    public function urgentEvents()
    {
        return Event::with(['location', 'category'])
            ->withCount(['attendees as pending_count' => fn ($q) => $q->where('approval_status', 'pending')])
            ->where('event_date', '>=', now()->toDateString())
            ->where('event_date', '<=', now()->addDays(10)->toDateString())
            ->orderBy('event_date')
            ->get()
            ->filter(fn ($event) => $event->pending_count > 0)
            ->values();
    }

    #[Computed]
    public function recentUsers()
    {
        return User::latest()->take(5)->get();
    }

    #[Computed]
    public function recentMatches()
    {
        return Signal::with(['sender', 'receiver'])
            ->where('status', 'accepted')
            ->latest('responded_at')
            ->take(5)
            ->get();
    }
}; ?>

<div>
    <div class="mv-page-head">
        <h2>오늘의 현황</h2>
        <p>{{ now()->format('Y년 m월 d일 (D)') }} 기준</p>
    </div>

    <div class="stat-grid">
        <div class="stat-card {{ $this->stats['pending'] > 0 ? 'warn' : '' }}">
            <div class="label">승인 대기 인원</div>
            <div class="value">{{ $this->stats['pending'] }}</div>
            <div class="sub">전체 회차 합산</div>
        </div>
        <div class="stat-card">
            <div class="label">예정된 회차</div>
            <div class="value">{{ $this->stats['upcoming_events'] }}</div>
            <div class="sub">오늘 이후</div>
        </div>
        <div class="stat-card">
            <div class="label">전체 회원</div>
            <div class="value">{{ $this->stats['total_users'] }}</div>
            <div class="sub">남 {{ $this->genderRatio['male'] }} · 여 {{ $this->genderRatio['female'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label">매칭 성사</div>
            <div class="value">{{ $this->stats['matches'] }}</div>
            <div class="sub">누적 시그널 성사 건수</div>
        </div>
    </div>

    @if ($this->urgentEvents->isNotEmpty())
        <div class="mv-card-block" style="border-color:rgba(255,62,127,.35);margin-bottom:24px;">
            <div class="dash-section-title" style="color:#ff88ab;">⚠ 10일 이내인데 승인 대기자가 남은 회차</div>
            @foreach ($this->urgentEvents as $event)
                <div class="mini-row">
                    <span class="name">{{ $event->category?->name }} · {{ $event->location?->name }} · {{ $event->event_date }}</span>
                    <span class="meta">대기 {{ $event->pending_count }}명 · <a href="/admin/attendees" style="color:var(--spark-orange);">확인하기 →</a></span>
                </div>
            @endforeach
        </div>
    @endif

    <div class="dash-grid">
        <div class="mv-card-block">
            <div class="dash-section-title">최근 매칭 성사</div>
            @forelse ($this->recentMatches as $signal)
                <div class="mini-row">
                    <span class="name">{{ $signal->sender?->name }} ↔ {{ $signal->receiver?->name }}</span>
                    <span class="meta">{{ $signal->responded_at?->format('m/d H:i') }}</span>
                </div>
            @empty
                <div class="empty">아직 매칭 성사 건이 없어요.</div>
            @endforelse
        </div>

        <div class="mv-card-block">
            <div class="dash-section-title">최근 가입한 회원</div>
            @forelse ($this->recentUsers as $user)
                <div class="mini-row">
                    <span class="name">{{ $user->name }}</span>
                    <span class="meta">{{ $user->created_at?->format('m/d H:i') }}</span>
                </div>
            @empty
                <div class="empty">회원이 아직 없어요.</div>
            @endforelse
        </div>
    </div>
</div>