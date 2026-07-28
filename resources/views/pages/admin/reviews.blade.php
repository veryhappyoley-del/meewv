<?php

use App\Models\Review;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::app', ['title' => '후기 관리'])] class extends Component
{
    use WithFileUploads;

    public bool $showForm = false;
    public ?int $editingId = null;

    public string $author_name = '';
    public string $content = '';
    public $photo = null;
    public ?string $existingPhotoPath = null;
    public bool $is_published = true;

    #[Computed]
    public function reviews()
    {
        return Review::orderBy('sort_order')->orderByDesc('id')->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $review = Review::findOrFail($id);
        $this->editingId = $review->id;
        $this->author_name = $review->author_name;
        $this->content = $review->content;
        $this->existingPhotoPath = $review->photo_path;
        $this->is_published = (bool) $review->is_published;
        $this->photo = null;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'author_name' => 'required|string|max:30',
            'content' => 'required|string|max:100',
            'photo' => ($this->editingId ? 'nullable' : 'required') . '|image|max:5120',
            'is_published' => 'boolean',
        ]);

        unset($data['photo']);

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('reviews', 'public');
        }

        if ($this->editingId) {
            Review::findOrFail($this->editingId)->update($data);
        } else {
            Review::create($data);
        }

        $this->resetForm();
        $this->showForm = false;
        unset($this->reviews);
    }

    public function delete(int $id): void
    {
        Review::findOrFail($id)->delete();
        unset($this->reviews);
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'author_name', 'content', 'photo', 'existingPhotoPath']);
        $this->is_published = true;
        $this->resetErrorBag();
    }
}; ?>

<div>
    <div class="mv-page-head">
        <h2>후기 관리</h2>
        <p>사진 한 장과 100자 이내 후기를 등록하면 홈페이지에 바로 노출돼요.</p>
    </div>

    <div class="crud-toolbar">
        <span></span>
        <button wire:click="openCreate" class="btn btn-primary">+ 새 후기 등록</button>
    </div>

    @if ($showForm)
        <div class="crud-form">
            <div class="crud-form-title">{{ $editingId ? '후기 수정' : '새 후기 등록' }}</div>

            <div class="field">
                <label>작성자 이름 (닉네임 가능)</label>
                <input type="text" wire:model="author_name" placeholder="예: 지훈">
                @error('author_name') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label>사진</label>
                <input type="file" wire:model="photo" accept="image/*">
                @error('photo') <span class="field-error">{{ $message }}</span> @enderror

                @if ($photo)
                    <img src="{{ $photo->temporaryUrl() }}" style="width:100px;height:100px;object-fit:cover;border-radius:10px;border:1px solid var(--line);margin-top:8px;">
                @elseif ($existingPhotoPath)
                    <img src="{{ asset('storage/' . $existingPhotoPath) }}" style="width:100px;height:100px;object-fit:cover;border-radius:10px;border:1px solid var(--line);margin-top:8px;">
                    <div style="font-size:11.5px;color:var(--text-lo);margin-top:4px;">사진을 새로 올리지 않으면 기존 사진이 유지돼요.</div>
                @endif
            </div>

            <div class="field">
                <label>후기 내용 (최대 100자) — {{ strlen($content) }}/100</label>
                <textarea wire:model.live="content" rows="3" maxlength="100" placeholder="와서 경험했던 순간을 짧게 적어주세요."></textarea>
                @error('content') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <label class="check-row">
                <input type="checkbox" wire:model="is_published"> 홈페이지에 노출
            </label>

            <div style="display:flex;gap:8px;margin-top:8px;">
                <button wire:click="save" class="btn btn-primary">{{ $editingId ? '수정 저장' : '등록' }}</button>
                <button wire:click="cancel" class="btn btn-outline">취소</button>
            </div>
        </div>
    @endif

    @forelse ($this->reviews as $review)
        <div wire:key="review-{{ $review->id }}" class="item-card">
            <div class="item-main" style="flex-direction:row;align-items:center;gap:14px;">
                @if ($review->photo_path)
                    <img src="{{ asset('storage/' . $review->photo_path) }}" style="width:52px;height:52px;object-fit:cover;border-radius:10px;border:1px solid var(--line);flex-shrink:0;">
                @endif
                <div>
                    <div class="item-name">
                        {{ $review->author_name }}
                        <span class="pill {{ $review->is_published ? 'pill-success' : 'pill-muted' }}" style="margin-left:6px;">
                            {{ $review->is_published ? '노출중' : '비노출' }}
                        </span>
                    </div>
                    <div class="item-meta">{{ $review->content }}</div>
                </div>
            </div>
            <div class="item-actions">
                <button wire:click="edit({{ $review->id }})" class="btn btn-outline btn-sm">수정</button>
                <button wire:click="delete({{ $review->id }})" wire:confirm="이 후기를 삭제할까요?" class="btn btn-danger btn-sm">삭제</button>
            </div>
        </div>
    @empty
        <div class="empty">등록된 후기가 없어요.</div>
    @endforelse
</div>
