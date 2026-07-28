<?php

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => '사이트 설정'])] class extends Component
{
    public string $site_name = '';
    public string $support_phone = '';
    public string $support_email = '';
    public string $kakao_channel_url = '';
    public string $instagram_url = '';
    public string $bank_name = '';
    public string $bank_account_number = '';
    public string $bank_account_holder = '';
    public bool $saved = false;

    public function mount(): void
    {
        $this->site_name = Setting::get('site_name', 'MEEWV');
        $this->support_phone = Setting::get('support_phone', '');
        $this->support_email = Setting::get('support_email', '');
        $this->kakao_channel_url = Setting::get('kakao_channel_url', '');
        $this->instagram_url = Setting::get('instagram_url', '');
        $this->bank_name = Setting::get('bank_name', '');
        $this->bank_account_number = Setting::get('bank_account_number', '');
        $this->bank_account_holder = Setting::get('bank_account_holder', '');
    }

    public function save(): void
    {
        $this->validate([
            'site_name' => 'required|string|max:50',
            'support_phone' => 'nullable|string|max:30',
            'support_email' => 'nullable|email|max:100',
            'kakao_channel_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
        ]);

        Setting::set('site_name', $this->site_name);
        Setting::set('support_phone', $this->support_phone);
        Setting::set('support_email', $this->support_email);
        Setting::set('kakao_channel_url', $this->kakao_channel_url);
        Setting::set('instagram_url', $this->instagram_url);
        Setting::set('bank_name', $this->bank_name);
        Setting::set('bank_account_number', $this->bank_account_number);
        Setting::set('bank_account_holder', $this->bank_account_holder);

        $this->saved = true;
    }
}; ?>

<div>
    <div class="mv-page-head">
        <h2>사이트 설정</h2>
        <p>기본 정보와 연락 채널을 관리하세요.</p>
    </div>

    @if ($saved)
        <div class="mv-alert mv-alert-success">저장됐어요.</div>
    @endif

    <div class="crud-form" style="max-width:560px;">
        <div class="field">
            <label>사이트명</label>
            <input type="text" wire:model="site_name">
            @error('site_name') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label>고객센터 전화번호</label>
            <input type="text" wire:model="support_phone" placeholder="010-0000-0000">
            @error('support_phone') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label>고객센터 이메일</label>
            <input type="text" wire:model="support_email" placeholder="hello@meewv.com">
            @error('support_email') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label>카카오 채널 URL</label>
            <input type="text" wire:model="kakao_channel_url" placeholder="https://pf.kakao.com/...">
            @error('kakao_channel_url') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label>인스타그램 URL</label>
            <input type="text" wire:model="instagram_url" placeholder="https://instagram.com/...">
            @error('instagram_url') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="crud-form-title" style="margin-top:10px;">무통장입금 계좌 정보</div>

        <div class="field">
            <label>은행명</label>
            <input type="text" wire:model="bank_name" placeholder="예: 카카오뱅크">
        </div>

        <div class="field">
            <label>계좌번호</label>
            <input type="text" wire:model="bank_account_number" placeholder="예: 3333-00-0000000">
        </div>

        <div class="field">
            <label>예금주</label>
            <input type="text" wire:model="bank_account_holder" placeholder="예: (주)미브">
        </div>

        <button wire:click="save" class="btn btn-primary">저장</button>
    </div>
</div>
