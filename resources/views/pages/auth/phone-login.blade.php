<?php

use App\Models\User;
use App\Services\PhoneAuthService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::auth')] class extends Component
{
    public string $mode = 'code'; // code | otp

    public string $phone = '';
    public string $code = '';
    public bool $codeSent = false;

    public string $memberPhone = '';
    public string $memberCode = '';

    public function switchMode(string $mode): void
    {
        $this->mode = $mode;
        $this->resetErrorBag();
    }

    public function loginWithCode()
    {
        $this->validate([
            'memberPhone' => 'required|regex:/^01[0-9]{8,9}$/',
            'memberCode' => 'required|string|size:8',
        ], [
            'memberPhone.regex' => '올바른 휴대폰 번호를 입력해주세요.',
        ]);

        $user = User::where('phone', $this->memberPhone)
            ->where('member_code', strtoupper($this->memberCode))
            ->first();

        if (! $user) {
            $this->addError('memberCode', '전화번호 또는 회원코드가 올바르지 않아요.');
            return;
        }

        Auth::login($user, remember: true);

        return redirect('/mypage');
    }

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

        Auth::login($user, remember: true);

        return redirect('/mypage');
    }
}; ?>

<div class="mv-auth-card">
    <div class="mv-auth-logo">Solo Social Party</div>
    <h1 class="mv-form-title">로그인</h1>

    <div style="display:flex;gap:8px;margin-bottom:22px;">
        <button type="button" wire:click="switchMode('code')" class="tab-btn {{ $mode === 'code' ? 'active' : '' }}" style="flex:1;">회원코드로</button>
        <button type="button" wire:click="switchMode('otp')" class="tab-btn {{ $mode === 'otp' ? 'active' : '' }}" style="flex:1;">인증번호로</button>
    </div>

    @if ($mode === 'code')
        <div wire:key="mode-code">
            <p class="mv-form-sub" style="margin-bottom:20px;">신청 완료 후 받으신 회원코드로 바로 로그인하세요.</p>

            <div class="field">
                <label>전화번호</label>
                <input type="text" wire:model="memberPhone" placeholder="01012345678">
                @error('memberPhone') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label>회원코드 (8자리)</label>
                <input type="text" wire:model="memberCode" maxlength="8" placeholder="예: A3F9K2P7" style="text-transform:uppercase;letter-spacing:2px;">
                @error('memberCode') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <button wire:click="loginWithCode" class="btn btn-primary btn-block">로그인</button>
        </div>
    @else
        <div wire:key="mode-otp">
            <p class="mv-form-sub" style="margin-bottom:20px;">회원코드가 없으시면 전화번호 인증으로 로그인하세요.</p>

            @if (session('message'))
                <div class="mv-alert mv-alert-success">{{ session('message') }}</div>
            @endif

            @if (! $codeSent)
                <div wire:key="phone-step" class="field">
                    <label>전화번호</label>
                    <input type="text" wire:model="phone" placeholder="01012345678">
                    @error('phone') <span class="field-error">{{ $message }}</span> @enderror
                </div>
                <button wire:click="sendCode" class="btn btn-primary btn-block">인증번호 받기</button>
            @else
                <div wire:key="code-step" class="field">
                    <label>인증번호 (6자리)</label>
                    <input type="text" wire:model="code" maxlength="6">
                    @error('code') <span class="field-error">{{ $message }}</span> @enderror
                </div>
                <button wire:click="verify" class="btn btn-primary btn-block">확인하고 로그인</button>
            @endif
        </div>
    @endif
</div>