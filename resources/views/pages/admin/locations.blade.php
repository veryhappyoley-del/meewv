<?php

use App\Models\Location;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => '지점 관리'])] class extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $address = '';
    public string $operating_days = '';
    public string $description = '';

    #[Computed]
    public function locations()
    {
        return Location::withCount('events')->orderBy('name')->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $loc = Location::findOrFail($id);
        $this->editingId = $loc->id;
        $this->name = $loc->name;
        $this->address = $loc->address ?? '';
        $this->operating_days = $loc->operating_days ?? '';
        $this->description = $loc->description ?? '';
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'operating_days' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        if ($this->editingId) {
            Location::findOrFail($this->editingId)->update($data);
        } else {
            Location::create($data);
        }

        $this->resetForm();
        $this->showForm = false;
        unset($this->locations);
    }

    public function delete(int $id): void
    {
        Location::findOrFail($id)->delete();
        unset($this->locations);
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'address', 'operating_days', 'description']);
        $this->resetErrorBag();
    }
}; ?>

<div>
    <div class="mv-page-head">
        <h2>지점 관리</h2>
        <p>지점명, 주소, 운영요일, 홈페이지 소개 문구를 관리하세요.</p>
    </div>

    <div class="crud-toolbar">
        <span></span>
        <button wire:click="openCreate" class="btn btn-primary">+ 새 지점 추가</button>
    </div>

    @if ($showForm)
        <div class="crud-form">
            <div class="crud-form-title">{{ $editingId ? '지점 수정' : '새 지점 추가' }}</div>

            <div class="field">
                <label>지점명</label>
                <input type="text" wire:model="name" placeholder="예: 강남점">
                @error('name') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label>주소</label>
                <input type="text" wire:model="address" placeholder="예: 서울 강남구 ...">
            </div>

            <div class="field">
                <label>운영 요일</label>
                <input type="text" wire:model="operating_days" placeholder="예: 금, 토">
            </div>

            <div class="field">
                <label>홈페이지 소개 문구 (2~3줄 권장)</label>
                <textarea wire:model="description" rows="3" placeholder="이 지점만의 분위기를 짧게 소개해주세요."></textarea>
                @error('description') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div style="display:flex;gap:8px;">
                <button wire:click="save" class="btn btn-primary">{{ $editingId ? '수정 저장' : '추가' }}</button>
                <button wire:click="cancel" class="btn btn-outline">취소</button>
            </div>
        </div>
    @endif

    @forelse ($this->locations as $loc)
        <div wire:key="loc-{{ $loc->id }}" class="item-card">
            <div class="item-main">
                <div class="item-name">{{ $loc->name }}</div>
                <div class="item-meta">{{ $loc->address }} · {{ $loc->operating_days }} · 회차 {{ $loc->events_count }}개</div>
            </div>
            <div class="item-actions">
                <button wire:click="edit({{ $loc->id }})" class="btn btn-outline btn-sm">수정</button>
                <button wire:click="delete({{ $loc->id }})" wire:confirm="이 지점을 삭제할까요?" class="btn btn-danger btn-sm">삭제</button>
            </div>
        </div>
    @empty
        <div class="empty">등록된 지점이 없어요.</div>
    @endforelse
</div>