<?php

use App\Models\Event;
use App\Models\Location;
use App\Models\Review;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::marketing')] class extends Component
{
    #[Computed]
    public function locations()
    {
        return Location::orderBy('name')->get();
    }

    #[Computed]
    public function upcomingCount()
    {
        return Event::where('event_date', '>=', now()->toDateString())
            ->where('status', 'open')
            ->count();
    }

    #[Computed]
    public function reviews()
    {
        return Review::where('is_published', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->take(6)
            ->get();
    }
}; ?>

<div>
    <style>
        :root {
            --peach-bg: #FFF3E9;
            --peach-deep: #FFE1C6;
            --salmon: #FF9770;
            --peach-orange: #FF7A3D;
            --peach-orange-deep: #E85D2E;
            --peach-text: #3A2418;
            --peach-text-mid: #8A6A56;
            --peach-line: rgba(58, 36, 24, 0.10);
            --peach-card: #FFFFFF;
        }

        /* 시그널 시스템 폰 목업 */
        .phone-mockup-wrap{padding:70px 24px;text-align:center;}
        .phone-frame{
            width:280px;margin:36px auto 0;background:#151015;border-radius:38px;padding:16px 12px;
            box-shadow:0 30px 60px -20px rgba(58,36,24,.4);position:relative;
        }
        .phone-notch{
            width:90px;height:20px;background:#151015;border-radius:0 0 16px 16px;
            position:absolute;top:0;left:50%;transform:translateX(-50%);z-index:2;
        }
        .phone-screen{background:var(--void-1,#0a0712);border-radius:28px;overflow:hidden;padding:26px 14px 20px;min-height:440px;text-align:left;}
        .phone-screen-title{color:#f6f2fb;font-family:var(--font-display);font-weight:800;font-size:14.5px;margin-bottom:2px;}
        .phone-screen-sub{color:#8d81a6;font-size:11px;margin-bottom:16px;}
        .phone-attendee{display:flex;align-items:center;gap:10px;background:rgba(246,242,251,.04);border:1px solid rgba(246,242,251,.09);
            border-radius:14px;padding:10px 12px;margin-bottom:9px;}
        .phone-avatar{width:34px;height:34px;border-radius:50%;flex-shrink:0;background:linear-gradient(135deg,var(--peach-orange,#FF7A3D),var(--salmon,#FF9770));
            display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:12px;}
        .phone-attendee-info{flex:1;min-width:0;}
        .phone-attendee-name{color:#f6f2fb;font-size:12.5px;font-weight:700;}
        .phone-attendee-job{color:#8d81a6;font-size:10.5px;}
        .phone-signal-btn{font-size:10.5px;font-weight:700;padding:6px 10px;border-radius:999px;
            background:linear-gradient(95deg,var(--peach-orange,#FF7A3D),var(--salmon,#FF9770));color:#fff;flex-shrink:0;white-space:nowrap;}
        .phone-signal-sent{font-size:10.5px;font-weight:700;padding:6px 10px;border-radius:999px;
            background:rgba(246,242,251,.06);color:#8d81a6;flex-shrink:0;white-space:nowrap;}
       .phone-att-card{background:rgba(246,242,251,.03);border:1px solid rgba(246,242,251,.09);border-radius:14px;padding:12px;margin-bottom:10px;}
        .phone-att-head{display:flex;align-items:center;gap:8px;margin-bottom:8px;}
        .phone-att-name{color:#f6f2fb;font-size:13px;font-weight:800;flex:1;}
        .phone-att-tags{display:flex;gap:5px;margin-bottom:8px;flex-wrap:wrap;}
        .phone-tag{font-size:9px;font-weight:700;padding:2px 8px;border-radius:999px;background:rgba(246,242,251,.06);color:#8d81a6;}
        .phone-tag.badge{background:rgba(255,138,61,.16);color:var(--peach-orange,#FF7A3D);}
        .phone-att-grid{display:grid;grid-template-columns:1fr 1fr;gap:6px;}
        .phone-tile{background:rgba(246,242,251,.03);border:1px solid rgba(246,242,251,.08);border-radius:8px;
            padding:6px 8px;font-size:9.5px;color:#f6f2fb;font-weight:600;display:flex;align-items:center;gap:4px;
            overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
        /* 타임라인 */
        .peach-timeline {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            max-width: 1080px;
            margin: 0 auto 70px;
            padding: 0 10px;
        }

        .peach-tl-line {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--peach-line);
            transform: translateY(-50%);
            z-index: 0;
        }

        .peach-tl-item {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }

        .peach-tl-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--peach-orange);
            border: 3px solid var(--peach-bg);
            box-shadow: 0 0 0 2px var(--peach-line);
        }

        .peach-tl-label {
            text-align: center;
            padding: 14px 6px;
        }

        .peach-tl-time {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 16px;
            color: var(--peach-orange-deep);
        }

        .peach-tl-title {
            font-weight: 700;
            font-size: 14px;
            color: var(--peach-text);
            margin-top: 2px;
        }

        .peach-tl-desc {
            font-size: 11.5px;
            color: var(--peach-text-mid);
            margin-top: 4px;
            line-height: 1.5;
        }

        @media (max-width:820px) {
            .peach-timeline {
                flex-direction: column;
                gap: 0;
            }

            .peach-tl-line {
                display: none;
            }

            .peach-tl-item {
                flex-direction: row;
                align-items: flex-start;
                gap: 14px;
                width: 100%;
                margin-bottom: 18px;
            }

            .peach-tl-label {
                text-align: left;
                padding: 0;
            }
        }

        /* 사진 콜라주 */
        .peach-collage {
            position: relative;
            max-width: 1150px;
            height: 440px;
            margin: 0 auto;
        }

        .peach-collage img {
            position: absolute;
            border-radius: 14px;
            object-fit: cover;
            box-shadow: 0 16px 34px -14px rgba(58, 36, 24, .35);
            border: 4px solid #fff;
        }

        .pc-1 {
            width: 220px;
            height: 150px;
            top: 10px;
            left: 0;
            transform: rotate(-6deg);
            z-index: 2;
        }

        .pc-2 {
            width: 190px;
            height: 250px;
            top: 0;
            left: 190px;
            transform: rotate(4deg);
            z-index: 3;
        }

        .pc-3 {
            width: 210px;
            height: 160px;
            top: 40px;
            left: 360px;
            transform: rotate(-3deg);
            z-index: 1;
        }

        .pc-4 {
            width: 200px;
            height: 150px;
            top: 190px;
            left: 60px;
            transform: rotate(5deg);
            z-index: 2;
        }

        .pc-5 {
            width: 220px;
            height: 170px;
            top: 210px;
            left: 300px;
            transform: rotate(-4deg);
            z-index: 3;
        }

        .pc-6 {
            width: 190px;
            height: 150px;
            top: 20px;
            left: 550px;
            transform: rotate(3deg);
            z-index: 1;
        }

        .pc-7 {
            width: 200px;
            height: 170px;
            top: 180px;
            left: 530px;
            transform: rotate(-5deg);
            z-index: 2;
        }

        .pc-8 {
            width: 190px;
            height: 230px;
            top: 0;
            left: 750px;
            transform: rotate(4deg);
            z-index: 3;
        }

        .pc-9 {
            width: 210px;
            height: 150px;
            top: 250px;
            left: 740px;
            transform: rotate(-3deg);
            z-index: 1;
        }

        .pc-10 {
            width: 170px;
            height: 160px;
            top: 60px;
            left: 950px;
            transform: rotate(6deg);
            z-index: 2;
        }

        @media (max-width:820px) {
            .peach-collage {
                height: auto;
            }

            .peach-collage img {
                position: static;
                width: 100% !important;
                height: 200px !important;
                margin-bottom: 14px;
                transform: none !important;
            }
        }

        .peach-page {
            background: var(--peach-bg);
            color: var(--peach-text);
            position: relative;
            z-index: 1;
        }

        /* 히어로 */
        .peach-hero {
            padding: 90px 24px 70px;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }

        .peach-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            border-radius: 999px;
            background: var(--peach-card);
            border: 1px solid var(--peach-line);
            font-size: 12.5px;
            letter-spacing: 0.14em;
            color: var(--peach-orange-deep);
            font-weight: 700;
            margin-bottom: 22px;
            text-transform: uppercase;
        }

        .peach-hero h1 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(30px, 5vw, 46px);
            line-height: 1.3;
            margin: 0 0 18px;
            color: var(--peach-text);
        }

        .peach-hero p {
            font-size: 16px;
            color: var(--peach-text-mid);
            line-height: 1.7;
            max-width: 520px;
            margin: 0 auto 32px;
        }

        .peach-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 15px 30px;
            border-radius: 999px;
            background: linear-gradient(95deg, var(--peach-orange), var(--salmon));
            color: #fff;
            font-weight: 800;
            font-size: 15px;
            box-shadow: 0 12px 28px -8px rgba(255, 122, 61, .45);
            transition: transform .2s;
            border: none;
            cursor: pointer;
        }

        .peach-btn:hover {
            transform: translateY(-2px);
        }

        .peach-hero-photo {
            width: 100%;
            max-width: 760px;
            margin: 0 auto;
            border-radius: 28px;
            overflow: hidden;
            aspect-ratio: 16/9;
            background: linear-gradient(135deg, var(--peach-deep), var(--salmon));
        }

        .peach-hero-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* 지점 */
        .peach-locations {
            padding: 60px 24px;
            max-width: 1080px;
            margin: 0 auto;
        }

        .peach-kicker {
            font-size: 12.5px;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--peach-orange-deep);
            margin-bottom: 10px;
            text-align: center;
        }

        .peach-h2 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(24px, 3vw, 32px);
            text-align: center;
            margin: 0 0 16px;
            color: var(--peach-text);
        }

        .peach-sub {
            text-align: center;
            color: var(--peach-text-mid);
            max-width: 520px;
            margin: 0 auto 40px;
            font-size: 14.5px;
            line-height: 1.7;
        }

        .peach-loc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 18px;
        }

        .peach-loc-card {
            background: var(--peach-card);
            border: 1px solid var(--peach-line);
            border-radius: 20px;
            padding: 26px 24px;
            box-shadow: 0 8px 24px -12px rgba(58, 36, 24, .12);
        }

        .peach-loc-card h3 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 19px;
            margin: 0 0 8px;
            color: var(--peach-orange-deep);
        }

        .peach-loc-card {
            text-decoration: none;
            display: block;
            transition: transform .2s;
        }

        .peach-loc-card:hover {
            transform: translateY(-3px);
        }

        .peach-loc-card p {
            font-size: 13.5px;
            color: var(--peach-text-mid);
            margin: 2px 0;
        }

        /* 통계/스테이트먼트 (사진+한줄) */
        .peach-statement {
            padding: 0;
        }

        .peach-statement-photo {
            width: 100%;
            aspect-ratio: 21/9;
            overflow: hidden;
            position: relative;
        }

        .peach-statement-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .peach-statement-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(0deg, rgba(58, 36, 24, .55), rgba(58, 36, 24, .1) 60%);
            display: flex;
            align-items: flex-end;
            padding: 40px;
        }

        .peach-statement-overlay h3 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(22px, 3.4vw, 32px);
            color: #fff;
            margin: 0;
            line-height: 1.4;
        }

        /* 에디토리얼 alternating 섹션 */
        .peach-feature {
            padding: 70px 24px;
        }

        .peach-feature-inner {
            max-width: 1080px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 60px;
        }

        .peach-feature-inner.reverse {
            flex-direction: row-reverse;
        }

        .peach-feature-photo {
            flex: 1;
            border-radius: 28px;
            overflow: hidden;
            aspect-ratio: 4/3;
            min-width: 0;
            box-shadow: 0 20px 50px -20px rgba(58, 36, 24, .25);
        }

        .peach-feature-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .peach-feature-text {
            flex: 1;
            min-width: 0;
        }

        .peach-feature-text .tag {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--salmon);
            margin-bottom: 14px;
            display: block;
        }

        .peach-feature-text h3 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(22px, 2.6vw, 30px);
            line-height: 1.35;
            margin: 0 0 16px;
            color: var(--peach-text);
        }

        .peach-feature-text p {
            font-size: 15px;
            color: var(--peach-text-mid);
            line-height: 1.8;
        }

        @media (max-width:820px) {

            .peach-feature-inner,
            .peach-feature-inner.reverse {
                flex-direction: column;
                gap: 28px;
            }
        }

        .peach-alt-bg {
            background: var(--peach-deep);
        }

        /* 문의 섹션 */
        .peach-contact {
            padding: 80px 24px;
            text-align: center;
        }

        .peach-contact h2 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(24px, 3vw, 32px);
            margin: 0 0 14px;
        }

        .peach-contact p {
            color: var(--peach-text-mid);
            margin: 0 0 28px;
        }

        .peach-insta-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 26px;
            border-radius: 999px;
            background: var(--peach-card);
            border: 1px solid var(--peach-line);
            color: var(--peach-orange-deep);
            font-weight: 700;
            font-size: 14.5px;
        }

        /* 소개 캐러셀 */
        .peach-carousel-wrap {
            padding: 70px 24px;
        }

        .peach-carousel {
            max-width: 640px;
            margin: 0 auto;
        }

        .peach-carousel-frame {
            border-radius: 24px;
            overflow: hidden;
            aspect-ratio: 4/5;
            box-shadow: 0 20px 50px -20px rgba(58, 36, 24, .3);
            position: relative;
        }

        .peach-carousel-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            position: absolute;
            inset: 0;
        }

        .peach-carousel-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .peach-carousel-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--peach-card);
            border: 1px solid var(--peach-line);
            color: var(--peach-orange-deep);
            font-size: 16px;
            cursor: pointer;
        }

        .peach-carousel-counter {
            font-size: 13.5px;
            color: var(--peach-text-mid);
            font-weight: 700;
            min-width: 50px;
            text-align: center;
        }

        /* 미디어 노출 (준비중 placeholder) */
        .peach-media-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            max-width: 1080px;
            margin: 0 auto;
        }

        @media (max-width:700px) {
            .peach-media-grid {
                grid-template-columns: 1fr;
            }
        }

        .peach-media-slot {
            aspect-ratio: 16/10;
            border-radius: 16px;
            border: 1.5px dashed var(--peach-line);
            background: var(--peach-card);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--peach-text-mid);
            font-size: 13px;
            font-weight: 600;
        }

        /* 후기 */
        .peach-reviews {
            padding: 70px 24px;
            max-width: 1080px;
            margin: 0 auto;
        }

        .peach-review-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        @media (max-width:820px) {
            .peach-review-grid {
                grid-template-columns: 1fr;
            }
        }

        .peach-review-card {
            background: var(--peach-card);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--peach-line);
            box-shadow: 0 8px 24px -14px rgba(58, 36, 24, .14);
        }

        .peach-review-card img {
            width: 100%;
            aspect-ratio: 4/5;
            object-fit: cover;
            display: block;
        }

        .peach-review-body {
            padding: 18px 20px;
        }

        .peach-review-body p {
            font-size: 14px;
            color: var(--peach-text);
            line-height: 1.6;
            margin: 0 0 10px;
        }

        .peach-review-name {
            font-size: 12px;
            color: var(--peach-text-mid);
            font-weight: 700;
        }

        /* 최종 CTA */
        .peach-final {
            padding: 90px 24px;
            text-align: center;
            background: linear-gradient(135deg, var(--peach-orange), var(--salmon));
            color: #fff;
        }

        .peach-final h2 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(26px, 3.5vw, 36px);
            margin: 0 0 14px;
        }

        .peach-final p {
            font-size: 15.5px;
            opacity: .92;
            margin: 0 0 30px;
        }

        .peach-final .peach-btn {
            background: #fff;
            color: var(--peach-orange-deep);
            box-shadow: 0 12px 28px -8px rgba(0, 0, 0, .25);
        }

        /* 유튜브 영상 */
        .peach-video-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            max-width: 1080px;
            margin: 0 auto;
        }

        @media (max-width:820px) {
            .peach-video-grid {
                grid-template-columns: 1fr;
            }
        }

        .peach-video-frame {
            position: relative;
            aspect-ratio: 16/9;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px -20px rgba(58, 36, 24, .25);
        }

        .peach-video-frame iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>

    <div class="peach-page">

        {{-- 히어로 --}}
        <section class="peach-hero">
            <div class="peach-eyebrow">MEET · WEAVE · MEEWV</div>
            <h1>반갑습니다!<br>이제 새로운 인연을<br>만날 시간입니다.</h1>
            <p>성비를 맞춘 테이블, 프로 MC가 이끄는 아이스브레이킹. 파티부터 러닝, 요가까지 — 새로운 만남과 인연을 엮어갑니다.</p>
            <a href="/apply" class="peach-btn">이번 주 참가 신청 →</a>

            <div class="peach-hero-photo" style="margin-top:44px;">
                <img src="/images/hero/1.png" alt="MEEWV 파티 현장">
            </div>
        </section>

        {{-- 지점별 소개 --}}
        <section class="peach-locations">
            <div class="peach-kicker">Our Locations</div>
            <h2 class="peach-h2">지점별 소개</h2>
            <div class="peach-loc-grid">
                @forelse ($this->locations as $loc)
                <a href="/apply" class="peach-loc-card">
                    <h3>{{ $loc->name }} 신청하기</h3>
                    @if ($loc->description)
                    <p style="margin-top:10px;line-height:1.7;">{{ $loc->description }}</p>
                    @endif
                </a>
                @empty
                <p style="color:var(--peach-text-mid);text-align:center;">등록된 지점이 아직 없어요.</p>
                @endforelse
            </div>
        </section>

        {{-- 콘텐츠 3가지 스테이트먼트 --}}
        <div class="peach-statement">
            <div class="peach-statement-photo">
                <img src="/images/hero/2.png" alt="파티·러닝·요가">
                <div class="peach-statement-overlay">
                    <h3>매주 새롭게 열리는 콘텐츠, 3가지.<br>파티부터 러닝, 요가까지.</h3>
                </div>
            </div>
        </div>

        {{-- 파티 진행 흐름 + 현장 사진 콜라주 --}}
        <section style="padding:70px 24px;">
            <div class="peach-kicker">Party Flow</div>
            <h2 class="peach-h2">평균 진행되는 콘텐츠, 5가지</h2>
            <p class="peach-sub">시작부터 시그널까지, 한 번의 파티 흐름이에요. (예시 일정이며 회차마다 달라질 수 있어요)</p>

            <div class="peach-timeline">
                <div class="peach-tl-line"></div>

                <div class="peach-tl-item">
                    <div class="peach-tl-label" style="order:0;">
                        <div class="peach-tl-time">20:30</div>
                        <div class="peach-tl-title">파티 시작</div>
                        <div class="peach-tl-desc">입장과 함께 자리 배정</div>
                    </div>
                    <div class="peach-tl-dot" style="order:1;"></div>
                </div>

                <div class="peach-tl-item">
                    <div class="peach-tl-dot" style="order:0;"></div>
                    <div class="peach-tl-label" style="order:1;">
                        <div class="peach-tl-time">21:00</div>
                        <div class="peach-tl-title">아이스브레이킹</div>
                        <div class="peach-tl-desc">MC와 함께하는 게임 타임</div>
                    </div>
                </div>

                <div class="peach-tl-item">
                    <div class="peach-tl-label" style="order:0;">
                        <div class="peach-tl-time">22:00</div>
                        <div class="peach-tl-title">자유 대화</div>
                        <div class="peach-tl-desc">테이블별 자유로운 이야기</div>
                    </div>
                    <div class="peach-tl-dot" style="order:1;"></div>
                </div>

                <div class="peach-tl-item">
                    <div class="peach-tl-dot" style="order:0;"></div>
                    <div class="peach-tl-label" style="order:1;">
                        <div class="peach-tl-time">23:00</div>
                        <div class="peach-tl-title">시그널 타임</div>
                        <div class="peach-tl-desc">마음에 드는 사람에게 시그널을</div>
                    </div>
                </div>

                <div class="peach-tl-item">
                    <div class="peach-tl-label" style="order:0;">
                        <div class="peach-tl-time">24:00</div>
                        <div class="peach-tl-title">파티 종료</div>
                        <div class="peach-tl-desc">매칭 확인은 마이페이지에서</div>
                    </div>
                    <div class="peach-tl-dot" style="order:1;"></div>
                </div>
            </div>

            <div class="peach-collage">
                <img src="/images/hero/1.png" class="pc-1" alt="파티 현장 1">
                <img src="/images/hero/2.png" class="pc-2" alt="파티 현장 2">
                <img src="/images/hero/3.png" class="pc-3" alt="파티 현장 3">
                <img src="/images/hero/4.png" class="pc-4" alt="파티 현장 4">
                <img src="/images/hero/5.png" class="pc-5" alt="파티 현장 5">
                <img src="/images/hero/6.png" class="pc-6" alt="파티 현장 6">
                <img src="/images/hero/1.png" class="pc-7" alt="파티 현장 7">
                <img src="/images/hero/2.png" class="pc-8" alt="파티 현장 8">
                <img src="/images/hero/3.png" class="pc-9" alt="파티 현장 9">
                <img src="/images/hero/4.png" class="pc-10" alt="파티 현장 10">
            </div>
        </section>

        {{-- 시그널 시스템 --}}
        <section class="peach-feature">
            <div class="peach-feature-inner">
                <div class="peach-feature-photo"><img src="/images/hero/3.png" alt="시그널 시스템"></div>
                <div class="peach-feature-text">
                    <span class="tag">Real-time Signal</span>
                    <h3>실시간 시그널 시스템,<br>마음이 통하면 바로 연결</h3>
                    <p>현장에서 마음에 드는 사람에게 시그널을 보내보세요. 상대방이 수락하면 그 자리에서 바로 연결돼요. 기술로 더 자연스러워진 설렘의 순간입니다.</p>
                </div>
            </div>
        </section>

        {{-- 실시간 시그널 시스템 폰 목업 --}}
        <section class="phone-mockup-wrap">
            <div class="peach-kicker">Real-time Signal</div>
            <h2 class="peach-h2">실시간 시그널 시스템</h2>
            <p class="peach-sub">마음이 통하면, 그 자리에서 바로 연결돼요.</p>

            <div class="phone-frame">
                <div class="phone-notch"></div>
                <div class="phone-screen">
                    <div class="phone-screen-title">오늘 참석자</div>
                    <div class="phone-screen-sub">마음에 드는 사람에게 시그널을 보내보세요.</div>

                    <div class="phone-att-card">
                        <div class="phone-att-head">
                            <div class="phone-avatar">서</div>
                            <div class="phone-att-name">서연</div>
                            <div class="phone-signal-btn">시그널</div>
                        </div>
                        <div class="phone-att-tags">
                            <span class="phone-tag">여성</span>
                            <span class="phone-tag">28세</span>
                            <span class="phone-tag badge">03번</span>
                        </div>
                        <div class="phone-att-grid">
                            <div class="phone-tile"><span>💼</span>마케터</div>
                            <div class="phone-tile"><span>🎯</span>여행,요가</div>
                            <div class="phone-tile"><span>📸</span>@seoyeon</div>
                            <div class="phone-tile"><span>💬</span>여행 좋아해요</div>
                        </div>
                    </div>

                    <div class="phone-att-card">
                        <div class="phone-att-head">
                            <div class="phone-avatar">하</div>
                            <div class="phone-att-name">하은</div>
                            <div class="phone-signal-sent">전송됨</div>
                        </div>
                        <div class="phone-att-tags">
                            <span class="phone-tag">여성</span>
                            <span class="phone-tag">26세</span>
                            <span class="phone-tag badge">07번</span>
                        </div>
                        <div class="phone-att-grid">
                            <div class="phone-tile"><span>💼</span>디자이너</div>
                            <div class="phone-tile"><span>🎯</span>러닝,독서</div>
                            <div class="phone-tile"><span>📸</span>@haeun</div>
                            <div class="phone-tile"><span>💬</span>운동 좋아해요</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 성비 매칭 --}}
        <section class="peach-feature peach-alt-bg">
            <div class="peach-feature-inner reverse">
                <div class="peach-feature-photo"><img src="/images/hero/4.png" alt="성비 매칭"></div>
                <div class="peach-feature-text">
                    <span class="tag">Table Matching</span>
                    <h3>성비 1:1 테이블,<br>어색함 없는 자리</h3>
                    <p>신청 즉시 무작위로 배정되는 게 아니라, 성비와 연령대를 고려해 자리를 구성해요. 처음 온 자리도 자연스럽게 대화가 이어지도록, 시작부터 세심하게 설계합니다.</p>
                </div>
            </div>
        </section>

        {{-- 검증된 신청자 --}}
        <section class="peach-feature">
            <div class="peach-feature-inner">
                <div class="peach-feature-photo"><img src="/images/hero/5.png" alt="검증된 신청자"></div>
                <div class="peach-feature-text">
                    <span class="tag">Verified Members</span>
                    <h3>검증된 신청자만,<br>안심하고 즐기는 자리</h3>
                    <p>간단한 프로필 확인 절차를 거친 분들만 참여할 수 있어요. 누구나 편안하게, 안전하게 즐길 수 있는 자리를 만드는 게 저희의 원칙이에요.</p>
                </div>
            </div>
        </section>

        {{-- MC 진행 --}}
        <section class="peach-feature peach-alt-bg">
            <div class="peach-feature-inner reverse">
                <div class="peach-feature-photo"><img src="/images/hero/6.png" alt="MC 진행"></div>
                <div class="peach-feature-text">
                    <span class="tag">Ice Breaking</span>
                    <h3>프로 MC의 진행,<br>게임으로 여는 대화</h3>
                    <p>경험 많은 MC가 아이스브레이킹 게임으로 대화의 문을 자연스럽게 열어드려요. 어색함은 게임으로 풀고, 남는 건 진짜 대화뿐이에요.</p>
                </div>
            </div>
        </section>

        {{-- 문의 --}}
        <section class="peach-contact">
            <div class="peach-kicker">Contact</div>
            <h2>모든 문의는 이곳으로</h2>
            <p>망설임이 확신이 되는 순간, MEEWV가 함께할게요.</p>
            <a href="#" class="peach-insta-btn">Instagram DM 문의하기</a>
        </section>

        {{-- 우리를 소개할게요 (캐러셀) --}}
        <section class="peach-carousel-wrap">
            <div class="peach-kicker">Our Story</div>
            <h2 class="peach-h2">우리를 소개할게요</h2>
            <p class="peach-sub">마치 여행지에서 만난 사람처럼.</p>

            <div class="peach-carousel" x-data="{ i: 0, total: 8 }">
                <div class="peach-carousel-frame">
                    <template x-for="n in total" :key="n">
                        <img :src="'/images/hero/' + n + '.png'" x-show="i === n - 1" x-cloak>
                    </template>
                </div>
                <div class="peach-carousel-controls">
                    <button type="button" @click="i = (i - 1 + total) % total" class="peach-carousel-btn">←</button>
                    <span class="peach-carousel-counter" x-text="(i + 1) + ' / ' + total"></span>
                    <button type="button" @click="i = (i + 1) % total" class="peach-carousel-btn">→</button>
                </div>
            </div>
        </section>

        {{-- 영상으로 만나보세요 --}}
        <section style="padding:70px 24px;">
            <div class="peach-kicker">Video</div>
            <h2 class="peach-h2">영상으로 만나보세요</h2>
            <p class="peach-sub">MEEWV의 순간들을 영상으로도 확인해보세요.</p>
            <div class="peach-video-grid">
                <div class="peach-video-frame">
                    <iframe src="https://www.youtube.com/embed/lOVNuTJhJ5k" title="MEEWV 영상 1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="peach-video-frame">
                    <iframe src="https://www.youtube.com/embed/etv8Ahue-wE" title="MEEWV 영상 2" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </section>

        {{-- 미디어 노출 (준비중) --}}
        <section style="padding:70px 24px;">
            <div class="peach-kicker">In The Media</div>
            <h2 class="peach-h2">앞으로 만나게 될 이야기</h2>
            <p class="peach-sub">MEEWV가 언론과 미디어에 소개되면, 이 자리에 하나씩 채워나갈게요.</p>
            <div class="peach-media-grid">
                <div class="peach-media-slot">준비중</div>
                <div class="peach-media-slot">준비중</div>
                <div class="peach-media-slot">준비중</div>
            </div>
        </section>

        {{-- 후기 --}}
        <section class="peach-reviews">
            <div class="peach-kicker">Real Reviews</div>
            <h2 class="peach-h2">다녀간 사람들의 진짜 순간</h2>
            <div class="peach-review-grid">
                @forelse ($this->reviews as $review)
                <div class="peach-review-card">
                    @if ($review->photo_path)
                    <img src="{{ asset('storage/' . $review->photo_path) }}" alt="{{ $review->author_name }}님의 후기">
                    @endif
                    <div class="peach-review-body">
                        <p>&ldquo;{{ $review->content }}&rdquo;</p>
                        <div class="peach-review-name">{{ $review->author_name }}</div>
                    </div>
                </div>
                @empty
                <p style="color:var(--peach-text-mid);text-align:center;">아직 등록된 후기가 없어요.</p>
                @endforelse
            </div>
        </section>

        {{-- 최종 CTA --}}
        <section class="peach-final">
            <h2>이번 주, 당신의 이야기가<br>새롭게 엮입니다.</h2>
            <p>자리는 한정되어 있어요. 지금 신청하고 함께해요.</p>
            <a href="/apply" class="peach-btn">지금 신청하기</a>
        </section>

    </div>
</div>