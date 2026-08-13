<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::marketing', [
    'title' => 'MEEWV YOGA · 요가 모임',
    'ogTitle' => 'MEEWV YOGA · 몸과 마음이 함께 쉬어가는 시간',
    'ogDescription' => '낯선 이들과 함께하는 요가 모임. 움직임 끝에 남는 건 잔잔한 대화와 편안함이에요.',
    'ogImage' => '/images/yoga/yoga-hero.jpg',
])] class extends Component
{
    //
}; ?>

<div class="yg-page">
    <style>
        :root {
            --yg-bg: #F6F4EE;
            --yg-card: #FFFFFF;
            --yg-deep: #EAE6D9;
            --yg-sage: #7C8B6F;
            --yg-sage-deep: #5E6E52;
            --yg-clay: #B98D6F;
            --yg-text: #2E2B24;
            --yg-text-mid: #71695A;
            --yg-line: rgba(46, 43, 36, 0.10);
        }

        .yg-page {
            background: var(--yg-bg);
            color: var(--yg-text);
            position: relative;
            z-index: 1;
        }

        /* 히어로 */
        .yg-hero {
            position: relative;
            height: 78vh;
            min-height: 520px;
            display: flex;
            align-items: center;
            background: linear-gradient(180deg, rgba(30, 28, 22, .45) 0%, rgba(30, 28, 22, .15) 40%, rgba(30, 28, 22, .55) 100%),
                url('/images/yoga/yoga-hero.jpg') center/cover no-repeat;
        }

        .yg-hero-inner{position:relative;z-index:1;max-width:900px;margin:-100px auto 0;padding:0 24px;text-align:center;width:100%;}
        .yg-hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, .25);
            font-size: 12px;
            letter-spacing: .16em;
            color: #fff;
            font-weight: 700;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .yg-hero h1 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(32px, 5vw, 52px);
            color: #fff;
            margin: 0 0 16px;
            line-height: 1.3;
        }

        .yg-hero p {
            font-size: 16px;
            color: rgba(255, 255, 255, .85);
            max-width: 480px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* 공통 섹션 */
        .yg-section {
            padding: 80px 24px;
            max-width: 1080px;
            margin: 0 auto;
        }

        .yg-kicker {
            font-size: 12.5px;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--yg-sage-deep);
            margin-bottom: 12px;
            text-align: center;
        }

        .yg-h2 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(24px, 3vw, 32px);
            text-align: center;
            margin: 0 0 16px;
            color: var(--yg-text);
        }

        .yg-sub {
            text-align: center;
            color: var(--yg-text-mid);
            max-width: 520px;
            margin: 0 auto 50px;
            font-size: 14.5px;
            line-height: 1.75;
        }

        /* 소개 문단 */
        .yg-intro {
            display: flex;
            align-items: center;
            gap: 56px;
        }

        .yg-intro-photo {
            flex: 1;
            border-radius: 26px;
            overflow: hidden;
            aspect-ratio: 4/5;
            min-width: 0;
            box-shadow: 0 24px 50px -20px rgba(46, 43, 36, .28);
        }

        .yg-intro-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .yg-intro-text {
            flex: 1;
            min-width: 0;
        }

        .yg-intro-text p {
            font-size: 15.5px;
            color: var(--yg-text-mid);
            line-height: 1.85;
            margin: 0 0 16px;
        }

        @media (max-width:820px) {
            .yg-intro {
                flex-direction: column;
                gap: 28px;
            }
        }

        .yg-teacher-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:32px;max-width:760px;margin:0 auto;}
        @media (max-width:680px){.yg-teacher-grid{grid-template-columns:1fr;max-width:380px;}}
        .yg-teacher-card{background:var(--yg-card);border-radius:26px;overflow:hidden;border:1px solid var(--yg-line);
            box-shadow:0 14px 34px -18px rgba(46,43,36,.18);}
        .yg-teacher-photo{aspect-ratio:4/5;overflow:hidden;}
        .yg-teacher-photo img{width:100%;height:100%;object-fit:cover;display:block;}
        .yg-teacher-body{padding:26px 28px 30px;}
        .yg-teacher-name{font-family:var(--font-display);font-weight:800;font-size:22px;margin-bottom:4px;}
        .yg-teacher-role{font-size:13px;color:var(--yg-sage-deep);font-weight:700;margin-bottom:14px;
            padding-bottom:14px;border-bottom:1px solid var(--yg-line);}
        .yg-teacher-desc{font-size:14px;color:var(--yg-text-mid);line-height:1.75;}
        .yg-teacher-creds{margin-top:14px;padding-top:14px;border-top:1px solid var(--yg-line);
            list-style:none;padding-left:0;}
        .yg-teacher-creds li{font-size:12px;color:var(--yg-text-mid);padding-left:16px;position:relative;margin-bottom:5px;}
        .yg-teacher-creds li::before{content:"✓";position:absolute;left:0;color:var(--yg-sage-deep);font-weight:800;}
        /* 프로그램 카드 */
        .yg-prog-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        @media (max-width:820px) {
            .yg-prog-grid {
                grid-template-columns: 1fr;
                max-width: 400px;
                margin: 0 auto;
            }
        }

        .yg-prog-card {
            position: relative;
            border-radius: 22px;
            overflow: hidden;
            aspect-ratio: 3/4;
            box-shadow: 0 16px 36px -18px rgba(46, 43, 36, .3);
        }

        .yg-prog-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .yg-prog-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(0deg, rgba(30, 28, 22, .85) 0%, rgba(30, 28, 22, .05) 55%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 24px 22px;
        }

        .yg-prog-level {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 800;
            color: #fff;
            background: rgba(255, 255, 255, .18);
            backdrop-filter: blur(4px);
            padding: 4px 11px;
            border-radius: 999px;
            margin-bottom: 10px;
            width: fit-content;
            letter-spacing: .04em;
        }

        .yg-prog-title {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 19px;
            color: #fff;
            margin-bottom: 6px;
        }

        .yg-prog-desc {
            font-size: 12.5px;
            color: rgba(255, 255, 255, .82);
            line-height: 1.6;
        }

        /* 진행 방식 스텝 */
        .yg-flow{display:flex;justify-content:center;gap:20px 0;max-width:900px;margin:0 auto;flex-wrap:wrap;}
        .yg-flow-step{flex:1 1 180px;max-width:210px;text-align:center;padding:0 14px;position:relative;}
        .yg-flow-num{width:44px;height:44px;border-radius:50%;background:var(--yg-card);border:1.5px solid var(--yg-sage);
            display:flex;align-items:center;justify-content:center;margin:0 auto 14px;
            font-family:var(--font-display);font-weight:800;color:var(--yg-sage-deep);font-size:15px;}
        .yg-flow-title{font-weight:700;font-size:15.5px;color:var(--yg-text);margin-bottom:6px;white-space:nowrap;}
        .yg-flow-desc{font-size:13.5px;color:var(--yg-text-mid);line-height:1.6;}

        /* CTA */
        .yg-cta {
            background: linear-gradient(135deg, var(--yg-sage), var(--yg-sage-deep));
            padding: 80px 24px;
            text-align: center;
            color: #fff;
        }

        .yg-cta h2 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(24px, 3.2vw, 32px);
            margin: 0 0 14px;
        }

        .yg-cta p {
            font-size: 15px;
            opacity: .9;
            margin: 0 0 28px;
        }

        .yg-cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 15px 32px;
            border-radius: 999px;
            background: #fff;
            color: var(--yg-sage-deep);
            font-weight: 800;
            font-size: 15px;
            box-shadow: 0 14px 30px -10px rgba(0, 0, 0, .3);
        }
    </style>

    {{-- 히어로 --}}
    <section class="yg-hero">
        <div class="yg-hero-inner">
            <div class="yg-hero-eyebrow">MEEWV YOGA</div>
            <h1>몸과 마음이<br>함께 쉬어가는 시간</h1>
            <p>낯선 이들과 함께하는 요가 모임.</p>
            <p>움직임 끝에 남는 건 잔잔한 대화와 편안함이에요.</p>
        </div>
    </section>

    {{-- 소개 --}}
    <section class="yg-section">
        <div class="yg-intro">
            <div class="yg-intro-photo">
                <img src="/images/yoga/yoga-4.jpg" alt="MEEWV 요가 소개">
            </div>
            <div class="yg-intro-text">
                <div class="yg-kicker" style="text-align:left;">About</div>
                <h2 class="yg-h2" style="text-align:left;">경쟁도, 부담도 없는<br>느슨한 연결</h2>
                <p>MEEWV 요가는 잘하는 게 목적이 아니에요. 낯선 사람들과 같은 공간에서 같은 호흡을 나누고, 수련이 끝난 뒤엔 자연스럽게 대화가 이어지는 자리예요.</p>
                <p>초보자도 부담 없이 참여할 수 있도록, 난이도는 항상 미리 안내해드려요. 매트 위에서는 각자의 속도로, 매트 밖에서는 함께의 온기로.</p>
            </div>
        </div>
    </section>

    {{-- 강사 소개 --}}
    <section class="yg-section" style="background:var(--yg-deep);border-radius:40px;">
        <div class="yg-kicker">Instructors</div>
        <h2 class="yg-h2">강사진 소개</h2>
        <p class="yg-sub">경험 많은 강사진이 처음 오신 분도 편안하게 따라올 수 있도록 이끌어드려요.</p>

        <div class="yg-teacher-grid">
            <div class="yg-teacher-card">
                <div class="yg-teacher-photo"><img src="/images/yoga/yoga-1.jpg" alt="강사 bora"></div>
                <div class="yg-teacher-body">
                    <div class="yg-teacher-name">보라</div>
                    <div class="yg-teacher-role">하타 · 빈야사 전문</div>
                    <div class="yg-teacher-desc">차분하고 정확한 디렉션으로 초보자도 안전하게 자세를 익힐 수 있도록 도와드려요.</div>
                    <ul class="yg-teacher-creds">
                        <li>RYT-200 요가 지도자 과정 수료</li>
                        <li>하타 요가 티처 트레이닝 이수</li>
                        <li>요가 지도경력 5년</li>
                    </ul>
                </div>
            </div>
            <div class="yg-teacher-card">
                <div class="yg-teacher-photo"><img src="/images/yoga/yoga-2.jpg" alt="강사 SJ"></div>
                <div class="yg-teacher-body">
                    <div class="yg-teacher-name">승진</div>
                    <div class="yg-teacher-role">플로우 · 릴렉스 전문</div>
                    <div class="yg-teacher-desc">호흡과 움직임의 흐름을 중시하는 수업으로, 몸의 긴장을 부드럽게 풀어드려요.</div>
                    <ul class="yg-teacher-creds">
                        <li>빈야사 플로우 지도자 과정 수료</li>
                        <li>명상 지도자 자격 이수</li>
                        <li>요가 지도경력 4년</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- 프로그램 소개 --}}
    <section class="yg-section">
        <div class="yg-kicker">Programs</div>
        <h2 class="yg-h2">프로그램 소개</h2>
        <p class="yg-sub">그날의 분위기와 참석자 수준에 맞춰 프로그램이 조정될 수 있어요.</p>

        <div class="yg-prog-grid">
            <div class="yg-prog-card">
                <img src="/images/yoga/yoga-5.jpg" alt="비기너 플로우">
                <div class="yg-prog-overlay">
                    <span class="yg-prog-level">입문 · LEVEL 1</span>
                    <div class="yg-prog-title">비기너 플로우</div>
                    <div class="yg-prog-desc">요가가 처음이어도 괜찮아요. 기본 호흡과 자세부터 천천히.</div>
                </div>
            </div>
            <div class="yg-prog-card">
                <img src="/images/yoga/yoga-6.jpg" alt="릴렉스 세션">
                <div class="yg-prog-overlay">
                    <span class="yg-prog-level">전체 · ALL LEVEL</span>
                    <div class="yg-prog-title">릴렉스 세션</div>
                    <div class="yg-prog-desc">가벼운 스트레칭과 명상으로 마무리하는 회복 중심 수업.</div>
                </div>
            </div>
            <div class="yg-prog-card">
                <img src="/images/yoga/yoga-7.jpg" alt="마인드풀 브레스">
                <div class="yg-prog-overlay">
                    <span class="yg-prog-level">전체 · ALL LEVEL</span>
                    <div class="yg-prog-title">마인드풀 브레스</div>
                    <div class="yg-prog-desc">호흡에 집중하며 하루의 긴장을 내려놓는 시간.</div>
                </div>
            </div>
        </div>
    </section>

    {{-- 진행 방식 --}}
    <section class="yg-section" style="padding-top:0;">
        <div class="yg-kicker">How it works</div>
        <h2 class="yg-h2">진행 방식</h2>
        <p class="yg-sub">부담 없이 참여할 수 있도록, 흐름은 항상 같아요.</p>

        <div class="yg-flow">
            <div class="yg-flow-step">
                <div class="yg-flow-num">1</div>
                <div class="yg-flow-title">입장 · 매트 배정</div>
                <div class="yg-flow-desc">도착하면 매트와 자리를 안내해드려요.</div>
            </div>
            <div class="yg-flow-step">
                <div class="yg-flow-num">2</div>
                <div class="yg-flow-title">가벼운 소개</div>
                <div class="yg-flow-desc">서로 짧게 인사 나누는 시간이 있어요.</div>
            </div>
            <div class="yg-flow-step">
                <div class="yg-flow-num">3</div>
                <div class="yg-flow-title">요가 수련</div>
                <div class="yg-flow-desc">강사와 함께 약 50분간 수련해요.</div>
            </div>
            <div class="yg-flow-step">
                <div class="yg-flow-num">4</div>
                <div class="yg-flow-title">티타임 · 대화</div>
                <div class="yg-flow-desc">차 한잔과 함께 자연스럽게 이어지는 대화.</div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="yg-cta">
        <h2>이번 주, 매트 하나 비워둘게요</h2>
        <p>낯선 얼굴들과 함께하는 조용한 시간, 지금 신청해보세요.</p>
        <a href="/apply" class="yg-cta-btn">요가 모임 신청하기 →</a>
    </section>
</div>