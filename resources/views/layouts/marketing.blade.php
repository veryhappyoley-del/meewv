<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'MEEWV · 새로운 만남을 엮다' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700;12..96,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/variable/pretendardvariable.css" />
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#FFF3E9">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#FFF3E9">

    @livewireStyles
    <style>
:root{
  --void-1:#FFF3E9; --void-2:#FFE1C6; --void-3:#FFD4B0;
  --spark-orange:#FF7A3D; --spark-pink:#FF9770; --spark-violet:#E85D2E; --spark-blue:#D4881F;
  --text-hi:#3A2418; --text-mid:#8A6A56; --text-lo:#B39880;
  --line:rgba(58,36,24,0.10); --card:#FFFFFF;
  --radius-lg:24px; --radius-md:14px; --radius-sm:10px;
  --font-display:'Bricolage Grotesque','Pretendard Variable',Pretendard,sans-serif;
  --font-body:'Pretendard Variable',Pretendard,-apple-system,sans-serif;
}
*{box-sizing:border-box;}
html,body{margin:0;padding:0;}
body{background:var(--void-1);color:var(--text-hi);font-family:var(--font-body);line-height:1.5;min-height:100vh;-webkit-font-smoothing:antialiased;}
a{color:inherit;text-decoration:none;}
::selection{background:var(--spark-pink);color:#fff;}

::-webkit-scrollbar{width:8px;height:8px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:rgba(58,36,24,.15);border-radius:999px;}
html{scrollbar-width:thin;scrollbar-color:rgba(58,36,24,.15) transparent;}

.mv-ambient{position:fixed;inset:0;z-index:0;pointer-events:none;
  background:linear-gradient(180deg, var(--void-1) 0%, var(--void-2) 45%, var(--void-1) 100%);}

.nav{position:sticky;top:0;z-index:50;background:rgba(255,243,233,0.85);backdrop-filter:blur(14px);
  -webkit-backdrop-filter:blur(14px);border-bottom:1px solid var(--line);}
.nav-inner{max-width:1180px;margin:0 auto;padding:16px 24px;display:flex;align-items:center;justify-content:space-between;gap:16px;}
.logo{display:flex;align-items:center;gap:8px;font-family:var(--font-display);font-weight:800;font-size:28px;}
.logo-mark{width:50px;height:auto;object-fit:contain;}
.nav-links{display:flex;align-items:center;gap:26px;font-size:14px;color:var(--text-mid);}
.nav-links a:hover{color:var(--text-hi);}
.nav-cta-group{display:flex;align-items:center;gap:12px;}
.nav-login{font-size:13.5px;color:var(--text-mid);}
.nav-login:hover{color:var(--text-hi);}

.btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:12px 22px;border-radius:999px;
  font-weight:700;font-size:14px;border:none;cursor:pointer;transition:transform .15s, box-shadow .2s, background .2s, border-color .2s;}
.btn-primary{background:linear-gradient(95deg,var(--spark-orange),var(--spark-pink));color:#fff;}
.btn-primary:hover{box-shadow:0 8px 22px -6px rgba(255,151,112,.55);transform:translateY(-1px);}
.btn-ghost{background:transparent;border:1px solid var(--line);color:var(--text-hi);}
.btn-ghost:hover{background:rgba(58,36,24,.05);}
.btn-sm{padding:9px 16px;font-size:13px;}

.footer{position:relative;z-index:1;border-top:1px solid var(--line);padding:40px 24px;text-align:center;color:var(--text-lo);font-size:12.5px;}

.nav-hamburger{display:none;background:none;border:none;cursor:pointer;padding:8px;flex-direction:column;gap:5px;}
.nav-hamburger span{display:block;width:22px;height:2px;background:var(--text-hi);border-radius:2px;transition:all .2s;}
.nav-mobile-menu{display:none;position:fixed;top:0;right:0;bottom:0;width:78%;max-width:320px;
    background:var(--peach-bg,#FFF3E9);z-index:100;box-shadow:-12px 0 40px -12px rgba(58,36,24,.25);
    padding:24px 22px;flex-direction:column;gap:4px;overflow-y:auto;}
.nav-mobile-menu.open{display:flex;}
.nav-mobile-overlay{display:none;position:fixed;inset:0;background:rgba(58,36,24,.35);z-index:99;}
.nav-mobile-overlay.open{display:block;}
.nav-mobile-close{align-self:flex-end;background:none;border:none;font-size:22px;color:var(--text-mid);
    cursor:pointer;padding:6px;margin-bottom:12px;}
.nav-mobile-menu a{display:block;padding:14px 6px;font-size:16px;font-weight:600;color:var(--text-hi);
    border-bottom:1px solid var(--line);}
.nav-mobile-menu a.btn-primary{margin-top:16px;text-align:center;border-bottom:none;color:#fff;}

@media (max-width:820px){
    .nav-links{display:none;}
    .nav-hamburger{display:flex;}
}
    </style>
</head>
<body>
<div class="mv-ambient"></div>

<header class="nav" x-data="{ mobileOpen: false }">
    <div class="nav-inner">
        <a href="/" class="logo">
            <img class="logo-mark" src="/images/logo_simbol.png" alt="MEEWV">MEEWV
        </a>
        <nav class="nav-links">
            <a href="#about">소개</a>
            <a href="#how">이용방법</a>
            <a href="#locations">지점</a>
            <a href="/crew">크루소개</a>
            <a href="/crew-apply">크루제안</a>
        </nav>
        <div class="nav-cta-group">
            @auth
                <a href="/mypage" class="nav-login">마이페이지</a>
            @else
                <a href="/login" class="nav-login">로그인</a>
            @endauth
            <a href="/apply" class="btn btn-primary btn-sm">참가 신청</a>
        </div>
        <button type="button" class="nav-hamburger" @click="mobileOpen = true" aria-label="메뉴 열기">
            <span></span><span></span><span></span>
        </button>
    </div>

    <div class="nav-mobile-overlay" :class="{ open: mobileOpen }" @click="mobileOpen = false"></div>
    <div class="nav-mobile-menu" :class="{ open: mobileOpen }">
        <button type="button" class="nav-mobile-close" @click="mobileOpen = false" aria-label="메뉴 닫기">×</button>
        <a href="#about" @click="mobileOpen = false">소개</a>
        <a href="#how" @click="mobileOpen = false">이용방법</a>
        <a href="#locations" @click="mobileOpen = false">지점</a>
        <a href="/crew" @click="mobileOpen = false">크루소개</a>
        <a href="/crew-apply" @click="mobileOpen = false">크루제안</a>
        @auth
            <a href="/mypage" @click="mobileOpen = false">마이페이지</a>
        @else
            <a href="/login" @click="mobileOpen = false">로그인</a>
        @endauth
        <a href="/apply" class="btn btn-primary" @click="mobileOpen = false">참가 신청</a>
    </div>
</header>

{{ $slot }}

<footer class="footer">
    © 2026 MEEWV. All rights reserved.
</footer>

@livewireScripts
</body>
</html>