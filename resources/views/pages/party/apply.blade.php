<?php

use App\Models\Event;
use App\Models\EventAttendee;
use App\Services\PhoneAuthService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::auth')] class extends Component
{
    use WithFileUploads;

    public int $step = 1;

    public ?int $event_id = null;

    public string $name = '';
    public string $birth_date = '';
    public string $gender = '';
    public $photo;
    public string $phone = '';
    public string $bio = '';
    public string $job = '';
    public string $instagram_handle = '';
    public string $hobbies_interests = '';

    public string $code = '';

    #[Computed]
    public function events()
    {
        return Event::with(['location', 'category'])
            ->where('status', 'open')
            ->orderBy('event_date')
            ->get();
    }

    public function selectEvent(int $eventId)
    {
        $this->event_id = $eventId;
        $this->step = 2;
    }

    public function submitProfile(PhoneAuthService $service)
    {
        $this->validate([
            'name' => 'required|string|max:50',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'photo' => 'nullable|image|max:5120',
            'phone' => 'required|regex:/^01[0-9]{8,9}$/',
            'bio' => 'nullable|string|max:500',
            'job' => 'nullable|string|max:100',
            'instagram_handle' => 'nullable|string|max:50',
            'hobbies_interests' => 'nullable|string|max:500',
        ], [
            'phone.regex' => '올바른 휴대폰 번호를 입력해주세요. (- 없이 숫자만)',
        ]);

        $service->sendCode($this->phone);
        $this->step = 3;
    }

    public function verifyAndSubmit(PhoneAuthService $service)
    {
        $this->validate([
            'code' => 'required|digits:6',
        ]);

        if (! $service->verifyCode($this->phone, $this->code)) {
            $this->addError('code', '인증번호가 올바르지 않거나 만료됐어요.');
            return;
        }

        $photoPath = $this->photo ? $this->photo->store('profile-photos', 'public') : null;

        $profile = [
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'bio' => $this->bio,
            'job' => $this->job,
            'instagram_handle' => $this->instagram_handle,
            'hobbies_interests' => $this->hobbies_interests,
        ];

        if ($photoPath) {
            $profile['photo_url'] = $photoPath;
        }

        $user = $service->findOrCreateUser($this->phone, $profile);
        $user->update($profile);

        EventAttendee::firstOrCreate(
            ['event_id' => $this->event_id, 'user_id' => $user->id],
            ['status' => 'registered', 'approval_status' => 'pending']
        );

        Auth::login($user);

        $this->step = 4;
    }
}; ?>

<div class="max-w-lg mx-auto mt-10 space-y-6 px-4">

    @if ($step === 1)
        <div wire:key="step-1">
            <h2 class="text-xl font-bold mb-4">참여하고 싶은 모임을 선택하세요</h2>
            <div class="space-y-3">
                @forelse ($this->events as $event)
                    <button
                        wire:click="selectEvent({{ $event->id }})"
                        wire:key="event-{{ $event->id }}"
                        class="w-full text-left border rounded p-4 hover:border-black"
                    >
                        <div class="font-semibold">{{ $event->category?->name }} · {{ $event->location?->name }}</div>
                        <div class="text-sm text-gray-500">{{ $event->event_date }} {{ $event->start_time }}</div>
                    </button>
                @empty
                    <p class="text-gray-500">현재 신청 가능한 모임이 없어요.</p>
                @endforelse
            </div>
        </div>
    @endif

    @if ($step === 2)
        <div wire:key="step-2" class="space-y-4">
            <h2 class="text-xl font-bold">참가자 정보를 입력해주세요</h2>

            <div>
                <label class="block text-sm font-medium mb-1">이름</label>
                <input type="text" wire:model="name" class="w-full border rounded px-3 py-2">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">생년월일</label>
                <input type="date" wire:model="birth_date" class="w-full border rounded px-3 py-2">
                @error('birth_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">성별</label>
                <select wire:model="gender" class="w-full border rounded px-3 py-2">
                    <option value="">선택</option>
                    <option value="male">남성</option>
                    <option value="female">여성</option>
                </select>
                @error('gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">사진</label>
                <input type="file" wire:model="photo" class="w-full border rounded px-3 py-2">
                @error('photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @if ($photo)
                    <img src="{{ $photo->temporaryUrl() }}" class="mt-2 w-24 h-24 object-cover rounded">
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">전화번호</label>
                <input type="text" wire:model="phone" placeholder="01012345678" class="w-full border rounded px-3 py-2">
                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">간략한 소개글</label>
                <textarea wire:model="bio" rows="3" class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">직업</label>
                <input type="text" wire:model="job" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">인스타그램</label>
                <input type="text" wire:model="instagram_handle" placeholder="@handle" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">취미 및 요즘 관심사</label>
                <textarea wire:model="hobbies_interests" rows="3" class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <button wire:click="submitProfile" class="w-full bg-black text-white rounded py-2">
                인증번호 받고 신청하기
            </button>
        </div>
    @endif

    @if ($step === 3)
        <div wire:key="step-3" class="space-y-4">
            <h2 class="text-xl font-bold">전화번호 인증</h2>
            <p class="text-sm text-gray-500">{{ $phone }}로 발송된 인증번호를 입력해주세요.</p>
            <input type="text" wire:model="code" maxlength="6" class="w-full border rounded px-3 py-2">
            @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            <button wire:click="verifyAndSubmit" class="w-full bg-black text-white rounded py-2">
                확인하고 신청 완료
            </button>
        </div>
    @endif

    @if ($step === 4)
        <div wire:key="step-4" class="text-center space-y-4">
            <h2 class="text-xl font-bold">신청이 완료됐어요!</h2>
            <p class="text-gray-500">관리자 승인 후 카카오톡으로 안내드릴게요.</p>
        </div>
    @endif

</div>
