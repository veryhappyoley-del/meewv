<?php

use App\Models\Event;
use App\Models\EventAttendee;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => '오늘 진행'])] class extends Component
{
    public ?int $selectedEventId = null;
    public bool $sent = false;
    public string $sentMessage = '';

    public function mount(): void
    {
        $first = Event::whereDate('event_date', now()->toDateString())
            ->orderBy('start_time')
            ->first();

        $this->selectedEventId = $first?->id;
    }

    #[Computed]
    public function todayEvents()
    {
        return Event::with(['location', 'category'])
            ->withCount([
                'attendees as approved_count' => fn ($q) => $q->where('approval_status', 'approved'),
                'attendees as checked_in_count' => fn ($q) => $q->whereNotNull('checked_in_at'),
            ])
            ->whereDate('event_date', now()->toDateString())
            ->orderBy('start_time')
            ->get();
    }

    #[Computed]
    public function selectedEvent()
    {
        if (! $this->selectedEventId) {
            return null;
        }

        return Event::with(['location', 'category'])->find($this->selectedEventId);
    }

    #[Computed]
    public function attendees()
    {
        if (! $this->selectedEventId) {
            return collect();
        }

        return EventAttendee::with('user')
            ->where('event_id', $this->selectedEventId)
            ->where('approval_status', 'approved')
            ->orderByDesc('checked_in_at')
            ->get();
    }

    public function selectEvent(int $eventId): void
    {
        $this->selectedEventId = $eventId;
        $this->sent = false;
    }

    public function sendIndividualKakao(int $attendeeId): void
    {
        $attendee = EventAttendee::with('user')->findOrFail($attendeeId);

        // TODO: 카카오 알림톡 API 연동 전까지는 로그로 대체
        Log::info("[카톡 발송] 개별 - {$attendee->user->name}({$attendee->user->phone})");

        $this->sent = true;
        $this->sentMessage = "{$attendee->user->name}님에게 카톡을 보냈어요.";
    }

    public function sendBroadcastKakao(): void
    {
        foreach ($this->attendees as $attendee) {
            Log::info("[카톡 발송] 전체 - {$attendee->user->name}({$attendee->user->phone})");
        }

        $this->sent = true;
        $this->sentMessage = "참가자 {$this->attendees->count()}명 전체에게 카톡을 보냈어요.";
    }

    public function sendClosingKakao(): void
    {
        foreach ($this->attendees as $attendee) {
            Log::info("[마감 카톡 발송] {$attendee->user->name}({$attendee->user->phone})");
        }

        $this->sent = true;
        $this->sentMessage = '마감 안내 카톡을 전체 참가자에게 보냈어요.';
    }
}; ?>

<div>
    <div class="mv-page-head">
        <h2>오늘 진행중인 일정</h2>
        <p>{{ now()->format('Y년 m월 d일 (D)') }} 기준, 오늘 열리는 회차예요.</p>
    </div>

    <div class="admin-layout">
        <aside class="event-sidebar">
            @forelse ($this->todayEvents as $event)
                <button
                    wire:click="selectEvent({{ $event->id }})"
                    wire:key="today-event-{{ $event->id }}"
                    class="event-side-item {{ $selectedEventId === $event->id ? 'active' : '' }}"
                >
                    <div class="row">
                        <span class="d">{{ $event->category?->name }} · {{ $event->start_time }}</span>
                        <span class="event-side-badge">{{ $event->checked_in_count }}/{{ $event->approved_count }}</span>
                    </div>
                    <div class="t">{{ $event->location?->name }}</div>
                </button>
            @empty
                <div class="empty">오늘 진행되는 일정이 없어요.</div>
            @endforelse
        </aside>

        <div class="admin-main-inner">
            @if (! $this->selectedEvent)
                <div class="empty">오늘 진행되는 일정이 없어요.</div>
            @else
                <div class="group-header">
                    <div class="cat-pill">
                        <span class="cat-dot"></span>
                        {{ $this->selectedEvent->category?->name }} · {{ $this->selectedEvent->location?->name }}
                    </div>
                    <div class="meta">{{ $this->selectedEvent->start_time }} 시작</div>
                    <div class="count">체크인 {{ $this->attendees->whereNotNull('checked_in_at')->count() }} / 승인 {{ $this->attendees->count() }}명</div>
                </div>

                @if ($sent)
                    <div class="mv-alert mv-alert-success">{{ $sentMessage }}</div>
                @endif

                <div style="display:flex;gap:8px;margin-bottom:18px;flex-wrap:wrap;">
                    <button wire:click="sendBroadcastKakao" class="btn btn-primary">전체 카톡 보내기</button>
                    <button wire:click="sendClosingKakao" class="btn btn-outline">마감 카톡 보내기</button>
                </div>

                @forelse ($this->attendees as $attendee)
                    <div wire:key="today-attendee-{{ $attendee->id }}" class="item-card">
                        <div class="item-main">
                            <div class="item-name">
                                {{ $attendee->badge_no ? $attendee->badge_no.'번 · ' : '' }}{{ $attendee->user->name }}
                                @if ($attendee->checked_in_at)
                                    <span class="pill pill-success" style="margin-left:6px;">체크인 완료</span>
                                @else
                                    <span class="pill pill-muted" style="margin-left:6px;">미체크인</span>
                                @endif
                            </div>
                            <div class="item-meta">{{ $attendee->user->phone }} · {{ $attendee->user->job }}</div>
                        </div>
                        <div class="item-actions">
                            <button wire:click="sendIndividualKakao({{ $attendee->id }})" class="btn btn-outline btn-sm">카톡 보내기</button>
                        </div>
                    </div>
                @empty
                    <div class="empty">승인된 참가자가 없어요.</div>
                @endforelse
            @endif
        </div>
    </div>
</div>