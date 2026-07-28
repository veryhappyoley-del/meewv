<?php

use App\Models\Category;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
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
    public array $photos = [];
    public string $phone = '';
    public string $bio = '';
    public string $job = '';
    public string $instagram_handle = '';
    public string $hobbies_interests = '';
    public bool $privacyConsent = false;
    public string $memberCode = '';

    public string $code = '';

    public ?int $categoryFilter = null;

    public ?int $attendeeId = null;
    public ?int $userId = null;
    public string $paymentMethod = '';
    public string $depositorName = '';
    public bool $cashReceiptRequested = false;
    public string $cashReceiptType = 'personal';
    public string $cashReceiptNumber = '';

    #[Computed]
    public function categories()
    {
        return Category::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function bankInfo()
    {
        return [
            'name' => Setting::get('bank_name', ''),
            'number' => Setting::get('bank_account_number', ''),
            'holder' => Setting::get('bank_account_holder', ''),
        ];
    }

    #[Computed]
    public function events()
    {
        return Event::with(['location', 'category'])
            ->where('status', 'open')
            ->where('event_date', '>=', now()->toDateString())
            ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
            ->orderBy('event_date')
            ->get();
    }

    public function filterCategory(?int $categoryId): void
    {
        $this->categoryFilter = $categoryId;
    }

    #[Computed]
    public function selectedEventModel()
    {
        return $this->event_id ? Event::with(['location', 'category'])->find($this->event_id) : null;
    }

    public function selectEvent(int $eventId)
    {
        $this->event_id = $eventId;
        $this->step = 2;
    }

    public function backTo(int $step)
    {
        $this->step = $step;
    }

    public function removePhoto(int $index): void
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    public function submitProfile(PhoneAuthService $service)
    {
        $this->validate([
            'name' => 'required|string|max:50',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'photos' => 'required|array|min:1|max:4',
            'photos.*' => 'image|max:5120',
            'phone' => 'required|regex:/^01[0-9]{8,9}$/',
            'bio' => 'required|string|max:500',
            'job' => 'nullable|string|max:100',
            'instagram_handle' => 'nullable|string|max:50',
            'hobbies_interests' => 'nullable|string|max:500',
            'privacyConsent' => 'accepted',
        ], [
            'phone.regex' => '올바른 휴대폰 번호를 입력해주세요. (- 없이 숫자만)',
            'photos.required' => '사진을 1장 이상 올려주세요.',
            'photos.max' => '사진은 최대 4장까지 올릴 수 있어요.',
            'privacyConsent.accepted' => '개인정보 수집·이용에 동의해주셔야 신청할 수 있어요.',
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

        $photoPaths = [];
        foreach ($this->photos as $photo) {
            $photoPaths[] = $photo->store('profile-photos', 'public');
        }

        $profile = [
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'bio' => $this->bio,
            'job' => $this->job,
            'instagram_handle' => $this->instagram_handle,
            'hobbies_interests' => $this->hobbies_interests,
        ];

        if ($photoPaths) {
            $profile['photos'] = $photoPaths;
        }

        $user = $service->findOrCreateUser($this->phone, $profile);
        $user->update($profile);

        $attendee = EventAttendee::firstOrCreate(
            ['event_id' => $this->event_id, 'user_id' => $user->id],
            ['status' => 'registered', 'approval_status' => 'pending']
        );

        $this->userId = $user->id;
        $this->attendeeId = $attendee->id;
        $this->step = 4;
    }

    public function selectPaymentMethod(string $method): void
    {
        $this->paymentMethod = $method;
        $this->cashReceiptRequested = false;
        $this->depositorName = '';
        $this->cashReceiptNumber = '';
    }

    private function generateMemberCode(): string
    {
        $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

        do {
            $code = '';
            for ($i = 0; $i < 8; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (\App\Models\User::where('member_code', $code)->exists());

        return $code;
    }

    public function submitPayment(): void
    {
        $rules = [
            'paymentMethod' => 'required|in:kakaopay,naverpay,tosspay,bank_transfer',
        ];

        if ($this->paymentMethod === 'bank_transfer') {
            $rules['depositorName'] = 'required|string|max:50';
            if ($this->cashReceiptRequested) {
                $rules['cashReceiptType'] = 'required|in:personal,business';
                $rules['cashReceiptNumber'] = 'required|string|max:30';
            }
        }

        $this->validate($rules, [
            'paymentMethod.required' => '결제 수단을 선택해주세요.',
            'depositorName.required' => '입금자명을 입력해주세요.',
            'cashReceiptNumber.required' => '현금영수증 발급 번호를 입력해주세요.',
        ]);

        Payment::create([
            'event_attendee_id' => $this->attendeeId,
            'amount' => $this->selectedEventModel?->price ?? 0,
            'currency' => 'KRW',
            'method' => $this->paymentMethod,
            'status' => 'pending',
            'depositor_name' => $this->depositorName ?: null,
            'cash_receipt_requested' => $this->cashReceiptRequested,
            'cash_receipt_type' => $this->cashReceiptRequested ? $this->cashReceiptType : null,
            'cash_receipt_number' => $this->cashReceiptRequested ? $this->cashReceiptNumber : null,
        ]);

        // 로그인(세션 재발급)은 더 이상 버튼을 누를 일이 없는 이 시점에 마지막으로 처리해요.
        // 이렇게 하면 세션이 바뀌어도 그 뒤에 눌릴 버튼이 없어서 "페이지 만료" 문제가 안 생겨요.
        if ($this->userId) {
            $user = \App\Models\User::find($this->userId);

            if ($user && ! $user->member_code) {
                $user->update(['member_code' => $this->generateMemberCode()]);
            }

            // TODO: 카카오 알림톡 API 연동 전까지는 로그로 대체
            Log::info("[카톡 발송 예정] {$user->name}({$user->phone}) 회원코드 안내: {$user->member_code}");

            Auth::login($user, remember: true);
            $this->memberCode = $user->member_code;
        }

        $this->step = 5;
    }
}; ?>

<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/themes/dark.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>

    <style>
        .apply-shell {
            position: relative;
            left: 50%;
            transform: translateX(-50%);
            width: 660px;
            max-width: 92vw;
        }

        .apply-glow {
            position: absolute;
            top: -140px;
            left: 50%;
            width: 700px;
            height: 700px;
            transform: translateX(-50%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            background: conic-gradient(from 90deg, var(--spark-orange), var(--spark-pink), var(--spark-violet), var(--spark-blue), var(--spark-orange));
            filter: blur(100px);
            opacity: 0.28;
            animation: apply-spin 30s linear infinite;
        }

        @keyframes apply-spin {
            to {
                transform: translateX(-50%) rotate(360deg);
            }
        }

        .apply-tracker {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            gap: 0;
            margin-bottom: 44px;
        }

        .apply-track-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
            max-width: 130px;
        }

        .apply-track-step:not(:last-child)::after {
            content: "";
            position: absolute;
            top: 18px;
            left: calc(50% + 26px);
            width: calc(100% - 20px);
            height: 2px;
            background: var(--line);
            z-index: 0;
        }

        .apply-track-step.done:not(:last-child)::after {
            background: linear-gradient(90deg, var(--spark-orange), var(--spark-pink));
        }

        .apply-track-dot {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 13px;
            color: var(--text-lo);
            background: var(--card);
            border: 1.5px solid var(--line);
            position: relative;
            z-index: 1;
            transition: all .25s;
        }

        .apply-track-step.active .apply-track-dot {
            background: linear-gradient(95deg, var(--spark-orange), var(--spark-pink));
            color: var(--void-1);
            border-color: transparent;
            box-shadow: 0 0 0 5px rgba(255, 62, 127, .15);
        }

        .apply-track-step.done .apply-track-dot {
            background: linear-gradient(95deg, var(--spark-orange), var(--spark-pink));
            color: var(--void-1);
            border-color: transparent;
        }

        .apply-track-label {
            font-size: 11.5px;
            color: var(--text-lo);
            margin-top: 8px;
            font-weight: 600;
            white-space: nowrap;
        }

        .apply-track-step.active .apply-track-label {
            color: var(--text-hi);
        }

        .apply-card {
            position: relative;
            z-index: 1;
            border: 1px solid var(--line);
            background: var(--card);
            border-radius: 28px;
            padding: 40px 36px;
            color: var(--text-hi);
        }

        @media (max-width:640px) {
            .apply-card {
                padding: 28px 20px;
                border-radius: 22px;
            }
        }

        .apply-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(246, 242, 251, 0.04);
            font-size: 11.5px;
            letter-spacing: 0.14em;
            color: var(--text-mid);
            font-weight: 700;
            margin-bottom: 18px;
            text-transform: uppercase;
            width: fit-content;
        }

        .apply-eyebrow .dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--spark-pink);
            box-shadow: 0 0 8px 2px var(--spark-pink);
        }

        .apply-h1 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(24px, 4vw, 30px);
            letter-spacing: -0.01em;
            margin: 0 0 10px;
            line-height: 1.25;
            color: var(--text-hi);
        }

        .apply-sub {
            color: var(--text-mid);
            font-size: 14.5px;
            line-height: 1.6;
            margin: 0 0 28px;
        }

        .cat-tab-bar {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .cat-tab-btn {
            font-size: 13px;
            font-weight: 700;
            padding: 9px 18px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: transparent;
            color: var(--text-mid);
            cursor: pointer;
            font-family: var(--font-body);
            transition: all .15s;
        }

        .cat-tab-btn:hover {
            color: var(--text-hi);
            border-color: rgba(246, 242, 251, .25);
        }

        .cat-tab-btn.active {
            background: linear-gradient(95deg, var(--spark-orange), var(--spark-pink));
            color: var(--void-1);
            border-color: transparent;
        }

        .event-card-v2 {
            display: flex;
            align-items: center;
            gap: 16px;
            width: 100%;
            text-align: left;
            border: 1px solid var(--line);
            background: rgba(246, 242, 251, .03);
            border-radius: 18px;
            padding: 16px 18px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all .2s;
            font-family: var(--font-body);
            color: var(--text-hi);
        }

        .event-card-v2:hover {
            border-color: rgba(246, 242, 251, .28);
            background: rgba(246, 242, 251, .06);
            transform: translateY(-1px);
        }

        .event-cat-badge {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .event-cat-badge svg {
            width: 26px;
            height: 26px;
            stroke: var(--void-1);
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .event-cat-badge.cat-party {
            background: linear-gradient(135deg, var(--spark-orange), var(--spark-pink));
        }

        .event-cat-badge.cat-running {
            background: linear-gradient(135deg, var(--spark-blue), var(--spark-violet));
        }

        .event-cat-badge.cat-yoga {
            background: linear-gradient(135deg, var(--spark-pink), var(--spark-violet));
        }

        .event-cat-badge.cat-default {
            background: linear-gradient(135deg, var(--spark-orange), var(--spark-violet));
        }

        .event-card-v2 .ev-main {
            flex: 1;
            min-width: 0;
        }

        .event-card-v2 .ev-cat {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 2px;
            color: var(--text-hi);
        }

        .event-card-v2 .ev-meta {
            font-size: 12.5px;
            color: var(--text-mid);
        }

        .event-card-v2 .ev-arrow {
            color: var(--text-lo);
            font-size: 20px;
            flex-shrink: 0;
            transition: transform .2s;
        }

        .event-card-v2:hover .ev-arrow {
            transform: translateX(3px);
            color: var(--spark-pink);
        }

        .section-label {
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--spark-pink);
            margin: 26px 0 14px;
        }

        .section-label:first-child {
            margin-top: 0;
        }

        .req-badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 800;
            color: var(--spark-pink);
            background: rgba(255, 62, 127, .14);
            border: 1px solid rgba(255, 62, 127, .3);
            padding: 2px 8px;
            border-radius: 999px;
            margin-left: 8px;
            letter-spacing: .02em;
            vertical-align: middle;
        }

        .photo-drop {
            display: block;
            border: 1.5px dashed var(--line);
            border-radius: 16px;
            padding: 22px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            background: rgba(246, 242, 251, .02);
            position: relative;
        }

        .photo-drop:hover {
            border-color: rgba(255, 138, 61, .5);
            background: rgba(255, 138, 61, .04);
        }

        .photo-drop-input {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .photo-drop-icon {
            font-size: 22px;
            margin-bottom: 6px;
        }

        .photo-drop-text {
            font-size: 13px;
            color: var(--text-mid);
            font-weight: 600;
        }

        .photo-drop-sub {
            font-size: 11.5px;
            color: var(--text-lo);
            margin-top: 3px;
        }

        .photo-thumbs {
            display: flex;
            gap: 8px;
            margin-top: 14px;
            flex-wrap: wrap;
        }

        .photo-thumb-wrap {
            position: relative;
        }

        .photo-remove-btn {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--void-1);
            border: 1px solid var(--line);
            color: var(--text-hi);
            font-size: 13px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            font-family: var(--font-body);
        }

        .photo-remove-btn:hover {
            background: var(--spark-pink);
            border-color: transparent;
        }

        .photo-thumb-fallback {
            width: 64px;
            height: 64px;
            border-radius: 10px;
            border: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: var(--text-lo);
            background: rgba(246, 242, 251, .03);
            font-weight: 700;
        }

        .photo-thumbs img {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid var(--line);
        }

        .photo-count {
            font-size: 11.5px;
            color: var(--spark-orange);
            margin-top: 8px;
            font-weight: 700;
        }

        .code-input {
            width: 100%;
            text-align: center;
            font-size: 28px;
            letter-spacing: 14px;
            font-weight: 700;
            font-family: var(--font-display);
            padding: 16px 10px 16px 24px;
            color: var(--text-hi);
        }

        .pay-method-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 8px;
        }

        .pay-method-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px 16px;
            border-radius: 14px;
            border: 1.5px solid var(--line);
            background: rgba(246, 242, 251, .03);
            color: var(--text-hi);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all .15s;
            font-family: var(--font-body);
        }

        .pay-method-btn:hover {
            border-color: rgba(246, 242, 251, .28);
        }

        .pay-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .pay-kakao .pay-dot {
            background: #FEE500;
        }

        .pay-naver .pay-dot {
            background: #03C75A;
        }

        .pay-toss .pay-dot {
            background: #0064FF;
        }

        .pay-bank .pay-dot {
            background: var(--text-lo);
        }

        .pay-method-btn.active {
            border-color: var(--spark-orange);
            background: rgba(255, 138, 61, .08);
        }

        .pay-method-btn.active .pay-dot {
            box-shadow: 0 0 0 3px rgba(255, 138, 61, .2);
        }

        .pg-note {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            border: 1px solid var(--line);
            background: rgba(246, 242, 251, .03);
            border-radius: 12px;
            padding: 14px 16px;
            margin: 14px 0;
        }

        .pg-note-icon {
            font-size: 16px;
            flex-shrink: 0;
        }

        .pg-note-text {
            font-size: 12.5px;
            color: var(--text-mid);
            line-height: 1.55;
        }

        .bank-info-card {
            border: 1px solid var(--line);
            background: rgba(246, 242, 251, .03);
            border-radius: 14px;
            padding: 16px 18px;
            margin: 14px 0 20px;
        }

        .bank-info-row {
            display: flex;
            justify-content: space-between;
            font-size: 13.5px;
            padding: 6px 0;
            color: var(--text-mid);
        }

        .bank-info-row strong {
            color: var(--text-hi);
            font-weight: 700;
        }

        .privacy-consent-box {
            margin: 18px 0 4px;
        }

        .privacy-consent-box .check-row {
            font-size: 12.5px;
            line-height: 1.6;
            color: var(--text-mid);
        }

        .code-card {
            border: 1px solid rgba(255, 138, 61, .3);
            background: rgba(255, 138, 61, .06);
            border-radius: 20px;
            padding: 22px 24px;
            margin: 20px 0;
            text-align: center;
        }

        .code-label {
            font-size: 12px;
            color: var(--text-mid);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .code-value {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 24px;
            letter-spacing: 5px;
            background: linear-gradient(95deg, var(--spark-orange), var(--spark-pink));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .code-hint {
            font-size: 11.5px;
            color: var(--text-lo);
            margin-top: 8px;
        }

        .success-badge {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            margin: 0 auto 22px;
            background: linear-gradient(135deg, var(--spark-orange), var(--spark-pink));
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pop-in .5s cubic-bezier(.34, 1.56, .64, 1);
        }

        @keyframes pop-in {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-note {
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: left;
            border: 1px solid var(--line);
            background: rgba(246, 242, 251, .03);
            border-radius: 14px;
            padding: 16px 18px;
            margin-top: 24px;
        }

        .success-note-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .success-note-text {
            font-size: 13px;
            color: var(--text-mid);
            line-height: 1.55;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12.5px;
            color: var(--text-lo);
            margin-bottom: 16px;
            cursor: pointer;
            background: none;
            border: none;
            font-family: var(--font-body);
        }

        .back-link:hover {
            color: var(--text-hi);
        }

        .gender-radio-group {
            display: flex;
            gap: 8px;
        }

        .gender-radio {
            position: relative;
            flex: 1;
            text-align: center;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid var(--line);
            background: rgba(246, 242, 251, .03);
            color: var(--text-mid);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s;
        }

        .gender-radio:hover {
            border-color: rgba(246, 242, 251, .25);
        }

        .gender-radio.active {
            background: linear-gradient(95deg, var(--spark-orange), var(--spark-pink));
            color: var(--void-1);
            border-color: transparent;
        }

        .gender-radio input {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* 셀렉트박스 다크 테마 + 커스텀 화살표 */
        .field select {
            color-scheme: dark;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='9' viewBox='0 0 14 9' fill='none'%3E%3Cpath d='M1 1L7 7L13 1' stroke='%23c7bcdb' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 42px;
        }

        .field select option {
            background-color: #1c1130;
            color: #f6f2fb;
        }

        html {
            color-scheme: dark;
        }

        .field input[type=date] {
            color-scheme: dark;
        }

        .field input[readonly] {
            cursor: pointer;
            background: rgba(246, 242, 251, .03);
        }

        /* Flatpickr 캘린더 다크 테마 브랜드 컬러 보정 */
        .flatpickr-calendar {
            background: var(--void-2) !important;
            border: 1px solid var(--line) !important;
            border-radius: 14px !important;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, .6) !important;
        }

        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background: linear-gradient(95deg, var(--spark-orange), var(--spark-pink)) !important;
            border-color: transparent !important;
            color: var(--void-1) !important;
        }

        .flatpickr-day:hover {
            background: rgba(255, 138, 61, .18) !important;
        }

        .flatpickr-months .flatpickr-month,
        .flatpickr-current-month,
        .flatpickr-weekday {
            color: var(--text-hi) !important;
            fill: var(--text-hi) !important;
        }

        .flatpickr-day {
            color: var(--text-mid);
        }

        .flatpickr-day.flatpickr-disabled {
            color: var(--text-lo) !important;
        }

        .numInputWrapper span.arrowUp:after {
            border-bottom-color: var(--text-mid) !important;
        }

        .numInputWrapper span.arrowDown:after {
            border-top-color: var(--text-mid) !important;
        }
    </style>

    <div class="apply-shell">
        <div class="apply-glow" aria-hidden="true"></div>

        <div class="apply-tracker">
            <div class="apply-track-step {{ $step > 1 ? 'done' : ($step === 1 ? 'active' : '') }}">
                <div class="apply-track-dot">{{ $step > 1 ? '✓' : '1' }}</div>
                <div class="apply-track-label">모임 선택</div>
            </div>
            <div class="apply-track-step {{ $step > 2 ? 'done' : ($step === 2 ? 'active' : '') }}">
                <div class="apply-track-dot">{{ $step > 2 ? '✓' : '2' }}</div>
                <div class="apply-track-label">정보 입력</div>
            </div>
            <div class="apply-track-step {{ $step > 3 ? 'done' : ($step === 3 ? 'active' : '') }}">
                <div class="apply-track-dot">{{ $step > 3 ? '✓' : '3' }}</div>
                <div class="apply-track-label">인증</div>
            </div>
            <div class="apply-track-step {{ $step > 4 ? 'done' : ($step === 4 ? 'active' : '') }}">
                <div class="apply-track-dot">{{ $step > 4 ? '✓' : '4' }}</div>
                <div class="apply-track-label">결제</div>
            </div>
            <div class="apply-track-step {{ $step === 5 ? 'active' : '' }}">
                <div class="apply-track-dot">5</div>
                <div class="apply-track-label">완료</div>
            </div>
        </div>

        <div class="apply-card">

            @if ($step === 1)
            <div wire:key="step-1">
                <div class="apply-eyebrow"><span class="dot"></span>Join Us</div>
                <h1 class="apply-h1">함께할 순간을<br>골라주세요</h1>
                <p class="apply-sub">강남점 · 홍대점, 매주 새로운 모임이 열려요.</p>

                <div class="cat-tab-bar">
                    <button type="button" wire:click="filterCategory(null)" class="cat-tab-btn {{ $categoryFilter === null ? 'active' : '' }}">전체</button>
                    @foreach ($this->categories as $cat)
                    <button type="button" wire:click="filterCategory({{ $cat->id }})" class="cat-tab-btn {{ $categoryFilter === $cat->id ? 'active' : '' }}">{{ $cat->name }}</button>
                    @endforeach
                </div>

                @forelse ($this->events as $event)
                <button wire:click="selectEvent({{ $event->id }})" wire:key="event-{{ $event->id }}" class="event-card-v2">
                    @php $catSlug = $event->category?->slug ?? 'default'; @endphp
                    <div class="event-cat-badge cat-{{ $catSlug }}">
                        @if ($catSlug === 'party')
                        <svg viewBox="0 0 24 24">
                            <path d="M7 3h10l-1.2 6a4 4 0 01-7.6 0L7 3z" />
                            <path d="M12 13v6" />
                            <path d="M9 20h6" />
                        </svg>
                        @elseif ($catSlug === 'running')
                        <svg viewBox="0 0 24 24">
                            <path d="M5 6l5 6-5 6" />
                            <path d="M13 6l5 6-5 6" />
                        </svg>
                        @elseif ($catSlug === 'yoga')
                        <svg viewBox="0 0 24 24">
                            <path d="M12 20c-4.5 0-7-3-7-6.5 2.3 0 4.6 1.1 7 3.3 2.4-2.2 4.7-3.3 7-3.3 0 3.5-2.5 6.5-7 6.5z" />
                            <path d="M12 13.5V4" />
                            <path d="M12 8.5c-2.2-2-2.2-4.3 0-6.3 2.2 2 2.2 4.3 0 6.3z" />
                        </svg>
                        @else
                        <svg viewBox="0 0 24 24">
                            <rect x="4" y="5" width="16" height="16" rx="2" />
                            <path d="M4 10h16" />
                            <path d="M8 3v4M16 3v4" />
                        </svg>
                        @endif
                    </div>
                    <div class="ev-main">
                        <div class="ev-cat">{{ $event->category?->name }} · {{ $event->location?->name }}</div>
                        <div class="ev-meta">{{ \Carbon\Carbon::parse($event->event_date)->format('m월 d일 (D)') }} · {{ $event->start_time }} · {{ $event->price > 0 ? number_format($event->price).'원' : '무료' }}</div>
                    </div>
                    <div class="ev-arrow">→</div>
                </button>
                @empty
                <div class="empty">현재 신청 가능한 모임이 없어요.</div>
                @endforelse
            </div>
            @endif

            @if ($step === 2)
            <div wire:key="step-2">
                <button type="button" wire:click="backTo(1)" class="back-link">← 모임 다시 선택</button>
                <div class="apply-eyebrow"><span class="dot"></span>Profile</div>
                <h1 class="apply-h1">참가자 정보를<br>입력해주세요</h1>
                <p class="apply-sub">승인 및 안내를 위해 정확하게 작성해주세요.</p>

                <div class="section-label">기본 정보</div>

                <div class="field">
                    <label>이름<span class="req-badge">필수</span></label>
                    <input type="text" wire:model="name">
                    @error('name') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-row-2">
                    <div class="field" x-data x-init="
                            (function initPicker() {
                                if (typeof flatpickr === 'undefined') {
                                    setTimeout(initPicker, 50);
                                    return;
                                }
                                flatpickr($refs.birthInput, { dateFormat: 'Y-m-d', maxDate: 'today' });
                            })();
                        ">
                        <label>생년월일<span class="req-badge">필수</span></label>
                        <input type="text" wire:model="birth_date" x-ref="birthInput" readonly placeholder="날짜를 선택해주세요">
                        @error('birth_date') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label>성별<span class="req-badge">필수</span></label>
                        <div class="gender-radio-group">
                            <label class="gender-radio {{ $gender === 'male' ? 'active' : '' }}">
                                <input type="radio" wire:model.live="gender" value="male">
                                남성
                            </label>
                            <label class="gender-radio {{ $gender === 'female' ? 'active' : '' }}">
                                <input type="radio" wire:model.live="gender" value="female">
                                여성
                            </label>
                        </div>
                        @error('gender') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="field">
                    <label>전화번호<span class="req-badge">필수</span></label>
                    <input type="text" wire:model="phone" placeholder="01012345678">
                    @error('phone') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="section-label">사진 (최대 4장)<span class="req-badge">필수</span></div>

                <label class="photo-drop">
                    <input type="file" wire:model="photos" multiple accept="image/*" class="photo-drop-input">
                    <div class="photo-drop-icon">📷</div>
                    <div class="photo-drop-text">눌러서 사진 선택</div>
                    <div class="photo-drop-sub">밝고 자연스러운 사진일수록 좋아요</div>
                </label>
                @error('photos') <span class="field-error">{{ $message }}</span> @enderror
                @error('photos.*') <span class="field-error">{{ $message }}</span> @enderror

                @if (count($photos))
                <div class="photo-thumbs">
                    @foreach ($photos as $index => $p)
                    @php
                    $ext = strtolower($p->getClientOriginalExtension());
                    $previewable = in_array($ext, ['png','jpg','jpeg','gif','bmp','webp']);
                    @endphp
                    <div class="photo-thumb-wrap" wire:key="photo-{{ $index }}">
                        @if ($previewable)
                        <img src="{{ $p->temporaryUrl() }}">
                        @else
                        <div class="photo-thumb-fallback">.{{ $ext ?: '?' }}</div>
                        @endif
                        <button type="button" wire:click="removePhoto({{ $index }})" class="photo-remove-btn" aria-label="사진 삭제">×</button>
                    </div>
                    @endforeach
                </div>
                <div class="photo-count">{{ count($photos) }}/4장 선택됨</div>
                @endif

                <div class="section-label">소개</div>

                <div class="field">
                    <label>간략한 소개글<span class="req-badge">필수</span></label>
                    <textarea wire:model="bio" rows="3" placeholder="어떤 사람인지 편하게 소개해주세요"></textarea>
                    @error('bio') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-row-2">
                    <div class="field">
                        <label>직업</label>
                        <input type="text" wire:model="job">
                    </div>
                    <div class="field">
                        <label>인스타그램</label>
                        <input type="text" wire:model="instagram_handle" placeholder="@handle">
                    </div>
                </div>

                <div class="field">
                    <label>취미 및 요즘 관심사</label>
                    <textarea wire:model="hobbies_interests" rows="3"></textarea>
                </div>

                <div class="privacy-consent-box">
                    <label class="check-row" style="align-items:flex-start;">
                        <input type="checkbox" wire:model="privacyConsent" style="margin-top:3px;">
                        <span>개인정보 수집 및 이용에 동의합니다. (모임 종료 후 2주 내 파기)
                            <a href="/privacy" target="_blank" style="color:var(--spark-orange);text-decoration:underline;">자세히 보기</a>
                        </span>
                    </label>
                    @error('privacyConsent') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <button wire:click="submitProfile" class="btn btn-primary btn-block" style="margin-top:8px;padding:15px;font-size:15px;">
                    인증번호 받고 신청하기
                </button>
            </div>
            @endif

            @if ($step === 3)
            <div wire:key="step-3" style="text-align:center;">
                <button type="button" wire:click="backTo(2)" class="back-link">← 정보 다시 입력</button>
                <div class="apply-eyebrow" style="margin-left:auto;margin-right:auto;"><span class="dot"></span>Verify</div>
                <h1 class="apply-h1">전화번호 인증</h1>
                <p class="apply-sub">{{ $phone }}로 발송된 인증번호 6자리를 입력해주세요.</p>

                <input type="text" wire:model="code" maxlength="6" class="code-input" placeholder="------">
                @error('code') <div class="field-error" style="margin-top:8px;">{{ $message }}</div> @enderror

                <button wire:click="verifyAndSubmit" class="btn btn-primary btn-block" style="margin-top:22px;padding:15px;font-size:15px;">
                    확인하고 신청 완료
                </button>
            </div>
            @endif

            @if ($step === 4)
            <div wire:key="step-4">
                <button type="button" wire:click="backTo(2)" class="back-link">← 정보 다시 입력</button>
                <div class="apply-eyebrow"><span class="dot"></span>Payment</div>
                <h1 class="apply-h1">참가비를<br>결제해주세요</h1>
                <p class="apply-sub">
                    @if ($this->selectedEventModel && $this->selectedEventModel->price > 0)
                    참가비 <strong style="color:var(--text-hi);">{{ number_format($this->selectedEventModel->price) }}원</strong>
                    @else
                    무료 참가 회차예요.
                    @endif
                </p>

                <div class="pay-method-grid">
                    <button type="button" wire:click="selectPaymentMethod('kakaopay')" class="pay-method-btn pay-kakao {{ $paymentMethod === 'kakaopay' ? 'active' : '' }}">
                        <span class="pay-dot"></span>카카오페이
                    </button>
                    <button type="button" wire:click="selectPaymentMethod('naverpay')" class="pay-method-btn pay-naver {{ $paymentMethod === 'naverpay' ? 'active' : '' }}">
                        <span class="pay-dot"></span>네이버페이
                    </button>
                    <button type="button" wire:click="selectPaymentMethod('tosspay')" class="pay-method-btn pay-toss {{ $paymentMethod === 'tosspay' ? 'active' : '' }}">
                        <span class="pay-dot"></span>토스페이
                    </button>
                    <button type="button" wire:click="selectPaymentMethod('bank_transfer')" class="pay-method-btn pay-bank {{ $paymentMethod === 'bank_transfer' ? 'active' : '' }}">
                        <span class="pay-dot"></span>무통장입금
                    </button>
                </div>
                @error('paymentMethod') <span class="field-error">{{ $message }}</span> @enderror

                @if ($paymentMethod && $paymentMethod !== 'bank_transfer')
                <div class="pg-note">
                    <div class="pg-note-icon">🔧</div>
                    <div class="pg-note-text">결제 연동 준비 중이에요. 지금은 신청만 먼저 접수되고, 결제 안내는 카카오톡으로 별도로 드릴게요.</div>
                </div>
                @endif

                @if ($paymentMethod === 'bank_transfer')
                <div class="bank-info-card">
                    <div class="bank-info-row"><span>은행</span><strong>{{ $this->bankInfo['name'] ?: '계좌 정보 준비중' }}</strong></div>
                    <div class="bank-info-row"><span>계좌번호</span><strong>{{ $this->bankInfo['number'] ?: '-' }}</strong></div>
                    <div class="bank-info-row"><span>예금주</span><strong>{{ $this->bankInfo['holder'] ?: '-' }}</strong></div>
                </div>

                <div class="field">
                    <label>입금자명<span class="req-badge">필수</span></label>
                    <input type="text" wire:model="depositorName" placeholder="입금하실 분 성함">
                    @error('depositorName') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <label class="check-row" style="padding:4px 0 12px;">
                    <input type="checkbox" wire:model.live="cashReceiptRequested"> 현금영수증 신청
                </label>

                @if ($cashReceiptRequested)
                <div class="form-row-2">
                    <div class="field">
                        <label>용도</label>
                        <select wire:model="cashReceiptType">
                            <option value="personal">개인 소득공제용</option>
                            <option value="business">사업자 지출증빙용</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>{{ $cashReceiptType === 'business' ? '사업자등록번호' : '휴대폰번호' }}<span class="req-badge">필수</span></label>
                        <input type="text" wire:model="cashReceiptNumber" placeholder="{{ $cashReceiptType === 'business' ? '000-00-00000' : '01012345678' }}">
                        @error('cashReceiptNumber') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif
                @endif

                <button wire:click="submitPayment" class="btn btn-primary btn-block" style="margin-top:16px;padding:15px;font-size:15px;">
                    신청 완료하기
                </button>
            </div>
            @endif

            @if ($step === 5)
            <div wire:key="step-5" style="text-align:center;">
                <div class="success-badge">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#0a0712" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5" />
                    </svg>
                </div>
                <h1 class="apply-h1">신청이 완료됐어요!</h1>
                <p class="apply-sub">관리자 승인 후 카카오톡으로 만남 장소와 드레스코드를 안내드릴게요.</p>

                @if ($memberCode)
                <div class="code-card">
                    <div class="code-label">내 회원코드</div>
                    <div class="code-value">{{ $memberCode }}</div>
                    <div class="code-hint">카카오톡으로도 보내드렸어요. 다음부터는 이 코드로 바로 로그인할 수 있어요.</div>
                </div>
                @endif

                <div class="success-note">
                    <div class="success-note-icon">💬</div>
                    <div class="success-note-text">승인 여부는 보통 1~2일 내에 카카오톡으로 안내돼요. 알림을 꼭 확인해주세요.</div>
                </div>

                <a href="/mypage" class="btn btn-outline btn-block" style="margin-top:16px;padding:15px;font-size:15px;">마이페이지로 이동</a>
            </div>
            @endif

        </div>
    </div>
</div>