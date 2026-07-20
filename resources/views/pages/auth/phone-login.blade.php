<?php

use App\Services\PhoneAuthService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::auth')] class extends Component
{
    public string $phone = '';
    public string $code = '';
    public bool $codeSent = false;

    public function sendCode(PhoneAuthService $service)
    {
        $this->validate([
            'phone' => 'required|regex:/^01[0-9]{8,9}$/',
        ], [
            'phone.regex' => '올바른 휴대폰 번호를 입력해주세요. (- 없이 숫자만)',
        ]);

        $service->sendCode($this->phone);
        $this->codeSent = true;

        session()->flash('message', '인증번호를 발송했어요.');
    }

    public function verify(PhoneAuthService $service)
    {
        $this->validate([
            'code' => 'required|digits:6',
        ]);

        $verified = $service->verifyCode($this->phone, $this->code);

        if (! $verified) {
            $this->addError('code', '인증번호가 올바르지 않거나 만료됐어요.');
            return;
        }

        $user = $service->findOrCreateUser($this->phone);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}; ?>

<div class="max-w-sm mx-auto mt-10 space-y-4">
    @if (session('message'))
        <div class="text-green-600 text-sm">{{ session('message') }}</div>
    @endif

    @if (! $codeSent)
        <div wire:key="phone-step">
            <label class="block text-sm font-medium mb-1">전화번호</label>
            <input type="text" wire:model="phone" placeholder="01012345678" class="w-full border rounded px-3 py-2">
            @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button wire:click="sendCode" class="w-full bg-black text-white rounded py-2">인증번호 받기</button>
    @else
        <div wire:key="code-step">
            <label class="block text-sm font-medium mb-1">인증번호 (6자리)</label>
            <input type="text" wire:model="code" maxlength="6" class="w-full border rounded px-3 py-2">
            @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button wire:click="verify" class="w-full bg-black text-white rounded py-2">확인하고 로그인</button>
    @endif
</div>
