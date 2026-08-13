<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::marketing', ['title' => 'MEEWV DIVING · 프리다이빙 모임'])] class extends Component
{
    //
}; ?>

<div class="dv-page">
    <style>
        :root{
            --dv-bg:#0B1B2E; --dv-bg-2:#0F2540; --dv-card:#122B47; --dv-line:rgba(148,180,214,0.16);
            --dv-cyan:#4FC3E8; --dv-cyan-deep:#2BA8D1; --dv-text:#EAF3FB; --dv-text-mid:#9BB6CE;
        }
        .dv-page{background:var(--dv-bg);color:var(--dv-text);position:relative;z-index:1;}

        .dv-hero{position:relative;height:82vh;min-height:560px;display:flex;align-items:center;
            background:linear-gradient(180deg, rgba(4,14,26,.55) 0%, rgba(4,14,26,.25) 45%, rgba(4,14,26,.75) 100%),
                url('/images/diving/diving-hero.webp') center/cover no-repeat;}
        .dv-hero-inner{position:relative;z-index:1;max-width:900px;margin:-90px auto 0;padding:0 24px;text-align:center;width:100%;}
        .dv-hero-eyebrow{display:inline-flex;align-items:center;gap:8px;padding:7px 16px;border-radius:999px;
            background:rgba(79,195,232,.14);backdrop-filter:blur(6px);border:1px solid rgba(79,195,232,.35);
            font-size:12px;letter-spacing:.16em;color:var(--dv-cyan);font-weight:700;margin-bottom:20px;text-transform:uppercase;}
        .dv-hero h1{font-family:var(--font-display);font-weight:800;font-size:clamp(32px,5vw,52px);
            color:#fff;margin:0 0 16px;line-height:1.3;}
        .dv-hero p{font-size:16px;color:rgba(234,243,251,.82);max-width:480px;margin:0 auto;line-height:1.7;}

        .dv-section{padding:80px 24px;max-width:1080px;margin:0 auto;}
        .dv-kicker{font-size:12.5px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;
            color:var(--dv-cyan);margin-bottom:12px;text-align:center;}
        .dv-h2{font-family:var(--font-display);font-weight:800;font-size:clamp(24px,3vw,32px);
            text-align:center;margin:0 0 16px;color:#fff;}
        .dv-sub{text-align:center;color:var(--dv-text-mid);max-width:540px;margin:0 auto 50px;
            font-size:14.5px;line-height:1.75;}

        .dv-intro{display:flex;align-items:center;gap:56px;flex-direction:row-reverse;}
        .dv-intro-photo{flex:1;border-radius:26px;overflow:hidden;aspect-ratio:1;min-width:0;
            box-shadow:0 24px 55px -18px rgba(0,0,0,.55);}
        .dv-intro-photo img{width:100%;height:100%;object-fit:cover;display:block;}
        .dv-intro-text{flex:1;min-width:0;}
        .dv-intro-text p{font-size:15.5px;color:var(--dv-text-mid);line-height:1.85;margin:0 0 16px;}
        @media (max-width:820px){.dv-intro{flex-direction:column;gap:28px;}}


        /* 강사 소개 (단독 스포트라이트) */
        .dv-instructor{position:relative;border-radius:36px;overflow:hidden;
            min-height:560px;display:flex;align-items:flex-end;
            background:linear-gradient(90deg, rgba(4,14,26,.15) 0%, rgba(4,14,26,.55) 55%, rgba(4,14,26,.92) 100%),
                url('/images/diving/instructor-main.png') center 20%/cover no-repeat;}
        .dv-instructor-body{position:relative;z-index:1;padding:48px 44px;max-width:520px;margin-left:auto;}
        .dv-instructor-badge{display:inline-flex;align-items:center;gap:8px;padding:6px 14px;border-radius:999px;
            background:rgba(79,195,232,.15);border:1px solid rgba(79,195,232,.4);
            font-size:11px;font-weight:800;color:var(--dv-cyan);letter-spacing:.08em;margin-bottom:18px;text-transform:uppercase;}
        .dv-instructor-name{font-family:var(--font-display);font-weight:800;font-size:clamp(26px,3.4vw,36px);
            color:#fff;margin:0 0 6px;}
        .dv-instructor-role{font-size:13.5px;color:var(--dv-cyan);font-weight:700;margin-bottom:20px;}
        .dv-instructor-desc{font-size:14.5px;color:rgba(234,243,251,.82);line-height:1.85;margin-bottom:24px;}
        .dv-cert-card{display:flex;align-items:center;gap:14px;background:rgba(255,255,255,.06);
            backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.14);border-radius:16px;padding:16px 18px;}
        .dv-cert-icon{width:42px;height:42px;border-radius:12px;flex-shrink:0;
            background:linear-gradient(135deg,var(--dv-cyan-deep),var(--dv-cyan));
            display:flex;align-items:center;justify-content:center;font-size:19px;}
        .dv-cert-info{flex:1;min-width:0;}
        .dv-cert-title{font-size:13px;font-weight:800;color:#fff;margin-bottom:2px;}
        .dv-cert-sub{font-size:11.5px;color:var(--dv-text-mid);}

        .dv-gallery-strip{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px;}
        .dv-gallery-strip img{width:100%;aspect-ratio:3/4;object-fit:cover;border-radius:20px;
            box-shadow:0 16px 34px -18px rgba(0,0,0,.5);}
        .dv-instructor-float{
            position:absolute;left:6%;top:9%;bottom:11%;width:36%;
            border-radius:26px;overflow:hidden;z-index:0;
            box-shadow:0 26px 54px -16px rgba(0,0,0,.65);
            border:1px solid rgba(255,255,255,.12);
        }
        .dv-instructor-float img{width:100%;height:100%;object-fit:cover;display:block;}

        @media (max-width:820px){
            .dv-instructor-float{display:none;}
        }
        
        @media (max-width:820px){
            .dv-instructor{min-height:460px;background-position:center 15%;}
            .dv-instructor-body{margin-left:0;padding:32px 24px;max-width:100%;}
        }

        .dv-curriculum{max-width:800px;margin:0 auto;}
        .dv-level{display:flex;gap:26px;padding:28px 0;border-bottom:1px solid var(--dv-line);}
        .dv-level:last-child{border-bottom:none;}
        .dv-level-num{flex-shrink:0;width:64px;height:64px;border-radius:18px;
            background:linear-gradient(135deg,var(--dv-cyan-deep),var(--dv-cyan));
            display:flex;align-items:center;justify-content:center;
            font-family:var(--font-display);font-weight:800;font-size:22px;color:#04121F;}
        .dv-level-body{flex:1;min-width:0;}
        .dv-level-title{font-family:var(--font-display);font-weight:800;font-size:19px;color:#fff;margin-bottom:6px;}
        .dv-level-sub{font-size:12px;color:var(--dv-cyan);font-weight:700;margin-bottom:10px;letter-spacing:.03em;}
        .dv-level-desc{font-size:14px;color:var(--dv-text-mid);line-height:1.7;}

        .dv-include-wrap{display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:760px;margin:0 auto;}
        @media (max-width:640px){.dv-include-wrap{grid-template-columns:1fr;}}
        .dv-include-card{background:var(--dv-card);border-radius:20px;padding:26px 26px;border:1px solid var(--dv-line);}
        .dv-include-card.ex{background:rgba(255,255,255,.02);}
        .dv-include-title{font-family:var(--font-display);font-weight:800;font-size:15px;margin-bottom:16px;
            display:flex;align-items:center;gap:8px;}
        .dv-include-title.inc{color:var(--dv-cyan);}
        .dv-include-title.exc{color:var(--dv-text-mid);}
        .dv-include-card ul{list-style:none;padding:0;margin:0;}
        .dv-include-card li{font-size:13.5px;color:var(--dv-text-mid);padding-left:20px;position:relative;margin-bottom:10px;line-height:1.5;}
        .dv-include-card.inc-list li::before{content:"✓";position:absolute;left:0;color:var(--dv-cyan);font-weight:800;}
        .dv-include-card.exc-list li::before{content:"—";position:absolute;left:0;color:var(--dv-text-mid);}

        .dv-safety{background:var(--dv-bg-2);border-radius:32px;padding:60px 40px;text-align:center;max-width:820px;margin:0 auto;}
        .dv-safety-icon{font-size:32px;margin-bottom:16px;}
        .dv-safety p{font-size:14.5px;color:var(--dv-text-mid);line-height:1.85;max-width:560px;margin:0 auto;}

        .dv-cta{background:linear-gradient(135deg,#0F3355,#0A1F38);padding:80px 24px;text-align:center;color:#fff;
            border-top:1px solid var(--dv-line);}
        .dv-cta h2{font-family:var(--font-display);font-weight:800;font-size:clamp(24px,3.2vw,32px);margin:0 0 14px;}
        .dv-cta p{font-size:15px;color:var(--dv-text-mid);margin:0 0 28px;}
        .dv-cta-btn{display:inline-flex;align-items:center;gap:8px;padding:15px 32px;border-radius:999px;
            background:linear-gradient(95deg,var(--dv-cyan-deep),var(--dv-cyan));color:#04121F;font-weight:800;font-size:15px;
            box-shadow:0 14px 34px -10px rgba(79,195,232,.45);}
    </style>

    {{-- 히어로 --}}
    <section class="dv-hero">
        <div class="dv-hero-inner">
            <div class="dv-hero-eyebrow">MEEWV DIVING</div>
            <h1>숨을 참는 순간,<br>비로소 고요해져요</h1>
            <p>장비 없이 몸 하나로 바다와 마주하는 시간. </p>
            <p>처음이어도 안전하게, 천천히 깊어질 수 있어요.</p>
        </div>
    </section>

    {{-- 소개 --}}
    <section class="dv-section">
        <div class="dv-intro">
            <div class="dv-intro-photo">
                <img src="/images/diving/diving-1.webp" alt="MEEWV 다이빙 소개">
            </div>
            <div class="dv-intro-text">
                <div class="dv-kicker" style="text-align:left;">About</div>
                <h2 class="dv-h2" style="text-align:left;">기록이 아니라,<br>몰입을 위한 다이빙</h2>
                <p>MEEWV 프리다이빙은 깊이를 겨루는 자리가 아니에요. 물속에서 오롯이 나에게 집중하는 시간, 그리고 같은 순간을 나눈 사람들과 이어지는 자리예요.</p>
                <p>수영을 잘 못해도 괜찮아요. 기초 이론부터 안전 수칙까지, 검증된 커리큘럼으로 천천히 단계를 밟아가요.</p>
            </div>
        </div>
    </section>

    {{-- 강사 소개 --}}
    <section class="dv-section">
        <div class="dv-kicker">Instructor</div>
        <h2 class="dv-h2">강사 소개</h2>
        <p class="dv-sub">국제 공인 자격을 갖춘 강사가 처음부터 끝까지 함께해요.</p>

        <div class="dv-instructor">
            <div class="dv-instructor-float">
                <img src="/images/diving/instructor-2.png" alt="임성원 강사">
            </div>
            <div class="dv-instructor-body">
                <div class="dv-instructor-badge">AIDA International Instructor</div>
                <h3 class="dv-instructor-name">임성원</h3>
                <div class="dv-instructor-role">Freediving Instructor · Sungwon Lim</div>
                <p class="dv-instructor-desc">
                    깊이보다 호흡을 먼저 가르칩니다. 처음 물을 두려워하던 사람도
                    편안하게 잠수할 수 있도록, 안전을 최우선으로 한 단계씩 함께해요.
                </p>

                <div class="dv-cert-card">
                    <div class="dv-cert-icon">🎓</div>
                    <div class="dv-cert-info">
                        <div class="dv-cert-title">AIDA International 공인 자격</div>
                        <div class="dv-cert-sub">Instructor Level · Cert.</div>
                    </div>
                </div>

                <div class="dv-gallery-strip">
                    <img src="/images/diving/instructor-4.jpg" alt="임성원 강사 프리다이빙">
                    <img src="/images/diving/instructor-3.jpg" alt="임성원 강사 프리다이빙">
                </div>
            </div>
        </div>
    </section>

    {{-- 커리큘럼 --}}
    <section class="dv-section" style="background:rgba(255,255,255,.02);border-radius:40px;">
        <div class="dv-kicker">Curriculum</div>
        <h2 class="dv-h2">교육 커리큘럼</h2>
        <p class="dv-sub">국제 기준에 맞춘 3단계 과정으로, 본인 페이스에 맞춰 진행해요.</p>

        <div class="dv-curriculum">
            <div class="dv-level">
                <div class="dv-level-num">01</div>
                <div class="dv-level-body">
                    <div class="dv-level-title">프리다이빙 입문</div>
                    <div class="dv-level-sub">DISCOVER · 이론 + 수영장 실습</div>
                    <div class="dv-level-desc">호흡법, 이퀄라이징(귀 압력 조절), 안전 수칙 등 기초 이론을 배우고, 수영장에서 정적 무호흡과 기본 핀킥을 연습해요.</div>
                </div>
            </div>
            <div class="dv-level">
                <div class="dv-level-num">02</div>
                <div class="dv-level-body">
                    <div class="dv-level-title">프리다이빙 중급</div>
                    <div class="dv-level-sub">INTERMEDIATE · 다이나믹 훈련</div>
                    <div class="dv-level-desc">핀킥 기술을 다듬고, 다이나믹(횡영) 훈련으로 호흡 지속시간을 늘려가요. 오픈워터 적응 훈련도 함께 진행해요.</div>
                </div>
            </div>
            <div class="dv-level">
                <div class="dv-level-num">03</div>
                <div class="dv-level-body">
                    <div class="dv-level-title">프리다이빙 심화</div>
                    <div class="dv-level-sub">ADVANCED · 딥다이빙 & 안전구조</div>
                    <div class="dv-level-desc">프리폴, 덕다이브 등 딥다이빙 기술을 익히고, 파트너 안전 구조법까지 배워 자립적으로 다이빙할 수 있게 돼요.</div>
                </div>
            </div>
        </div>
    </section>

    {{-- 포함/불포함 --}}
    <section class="dv-section">
        <div class="dv-kicker">Price Info</div>
        <h2 class="dv-h2">참가비 안내</h2>
        <p class="dv-sub">참가비에 포함되는 항목과, 따로 준비하셔야 하는 항목을 미리 확인해주세요.</p>

        <div class="dv-include-wrap">
            <div class="dv-include-card inc-list">
                <div class="dv-include-title inc">✓ 포함 항목</div>
                <ul>
                    <li>강습비</li>
                    <li>입장비</li>
                </ul>
            </div>
            <div class="dv-include-card ex exc-list">
                <div class="dv-include-title exc">— 불포함 항목</div>
                <ul>
                    <li>수건</li>
                    <li>세면도구</li>
                    <li>개인 장비(마스크, 핀 등)</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- 안전 --}}
    <section class="dv-section" style="padding-top:0;">
        <div class="dv-safety">
            <div class="dv-safety-icon">🤿</div>
            <p>모든 수업은 자격을 갖춘 강사와 세이프티 다이버가 함께해요. 절대 혼자 무리하지 않도록, 본인의 페이스를 존중하는 것이 저희의 첫 번째 원칙이에요.</p>
        </div>
    </section>

    {{-- CTA --}}
    <section class="dv-cta">
        <h2>물속에서, 새로운 나를 만나보세요</h2>
        <p>처음이어도 괜찮아요. 안전하게, 천천히 시작해봐요.</p>
        <a href="/apply" class="dv-cta-btn">다이빙 모임 신청하기 →</a>
    </section>
</div>