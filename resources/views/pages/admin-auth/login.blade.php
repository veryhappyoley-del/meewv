<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::auth')] class extends Component
{
    public string $email = '';
    public string $password = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|string|max:100',
            'password' => 'required',
        ]);

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('email', '아이디 또는 비밀번호가 올바르지 않아요.');
            return;
        }

        if (! Auth::user()->is_admin) {
            Auth::logout();
            $this->addError('email', '관리자 계정이 아니에요.');
            return;
        }

        request()->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }
}; ?>

<div class="mv-auth-card">
    <div class="mv-auth-logo">MEEWV Admin</div>
    <h1 class="mv-form-title">관리자 로그인</h1>
    <p class="mv-form-sub">관리자 계정으로 로그인해주세요.</p>

    <div class="field">
        <label>아이디</label>
        <input type="text" wire:model="email" placeholder="admin">
        @error('email') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label>비밀번호</label>
        <input type="password" wire:model="password">
        @error('password') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <button wire:click="login" class="btn btn-primary btn-block">로그인</button>
</div>