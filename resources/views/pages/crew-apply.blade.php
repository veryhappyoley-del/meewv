<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::marketing', ['title' => '크루제안 · MEEWV'])] class extends Component
{
}; ?>

<div>
    <style>
        :root{
            --peach-bg:#FFF3E9; --peach-deep:#FFE1C6; --salmon:#FF9770;
            --peach-orange:#FF7A3D; --peach-orange-deep:#E85D2E;
            --peach-text:#3A2418; --peach-text-mid:#8A6A56; --peach-line:rgba(58,36,24,0.10); --peach-card:#FFFFFF;
        }
        .ca-page{background:var(--peach-bg);color:var(--peach-text);position:relative;z-index:1;padding:80px 24px 100px;}
        .ca-head{max-width:640px;margin:0 auto 60px;text-align:center;}
        .ca-kicker{font-size:12.5px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:var(--peach-orange-deep);margin-bottom:12px;}
        .ca-head h1{font-family:var(--font-display);font-weight:800;font-size:clamp(26px,3.6vw,36px);margin:0 0 14px;}
        .ca-head p{color:var(--peach-text-mid);font-size:15px;line-height:1.7;}

        .ca-role{max-width:1000px;margin:0 auto 60px;display:flex;align-items:center;gap:50px;}
        .ca-role.reverse{flex-direction:row-reverse;}
        .ca-role-photo{flex:1;aspect-ratio:4/3;border-radius:24px;overflow:hidden;min-width:0;
            background:linear-gradient(135deg,var(--peach-deep),var(--salmon));
            box-shadow:0 20px 44px -18px rgba(58,36,24,.25);}
        .ca-role-photo img{width:100%;height:100%;object-fit:cover;display:block;}
        .ca-role-text{flex:1;min-width:0;}
        .ca-role-text h2{font-family:var(--font-display);font-weight:800;font-size:clamp(20px,2.4vw,26px);margin:0 0 14px;}
        .ca-role-text p{font-size:14.5px;color:var(--peach-text-mid);line-height:1.8;}
        @media (max-width:780px){.ca-role, .ca-role.reverse{flex-direction:column;gap:24px;}}

        .ca-cta{max-width:560px;margin:60px auto 0;text-align:center;padding:44px 30px;border-radius:28px;
            background:linear-gradient(135deg,var(--peach-orange),var(--salmon));color:#fff;}
        .ca-cta h3{font-family:var(--font-display);font-weight:800;font-size:20px;margin:0 0 10px;}
        .ca-cta p{font-size:13.5px;opacity:.92;margin:0 0 22px;}
        .ca-cta-btn{display:inline-flex;align-items:center;gap:8px;padding:13px 28px;border-radius:999px;
            background:#fff;color:var(--peach-orange-deep);font-weight:800;font-size:14.5px;}
    </style>

    <div class="ca-page">
        <div class="ca-head">
            <div class="ca-kicker">Join Us</div>
            <h1>새로운 경험, 새로운 사람들<br>MEEWV의 크루로 함께해요</h1>
            <p>지금 아래 역할로 크루를 모집하고 있어요. 관심 있으신 분은 편하게 문의해주세요.</p>
        </div>

        <div class="ca-role">
            <div class="ca-role-photo"><img src="/images/hero/6.png" alt="MC 진행자 모집"></div>
            <div class="ca-role-text">
                <h2>MC 진행자</h2>
                <p>어색한 첫 만남을 웃음으로 풀어내는 사람. 게임 하나로 테이블 전체의 분위기를 바꿀 수 있는, MEEWV의 얼굴이 되어주세요. 경험이 없어도 괜찮아요, 사람과 함께하는 걸 좋아한다면 충분해요.</p>
            </div>
        </div>

        <div class="ca-role reverse">
            <div class="ca-role-photo"><img src="/images/hero/7.png" alt="현장 스태프 모집"></div>
            <div class="ca-role-text">
                <h2>현장 스태프</h2>
                <p>접객의 최전선에서 참가자들과 호흡하며 파티를 함께 만들어갈 분을 찾아요. 체크인부터 마무리까지, 현장의 매끄러운 진행을 책임져주세요.</p>
            </div>
        </div>

        <div class="ca-role">
            <div class="ca-role-photo"><img src="/images/hero/8.png" alt="요가 러닝 강사 모집"></div>
            <div class="ca-role-text">
                <h2>요가 · 러닝 강사</h2>
                <p>몸을 움직이며 자연스럽게 가까워지는 자리를 이끌어주실 강사님을 찾고 있어요. 전문성은 물론, 참가자들과 편안하게 소통하는 능력도 중요해요.</p>
            </div>
        </div>

        <div class="ca-cta">
            <h3>지원 문의하기</h3>
            <p>간단한 자기소개와 함께 인스타그램 DM으로 편하게 연락주세요.</p>
            <a href="#" class="ca-cta-btn">Instagram DM 보내기 →</a>
        </div>
    </div>
</div>