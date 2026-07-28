<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::marketing', ['title' => '크루소개 · MEEWV'])] class extends Component
{
}; ?>

<div>
    <style>
        :root{
            --peach-bg:#FFF3E9; --peach-deep:#FFE1C6; --salmon:#FF9770;
            --peach-orange:#FF7A3D; --peach-orange-deep:#E85D2E;
            --peach-text:#3A2418; --peach-text-mid:#8A6A56; --peach-line:rgba(58,36,24,0.10); --peach-card:#FFFFFF;
        }
        .crew-page{background:var(--peach-bg);color:var(--peach-text);position:relative;z-index:1;padding:80px 24px 100px;}
        .crew-head{max-width:640px;margin:0 auto 60px;text-align:center;}
        .crew-kicker{font-size:12.5px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:var(--peach-orange-deep);margin-bottom:12px;}
        .crew-head h1{font-family:var(--font-display);font-weight:800;font-size:clamp(26px,3.6vw,36px);margin:0 0 14px;}
        .crew-head p{color:var(--peach-text-mid);font-size:15px;line-height:1.7;}

        .crew-section{max-width:1080px;margin:0 auto 80px;}
        .crew-section-title{font-family:var(--font-display);font-weight:800;font-size:20px;margin:0 0 24px;
            padding-bottom:12px;border-bottom:2px solid var(--peach-line);color:var(--peach-text);}
        .crew-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;}
        .crew-card{background:var(--peach-card);border:1px solid var(--peach-line);border-radius:20px;padding:24px 20px;text-align:center;
            box-shadow:0 8px 24px -14px rgba(58,36,24,.14);}
        .crew-photo{width:88px;height:88px;border-radius:50%;margin:0 auto 16px;object-fit:cover;background:linear-gradient(135deg,var(--peach-orange),var(--salmon));
            display:flex;align-items:center;justify-content:center;color:#fff;font-family:var(--font-display);font-weight:800;font-size:26px;}
        .crew-name{font-family:var(--font-display);font-weight:800;font-size:16.5px;margin-bottom:4px;}
        .crew-role{font-size:12px;color:var(--peach-orange-deep);font-weight:700;margin-bottom:10px;}
        .crew-bio{font-size:13px;color:var(--peach-text-mid);line-height:1.6;}
    </style>

    <div class="crew-page">
        <div class="crew-head">
            <div class="crew-kicker">Our Crew</div>
            <h1>MEEWV를 만드는 사람들</h1>
            <p>강사진부터 진행자, 현장 스태프까지 — MEEWV의 모든 순간은 이분들의 손끝에서 만들어져요.</p>
        </div>

        <div class="crew-section">
            <div class="crew-section-title">요가 · 러닝 강사진</div>
            <div class="crew-grid">
                <div class="crew-card">
                    <div class="crew-photo">지</div>
                    <div class="crew-name">김지훈</div>
                    <div class="crew-role">요가 강사</div>
                    <div class="crew-bio">5년차 요가 강사, 몸과 마음을 함께 풀어주는 클래스를 진행해요.</div>
                </div>
                <div class="crew-card">
                    <div class="crew-photo">서</div>
                    <div class="crew-name">이서연</div>
                    <div class="crew-role">러닝 크루장</div>
                    <div class="crew-bio">누구나 부담 없이 완주할 수 있는 페이스로 함께 달려요.</div>
                </div>
            </div>
        </div>

        <div class="crew-section">
            <div class="crew-section-title">MC 진행자</div>
            <div class="crew-grid">
                <div class="crew-card">
                    <div class="crew-photo">도</div>
                    <div class="crew-name">박도윤</div>
                    <div class="crew-role">메인 MC</div>
                    <div class="crew-bio">어색한 자리도 웃음으로 풀어내는 아이스브레이킹 전문가예요.</div>
                </div>
                <div class="crew-card">
                    <div class="crew-photo">하</div>
                    <div class="crew-name">최하은</div>
                    <div class="crew-role">서브 MC</div>
                    <div class="crew-bio">참가자 한 명 한 명을 세심하게 챙기는 진행을 해요.</div>
                </div>
            </div>
        </div>

        <div class="crew-section">
            <div class="crew-section-title">현장 스태프</div>
            <div class="crew-grid">
                <div class="crew-card">
                    <div class="crew-photo">우</div>
                    <div class="crew-name">정우진</div>
                    <div class="crew-role">현장 운영</div>
                    <div class="crew-bio">체크인부터 마무리까지, 매끄러운 진행을 책임져요.</div>
                </div>
                <div class="crew-card">
                    <div class="crew-photo">채</div>
                    <div class="crew-name">한채원</div>
                    <div class="crew-role">현장 운영</div>
                    <div class="crew-bio">참가자들이 편안하게 즐길 수 있도록 세심하게 챙겨요.</div>
                </div>
            </div>
        </div>
    </div>
</div>