<?php

use App\Models\Category;
use App\Models\Event;
use App\Models\Location;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => '회차 관리'])] class extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public ?int $location_id = null;
    public ?int $category_id = null;
    public string $event_date = '';
    public string $start_time = '';
    public string $end_time = '';
    public int $capacity = 8;
    public int $price = 0;
    public string $status = 'open';
    public string $meeting_point = '';
    public string $guide_note = '';

    #[Computed]
    public function locations()
    {
        return Location::orderBy('name')->get();
    }

    #[Computed]
    public function categories()
    {
        return Category::orderBy('name')->get();
    }

    #[Computed]
    public function events()
    {
        return Event::with(['location', 'category'])
            ->withCount([
                'attendees as pending_count' => fn ($q) => $q->where('approval_status', 'pending'),
                'attendees as approved_count' => fn ($q) => $q->where('approval_status', 'approved'),
            ])
            ->orderByDesc('event_date')
            ->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $event = Event::findOrFail($id);
        $this->editingId = $event->id;
        $this->location_id = $event->location_id;
        $this->category_id = $event->category_id;
        $this->event_date = $event->event_date;
        $this->start_time = $event->start_time;
        $this->end_time = $event->end_time ?? '';
        $this->capacity = $event->capacity;
        $this->price = $event->price;
        $this->status = $event->status;
        $this->meeting_point = $event->meeting_point ?? '';
        $this->guide_note = $event->guide_note ?? '';
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'location_id' => 'required|exists:locations,id',
            'category_id' => 'required|exists:categories,id',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
            'status' => 'required|in:open,closed,finished',
            'meeting_point' => 'nullable|string|max:255',
            'guide_note' => 'nullable|string|max:1000',
        ]);

        if ($this->editingId) {
            Event::findOrFail($this->editingId)->update($data);
        } else {
            Event::create($data);
        }

        $this->resetForm();
        $this->showForm = false;
        unset($this->events);
    }

    public function delete(int $id): void
    {
        Event::findOrFail($id)->delete();
        unset($this->events);
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'location_id', 'category_id', 'end_time', 'meeting_point', 'guide_note']);
        $this->event_date = '';
        $this->start_time = '';
        $this->capacity = 8;
        $this->price = 0;
        $this->status = 'open';
        $this->resetErrorBag();
    }
}; ?>

<div>
    <div class="mv-page-head">
        <h2>회차 관리</h2>
        <p>파티·러닝·요가 회차를 만들고 관리하세요.</p>
    </div>

    <div class="crud-toolbar">
        <span></span>
        <button wire:click="openCreate" class="btn btn-primary">+ 새 회차 만들기</button>
    </div>

    @if ($showForm)
        <div class="crud-form">
            <div class="crud-form-title">{{ $editingId ? '회차 수정' : '새 회차 만들기' }}</div>

            <div class="form-row-2">
                <div class="field">
                    <label>지점</label>
                    <select wire:model="location_id">
                        <option value="">선택</option>
                        @foreach ($this->locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                    </select>
                    @error('location_id') <span class="field-error">{{ $message }}</span> @enderror
                </div>
                <div class="field">
                    <label>카테고리</label>
                    <select wire:model="category_id">
                        <option value="">선택</option>
                        @foreach ($this->categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="field-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row-3">
                <div class="field">
                    <label>날짜</label>
                    <input type="date" wire:model="event_date">
                    @error('event_date') <span class="field-error">{{ $message }}</span> @enderror
                </div>
                <div class="field">
                    <label>시작 시간</label>
                    <input type="time" wire:model="start_time">
                    @error('start_time') <span class="field-error">{{ $message }}</span> @enderror
                </div>
                <div class="field">
                    <label>종료 시간 (선택)</label>
                    <input type="time" wire:model="end_time">
                </div>
            </div>

            <div class="form-row-3">
                <div class="field">
                    <label>정원</label>
                    <input type="number" wire:model="capacity" min="1">
                    @error('capacity') <span class="field-error">{{ $message }}</span> @enderror
                </div>
                <div class="field">
                    <label>참가비 (원)</label>
                    <input type="number" wire:model="price" min="0" step="1000" placeholder="0">
                    @error('price') <span class="field-error">{{ $message }}</span> @enderror
                </div>
                <div class="field">
                    <label>상태</label>
                    <select wire:model="status">
                        <option value="open">모집중</option>
                        <option value="closed">마감</option>
                        <option value="finished">종료됨</option>
                    </select>
                </div>
            </div>

            <div class="field">
                <label>만남 장소 (승인자에게만 공개)</label>
                <input type="text" wire:model="meeting_point" placeholder="예: OO라운지 3층">
            </div>

            <div class="field">
                <label>안내 사항 (드레스코드, 준비물 등)</label>
                <textarea wire:model="guide_note" rows="3"></textarea>
            </div>

            <div style="display:flex;gap:8px;">
                <button wire:click="save" class="btn btn-primary">{{ $editingId ? '수정 저장' : '만들기' }}</button>
                <button wire:click="cancel" class="btn btn-outline">취소</button>
            </div>
        </div>
    @endif

    @forelse ($this->events as $event)
        <div wire:key="event-{{ $event->id }}" class="item-card">
            <div class="item-main">
                <div class="item-name">
                    {{ $event->category?->name }} · {{ $event->location?->name }}
                    <span class="pill pill-{{ $event->status === 'open' ? 'open' : 'closed' }}" style="margin-left:6px;">
                        {{ $event->status === 'open' ? '모집중' : ($event->status === 'closed' ? '마감' : '종료됨') }}
                    </span>
                </div>
                <div class="item-meta">
                    {{ $event->event_date }} · {{ $event->start_time }}{{ $event->end_time ? ' ~ '.$event->end_time : '' }}
                    · 정원 {{ $event->capacity }}명 · 참가비 {{ number_format($event->price) }}원 · 대기 {{ $event->pending_count }} · 승인 {{ $event->approved_count }}
                </div>
            </div>
            <div class="item-actions">
                <button wire:click="edit({{ $event->id }})" class="btn btn-outline btn-sm">수정</button>
                <button wire:click="delete({{ $event->id }})" wire:confirm="이 회차를 삭제할까요? 신청자 정보도 함께 삭제돼요." class="btn btn-danger btn-sm">삭제</button>
            </div>
        </div>
    @empty
        <div class="empty">만들어진 회차가 없어요. "새 회차 만들기"로 시작하세요.</div>
    @endforelse
</div>
