<?php

use App\Models\Event;
use App\Models\EventAttendee;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => '신청 승인'])] class extends Component
{
    public ?int $selectedEventId = null;

    public function mount(): void
    {
        $first = Event::where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')->orderBy('start_time')
            ->first();

        $this->selectedEventId = $first?->id;
    }

    #[Computed]
    public function upcomingEvents()
    {
        return Event::with(['location', 'category'])
            ->withCount([
                'attendees as pending_count' => fn ($q) => $q->where('approval_status', 'pending'),
                'attendees as approved_count' => fn ($q) => $q->where('approval_status', 'approved'),
                'attendees as rejected_count' => fn ($q) => $q->where('approval_status', 'rejected'),
            ])
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')->orderBy('start_time')
            ->get()
            ->groupBy(fn ($e) => $e->category?->name ?? '기타');
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
    public function pendingAttendees()
    {
        if (! $this->selectedEventId) {
            return collect();
        }

        return EventAttendee::with('user')
            ->where('event_id', $this->selectedEventId)
            ->where('approval_status', 'pending')
            ->get();
    }

    #[Computed]
    public function approvedAttendees()
    {
        if (! $this->selectedEventId) {
            return collect();
        }

        return EventAttendee::with('user')
            ->where('event_id', $this->selectedEventId)
            ->where('approval_status', 'approved')
            ->get();
    }

    #[Computed]
    public function rejectedAttendees()
    {
        if (! $this->selectedEventId) {
            return collect();
        }

        return EventAttendee::with('user')
            ->where('event_id', $this->selectedEventId)
            ->where('approval_status', 'rejected')
            ->get();
    }

    #[Computed]
    public function genderCounts()
    {
        if (! $this->selectedEventId) {
            return ['male' => 0, 'female' => 0, 'total' => 0];
        }

        $counts = EventAttendee::query()
            ->join('users', 'users.id', '=', 'event_attendees.user_id')
            ->where('event_attendees.event_id', $this->selectedEventId)
            ->selectRaw('users.gender as gender, count(*) as cnt')
            ->groupBy('users.gender')
            ->pluck('cnt', 'gender');

        $male = (int) ($counts['male'] ?? 0);
        $female = (int) ($counts['female'] ?? 0);

        return ['male' => $male, 'female' => $female, 'total' => $male + $female];
    }

    public function selectEvent(int $eventId): void
    {
        $this->selectedEventId = $eventId;
    }

    private function refreshLists(): void
    {
        unset(
            $this->pendingAttendees,
            $this->approvedAttendees,
            $this->rejectedAttendees,
            $this->upcomingEvents,
            $this->genderCounts,
        );
    }

    public function approve(int $attendeeId)
    {
        $attendee = EventAttendee::findOrFail($attendeeId);
        $attendee->update(['approval_status' => 'approved']);

        Log::info("[카톡 발송 예정] {$attendee->user->name}({$attendee->user->phone}) 승인 - 장소: {$attendee->event->location->name}, 드레스코드 안내");

        $this->refreshLists();
    }

    public function reject(int $attendeeId)
    {
        $attendee = EventAttendee::findOrFail($attendeeId);
        $attendee->update(['approval_status' => 'rejected']);

        Log::info("[카톡 발송 예정] {$attendee->user->name}({$attendee->user->phone}) 거절 - 환불 안내");

        $this->refreshLists();
    }
}; ?>

<div>
    <div class="mv-page-head">
        <h2>승인 대기 중인 신청자</h2>
        <p>정원이 다 차면 초록색 "완료"로, 임박했는데 자리가 남으면 빨간 "확정필요"로 표시돼요.</p>
    </div>

    <div class="admin-layout">
        <aside class="event-sidebar">
            @forelse ($this->upcomingEvents as $categoryName => $events)
                <div class="event-sidebar-group" wire:key="cat-{{ $categoryName }}" x-data="{ open: true }">
                    <button type="button" class="event-sidebar-group-toggle" @click="open = !open">
                        <h3>{{ $categoryName }} ({{ $events->count() }})</h3>
                        <span class="chev" :class="{ 'rot': open }">▾</span>
                    </button>

                    <div x-show="open" x-cloak>
                        @foreach ($events as $event)
                            @php
                                $daysUntil = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($event->event_date)->startOfDay());
                                $isFull = $event->approved_count >= $event->capacity;
                                $isUrgent = ! $isFull && $daysUntil <= 10 && $event->pending_count > 0;
                            @endphp
                            <button
                                wire:click="selectEvent({{ $event->id }})"
                                wire:key="side-event-{{ $event->id }}"
                                class="event-side-item {{ $selectedEventId === $event->id ? 'active' : '' }} {{ $isUrgent ? 'urgent' : '' }} {{ $isFull ? 'full' : '' }}"
                            >
                                <div class="row">
                                    <span class="d">
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('m/d (D)') }}
                                        @if ($isFull)
                                            <span class="full-tag">✓ 완료</span>
                                        @elseif ($isUrgent)
                                            <span class="urgent-tag">⚠ 확정필요</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="t">{{ $event->location?->name }} · {{ $event->start_time }} · 정원 {{ $event->capacity }}명</div>
                                <div class="mini-counts">
                                    <span class="mini-badge mb-approved">승인 {{ $event->approved_count }}</span>
                                    <span class="mini-badge mb-pending">대기 {{ $event->pending_count }}</span>
                                    <span class="mini-badge mb-rejected">거절 {{ $event->rejected_count }}</span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="empty">예정된 회차가 없어요.</div>
            @endforelse
        </aside>

        <div class="admin-main-inner">
            @if (! $this->selectedEvent)
                <div class="empty">왼쪽에서 회차를 선택해주세요.</div>
            @else
                @php
                    $capacity = $this->selectedEvent->capacity;
                    $approvedTotal = $this->approvedAttendees->count();
                    $isFullMain = $approvedTotal >= $capacity;
                @endphp

                <div class="group-header">
                    <div class="cat-pill">
                        <span class="cat-dot"></span>
                        {{ $this->selectedEvent->category?->name }} · {{ $this->selectedEvent->location?->name }}
                    </div>
                    <div class="meta">{{ $this->selectedEvent->event_date }} · {{ $this->selectedEvent->start_time }}</div>
                    <div class="count">
                        승인 {{ $approvedTotal }} / 정원 {{ $capacity }}명
                        @if ($isFullMain)
                            <span class="full-tag">✓ 완료</span>
                        @endif
                    </div>
                </div>

                <div class="gender-count-bar">
                    <span class="gc-pill gc-total">총 {{ $this->genderCounts['total'] }}명 신청</span>
                    <span class="gc-pill gc-male">남 {{ $this->genderCounts['male'] }}명</span>
                    <span class="gc-pill gc-female">여 {{ $this->genderCounts['female'] }}명</span>
                </div>

                <div x-data="{ tab: 'pending' }">
                    <div class="tab-bar">
                        <button @click="tab = 'pending'" :class="{ active: tab === 'pending' }" class="tab-btn">대기 ({{ $this->pendingAttendees->count() }})</button>
                        <button @click="tab = 'approved'" :class="{ active: tab === 'approved' }" class="tab-btn">승인 ({{ $this->approvedAttendees->count() }})</button>
                        <button @click="tab = 'rejected'" :class="{ active: tab === 'rejected' }" class="tab-btn">거절 ({{ $this->rejectedAttendees->count() }})</button>
                    </div>

                    {{-- 대기 목록 --}}
                    <div x-show="tab === 'pending'">
                        @forelse ($this->pendingAttendees as $attendee)
                            @php $photos = $attendee->user->photos ?? []; @endphp
                            <div wire:key="pending-{{ $attendee->id }}" class="review-card">
                                @if (count($photos))
                                    <div class="review-photo-wrap" x-data="{ i: 0, photos: {{ \Illuminate\Support\Js::from(array_map(fn ($p) => asset('storage/' . $p), $photos)) }} }">
                                        <img :src="photos[i]" @click="i = (i + 1) % photos.length" class="review-photo" style="cursor:pointer;" alt="{{ $attendee->user->name }} 사진">
                                        <div class="photo-counter" x-text="(i + 1) + ' / ' + photos.length"></div>
                                    </div>
                                @else
                                    <div class="review-photo-placeholder">사진<br>없음</div>
                                @endif

                                <div class="review-body">
                                    <div class="review-name-row">
                                        <span class="review-name">{{ $attendee->user->name }}</span>
                                        <span class="pill pill-pending">승인 대기</span>
                                    </div>
                                    <div class="review-sub">
                                        {{ $attendee->user->phone }}
                                        @if ($attendee->user->birth_date)
                                            · {{ \Carbon\Carbon::parse($attendee->user->birth_date)->age }}세
                                        @endif
                                        · {{ $attendee->user->gender === 'male' ? '남성' : ($attendee->user->gender === 'female' ? '여성' : '') }}
                                    </div>
                                    <div class="review-tags">
                                        @if ($attendee->user->job)<span class="review-tag">{{ $attendee->user->job }}</span>@endif
                                        @if ($attendee->user->instagram_handle)<span class="review-tag">{{ $attendee->user->instagram_handle }}</span>@endif
                                        @if ($attendee->user->hobbies_interests)<span class="review-tag">{{ $attendee->user->hobbies_interests }}</span>@endif
                                    </div>
                                    @if ($attendee->user->bio)<div class="review-bio">{{ $attendee->user->bio }}</div>@endif
                                    <div class="review-actions">
                                        <button wire:click="approve({{ $attendee->id }})" wire:confirm="{{ $attendee->user->name }}님을 승인할까요?" class="btn btn-primary">승인</button>
                                        <button wire:click="reject({{ $attendee->id }})" wire:confirm="{{ $attendee->user->name }}님을 거절할까요?" class="btn btn-outline">거절</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty">대기 중인 신청자가 없어요.</div>
                        @endforelse
                    </div>

                    {{-- 승인 완료 목록 --}}
                    <div x-show="tab === 'approved'" x-cloak>
                        @forelse ($this->approvedAttendees as $attendee)
                            @php $photos = $attendee->user->photos ?? []; @endphp
                            <div wire:key="approved-{{ $attendee->id }}" class="review-card">
                                @if (count($photos))
                                    <div class="review-photo-wrap" x-data="{ i: 0, photos: {{ \Illuminate\Support\Js::from(array_map(fn ($p) => asset('storage/' . $p), $photos)) }} }">
                                        <img :src="photos[i]" @click="i = (i + 1) % photos.length" class="review-photo" style="cursor:pointer;" alt="{{ $attendee->user->name }} 사진">
                                        <div class="photo-counter" x-text="(i + 1) + ' / ' + photos.length"></div>
                                    </div>
                                @else
                                    <div class="review-photo-placeholder">사진<br>없음</div>
                                @endif

                                <div class="review-body">
                                    <div class="review-name-row">
                                        <span class="review-name">{{ $attendee->user->name }}</span>
                                        <span class="pill pill-success">승인됨</span>
                                    </div>
                                    <div class="review-sub">
                                        {{ $attendee->user->phone }}
                                        @if ($attendee->user->birth_date)
                                            · {{ \Carbon\Carbon::parse($attendee->user->birth_date)->age }}세
                                        @endif
                                        · {{ $attendee->user->gender === 'male' ? '남성' : ($attendee->user->gender === 'female' ? '여성' : '') }}
                                    </div>
                                    <div class="review-tags">
                                        @if ($attendee->user->job)<span class="review-tag">{{ $attendee->user->job }}</span>@endif
                                        @if ($attendee->user->instagram_handle)<span class="review-tag">{{ $attendee->user->instagram_handle }}</span>@endif
                                        @if ($attendee->user->hobbies_interests)<span class="review-tag">{{ $attendee->user->hobbies_interests }}</span>@endif
                                    </div>
                                    @if ($attendee->user->bio)<div class="review-bio">{{ $attendee->user->bio }}</div>@endif
                                    <div class="review-actions">
                                        <button wire:click="reject({{ $attendee->id }})" wire:confirm="{{ $attendee->user->name }}님을 거절로 변경할까요?" class="btn btn-outline">거절로 변경</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty">아직 승인된 참가자가 없어요.</div>
                        @endforelse
                    </div>

                    {{-- 거절 목록 --}}
                    <div x-show="tab === 'rejected'" x-cloak>
                        @forelse ($this->rejectedAttendees as $attendee)
                            @php $photos = $attendee->user->photos ?? []; @endphp
                            <div wire:key="rejected-{{ $attendee->id }}" class="review-card">
                                @if (count($photos))
                                    <div class="review-photo-wrap" x-data="{ i: 0, photos: {{ \Illuminate\Support\Js::from(array_map(fn ($p) => asset('storage/' . $p), $photos)) }} }">
                                        <img :src="photos[i]" @click="i = (i + 1) % photos.length" class="review-photo" style="cursor:pointer;" alt="{{ $attendee->user->name }} 사진">
                                        <div class="photo-counter" x-text="(i + 1) + ' / ' + photos.length"></div>
                                    </div>
                                @else
                                    <div class="review-photo-placeholder">사진<br>없음</div>
                                @endif

                                <div class="review-body">
                                    <div class="review-name-row">
                                        <span class="review-name">{{ $attendee->user->name }}</span>
                                        <span class="pill pill-muted">거절됨</span>
                                    </div>
                                    <div class="review-sub">
                                        {{ $attendee->user->phone }}
                                        @if ($attendee->user->birth_date)
                                            · {{ \Carbon\Carbon::parse($attendee->user->birth_date)->age }}세
                                        @endif
                                        · {{ $attendee->user->gender === 'male' ? '남성' : ($attendee->user->gender === 'female' ? '여성' : '') }}
                                    </div>
                                    <div class="review-tags">
                                        @if ($attendee->user->job)<span class="review-tag">{{ $attendee->user->job }}</span>@endif
                                        @if ($attendee->user->instagram_handle)<span class="review-tag">{{ $attendee->user->instagram_handle }}</span>@endif
                                        @if ($attendee->user->hobbies_interests)<span class="review-tag">{{ $attendee->user->hobbies_interests }}</span>@endif
                                    </div>
                                    @if ($attendee->user->bio)<div class="review-bio">{{ $attendee->user->bio }}</div>@endif
                                    <div class="review-actions">
                                        <button wire:click="approve({{ $attendee->id }})" wire:confirm="{{ $attendee->user->name }}님을 승인으로 변경할까요?" class="btn btn-primary">승인으로 변경</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty">거절된 신청자가 없어요.</div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>