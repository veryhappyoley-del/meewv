<?php

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::app', ['title' => '회원 관리'])] class extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'users' => User::query()
                ->when($this->search, function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%")
                        ->orWhere('instagram_handle', 'like', "%{$this->search}%");
                })
                ->withCount('eventAttendances')
                ->latest()
                ->paginate(15),
        ];
    }
}; ?>

<div>
    <div class="mv-page-head">
        <h2>회원 관리</h2>
        <p>지금까지 신청한 모든 회원이에요.</p>
    </div>

    <div class="search-bar">
        <input type="text" wire:model.live.debounce.400ms="search" placeholder="이름, 전화번호, 인스타로 검색" style="padding:10px 13px;border-radius:10px;border:1px solid var(--line);background:rgba(246,242,251,.03);color:var(--text-hi);width:100%;font-size:14px;">
    </div>

    <table class="mv-table">
        <thead>
            <tr>
                <th>이름</th>
                <th>연락처</th>
                <th>성별/나이</th>
                <th>직업</th>
                <th>인스타그램</th>
                <th>참여 횟수</th>
                <th>가입일</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr wire:key="user-{{ $user->id }}">
                    <td style="font-weight:700;">{{ $user->name }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>
                        {{ $user->gender === 'male' ? '남' : ($user->gender === 'female' ? '여' : '-') }}
                        @if ($user->birth_date)
                            · {{ \Carbon\Carbon::parse($user->birth_date)->age }}세
                        @endif
                    </td>
                    <td>{{ $user->job ?: '-' }}</td>
                    <td>{{ $user->instagram_handle ?: '-' }}</td>
                    <td>{{ $user->event_attendances_count }}회</td>
                    <td style="color:var(--text-lo);">{{ $user->created_at?->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr><td colspan="7"><div class="empty">검색 결과가 없어요.</div></td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:16px;">
        {{ $users->links() }}
    </div>
</div>
