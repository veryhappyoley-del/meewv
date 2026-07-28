<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::auth', ['title' => '마이페이지'])] class extends Component
{
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}; ?>

<div>
    <style>
        .mypage-shell{position:relative;left:50%;transform:translateX(-50%);width:520px;max-width:92vw;}
        .mypage-card{border:1px solid var(--line);background:var(--card);border-radius:28px;padding:32px 30px;margin-bottom:16px;}
        .mypage-profile-top{display:flex;gap:16px;align-items:center;margin-bottom:18px;}
        .mypage-photo{width:72px;height:72px;border-radius:18px;object-fit:cover;border:1px solid var(--line);flex-shrink:0;}
        .mypage-photo-placeholder{width:72px;height:72px;border-radius:18px;border:1px dashed var(--line);flex-shrink:0;
            display:flex;align-items:center;justify-content:center;color:var(--text-lo);font-size:11px;}
        .mypage-name{font-family:var(--font-display);font-weight:800;font-size:20px;}
        .mypage-sub{font-size:13px;color:var(--text-mid);margin-top:4px;}
        .mypage-tags{display:flex;gap:6px;flex-wrap:wrap;margin-top:10px;}
        .mypage-tag{font-size:11.5px;padding:3px 9px;border-radius:999px;background:rgba(246,242,251,.06);color:var(--text-mid);border:1px solid var(--line);}
        .mypage-bio{font-size:13.5px;color:var(--text-hi);background:rgba(246,242,251,.03);border-radius:10px;padding:12px 14px;margin-top:14px;line-height:1.6;}

        .code-card{border:1px solid rgba(255,138,61,.3);background:rgba(255,138,61,.06);border-radius:20px;padding:22px 24px;margin-bottom:16px;text-align:center;}
        .code-label{font-size:12px;color:var(--text-mid);margin-bottom:8px;font-weight:600;}
        .code-value{font-family:var(--font-display);font-weight:800;font-size:26px;letter-spacing:6px;
            background:linear-gradient(95deg,var(--spark-orange),var(--spark-pink));-webkit-background-clip:text;background-clip:text;color:transparent;}
        .code-hint{font-size:11.5px;color:var(--text-lo);margin-top:8px;}

        .mypage-links{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
        .mypage-link-btn{display:flex;flex-direction:column;gap:4px;padding:18px;border-radius:16px;border:1px solid var(--line);
            background:rgba(246,242,251,.03);color:var(--text-hi);text-align:left;transition:all .15s;}
        .mypage-link-btn:hover{border-color:rgba(246,242,251,.28);background:rgba(246,242,251,.06);}
        .mypage-link-btn .lk-title{font-weight:700;font-size:14.5px;}
        .mypage-link-btn .lk-sub{font-size:11.5px;color:var(--text-lo);}

        .logout-btn{width:100%;margin-top:6px;background:transparent;border:1px solid var(--line);color:var(--text-lo);
            padding:12px;border-radius:12px;font-size:13px;cursor:pointer;font-family:var(--font-body);}
        .logout-btn:hover{color:var(--text-hi);border-color:rgba(246,242,251,.25);}
    </style>

    <div class="mypage-shell">
        <div class="mypage-card">
            <div class="mypage-profile-top">
                @php $photos = auth()->user()->photos ?? []; @endphp
                @if (count($photos))
                    <img src="{{ asset('storage/' . $photos[0]) }}" class="mypage-photo">
                @else
                    <div class="mypage-photo-placeholder">사진<br>없음</div>
                @endif
                <div>
                    <div class="mypage-name">{{ auth()->user()->name ?: '이름 없음' }}</div>
                    <div class="mypage-sub">
                        {{ auth()->user()->phone }}
                        @if (auth()->user()->birth_date)
                            · {{ \Carbon\Carbon::parse(auth()->user()->birth_date)->age }}세
                        @endif
                    </div>
                </div>
            </div>

            <div class="mypage-tags">
                @if (auth()->user()->job)<span class="mypage-tag">{{ auth()->user()->job }}</span>@endif
                @if (auth()->user()->instagram_handle)<span class="mypage-tag">{{ auth()->user()->instagram_handle }}</span>@endif
                @if (auth()->user()->hobbies_interests)<span class="mypage-tag">{{ auth()->user()->hobbies_interests }}</span>@endif
            </div>

            @if (auth()->user()->bio)
                <div class="mypage-bio">{{ auth()->user()->bio }}</div>
            @endif
        </div>

        @if (auth()->user()->member_code)
            <div class="code-card">
                <div class="code-label">내 회원코드</div>
                <div class="code-value">{{ auth()->user()->member_code }}</div>
                <div class="code-hint">다른 기기에서 로그인할 때, 전화번호와 이 코드로 바로 들어올 수 있어요.</div>
            </div>
        @endif

        <div class="mypage-links">
            <a href="/signals/received" class="mypage-link-btn">
                <span class="lk-title">받은 시그널</span>
                <span class="lk-sub">나에게 온 시그널 확인</span>
            </a>
            <a href="/signals/matches" class="mypage-link-btn">
                <span class="lk-title">매칭 현황</span>
                <span class="lk-sub">연결된 인연 보기</span>
            </a>
            <a href="/party/attendees" class="mypage-link-btn">
                <span class="lk-title">오늘 참석자</span>
                <span class="lk-sub">시그널 보내기</span>
            </a>
            <a href="/apply" class="mypage-link-btn">
                <span class="lk-title">새 모임 신청</span>
                <span class="lk-sub">다른 회차 참가하기</span>
            </a>
        </div>

        <button wire:click="logout" class="logout-btn">로그아웃</button>
    </div>
</div>
