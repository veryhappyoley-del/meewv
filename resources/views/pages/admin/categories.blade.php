<?php

use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Str;

new #[Layout('layouts::app', ['title' => '카테고리 관리'])] class extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public bool $is_active = true;

    #[Computed]
    public function categories()
    {
        return Category::withCount('events')->orderBy('name')->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $cat = Category::findOrFail($id);
        $this->editingId = $cat->id;
        $this->name = $cat->name;
        $this->slug = $cat->slug;
        $this->description = $cat->description ?? '';
        $this->is_active = (bool) $cat->is_active;
        $this->showForm = true;
    }

    public function updatedName(string $value): void
    {
        if (! $this->editingId) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:50|unique:categories,slug,' . $this->editingId,
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($this->editingId) {
            Category::findOrFail($this->editingId)->update($data);
        } else {
            Category::create($data);
        }

        $this->resetForm();
        $this->showForm = false;
        unset($this->categories);
    }

    public function delete(int $id): void
    {
        Category::findOrFail($id)->delete();
        unset($this->categories);
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'slug', 'description']);
        $this->is_active = true;
        $this->resetErrorBag();
    }
}; ?>

<div>
    <div class="mv-page-head">
        <h2>카테고리 관리</h2>
        <p>파티, 러닝, 요가처럼 모임 종목을 관리하세요.</p>
    </div>

    <div class="crud-toolbar">
        <span></span>
        <button wire:click="openCreate" class="btn btn-primary">+ 새 카테고리 추가</button>
    </div>

    @if ($showForm)
        <div class="crud-form">
            <div class="crud-form-title">{{ $editingId ? '카테고리 수정' : '새 카테고리 추가' }}</div>

            <div class="form-row-2">
                <div class="field">
                    <label>이름</label>
                    <input type="text" wire:model.live="name" placeholder="예: 클라이밍">
                    @error('name') <span class="field-error">{{ $message }}</span> @enderror
                </div>
                <div class="field">
                    <label>슬러그 (영문, URL용)</label>
                    <input type="text" wire:model="slug" placeholder="예: climbing">
                    @error('slug') <span class="field-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="field">
                <label>설명 (선택)</label>
                <input type="text" wire:model="description">
            </div>

            <label class="check-row">
                <input type="checkbox" wire:model="is_active"> 활성화 (신청 화면에 노출)
            </label>

            <div style="display:flex;gap:8px;margin-top:8px;">
                <button wire:click="save" class="btn btn-primary">{{ $editingId ? '수정 저장' : '추가' }}</button>
                <button wire:click="cancel" class="btn btn-outline">취소</button>
            </div>
        </div>
    @endif

    @forelse ($this->categories as $cat)
        <div wire:key="cat-{{ $cat->id }}" class="item-card">
            <div class="item-main">
                <div class="item-name">
                    {{ $cat->name }}
                    <span class="pill {{ $cat->is_active ? 'pill-success' : 'pill-muted' }}" style="margin-left:6px;">
                        {{ $cat->is_active ? '활성' : '비활성' }}
                    </span>
                </div>
                <div class="item-meta">/{{ $cat->slug }} · 회차 {{ $cat->events_count }}개</div>
            </div>
            <div class="item-actions">
                <button wire:click="edit({{ $cat->id }})" class="btn btn-outline btn-sm">수정</button>
                <button wire:click="delete({{ $cat->id }})" wire:confirm="이 카테고리를 삭제할까요?" class="btn btn-danger btn-sm">삭제</button>
            </div>
        </div>
    @empty
        <div class="empty">등록된 카테고리가 없어요.</div>
    @endforelse
</div>
