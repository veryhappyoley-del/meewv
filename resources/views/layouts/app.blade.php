<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'MEEWV Admin' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,700;12..96,800&display=swap" rel="stylesheet">
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
    <meta name="msapplication-TileColor" content="#0a0712">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#0a0712">

    @livewireStyles
    <style>
:root{
  --void-1:#0a0712; --void-2:#150c24; --void-3:#1c1130;
  --spark-orange:#ff8a3d; --spark-pink:#ff3e7f; --spark-violet:#8b5cf6; --spark-blue:#4c6fff;
  --text-hi:#f6f2fb; --text-mid:#c7bcdb; --text-lo:#8d81a6;
  --line:rgba(246,242,251,0.10); --card:rgba(246,242,251,0.045);
  --radius-lg:20px; --radius-md:14px; --radius-sm:10px;
  --font-display:'Bricolage Grotesque','Pretendard Variable',Pretendard,sans-serif;
  --font-body:'Pretendard Variable',Pretendard,-apple-system,sans-serif;
}
*{box-sizing:border-box;}
html,body{margin:0;padding:0;}
body{background:var(--void-1);color:var(--text-hi);font-family:var(--font-body);line-height:1.5;min-height:100vh;-webkit-font-smoothing:antialiased;}
a{color:inherit;text-decoration:none;}

::-webkit-scrollbar{width:8px;height:8px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:rgba(246,242,251,.15);border-radius:999px;}
::-webkit-scrollbar-thumb:hover{background:rgba(246,242,251,.28);}
html{scrollbar-width:thin;scrollbar-color:rgba(246,242,251,.15) transparent;}

::selection{background:var(--spark-pink);color:var(--void-1);}
[x-cloak]{display:none!important;}

.mv-ambient{position:fixed;inset:0;z-index:0;pointer-events:none;
  background:radial-gradient(ellipse 800px 500px at 10% -10%, rgba(139,92,246,0.18), transparent 60%),
  radial-gradient(ellipse 700px 600px at 110% 10%, rgba(255,62,127,0.12), transparent 60%),
  linear-gradient(180deg, var(--void-1) 0%, var(--void-2) 60%, var(--void-1) 100%);}

/* ===== 어드민 쉘 (사이드바) ===== */
.admin-shell{display:flex;min-height:100vh;position:relative;z-index:1;}
.admin-sidebar{width:248px;flex-shrink:0;background:rgba(21,12,36,.55);border-right:1px solid var(--line);
  display:flex;flex-direction:column;position:sticky;top:0;height:100vh;overflow-y:auto;}
.admin-sidebar-logo{padding:20px 20px;display:flex;align-items:center;gap:8px;
  font-family:var(--font-display);font-weight:800;font-size:15.5px;border-bottom:1px solid var(--line);}
.admin-logo-mark{width:38px;height:28px;flex-shrink:0;object-fit:contain;}
.admin-logo-tag{font-size:10px;font-weight:600;color:var(--text-lo);border:1px solid var(--line);padding:2px 7px;border-radius:999px;margin-left:2px;}
.admin-sidebar-nav{flex:1;padding:14px 12px;}
.admin-nav-section{font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:var(--text-lo);margin:18px 10px 6px;}
.admin-nav-section:first-of-type{margin-top:6px;}
.admin-nav-link{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;
  color:var(--text-mid);font-size:13.3px;margin-bottom:2px;border:1px solid transparent;transition:all .15s;}
.admin-nav-link:hover{background:rgba(246,242,251,.05);color:var(--text-hi);}
.admin-nav-link.active{background:linear-gradient(95deg, rgba(255,138,61,.14), rgba(255,62,127,.10));
  color:var(--text-hi);border-color:var(--line);font-weight:600;}
.admin-nav-icon{width:16px;height:16px;flex-shrink:0;opacity:.85;}
.admin-sidebar-footer{padding:14px 20px;border-top:1px solid var(--line);}
.admin-sidebar-footer .name{font-size:13px;font-weight:600;}
.admin-sidebar-footer .role{font-size:11px;color:var(--text-lo);margin-bottom:8px;}
.admin-logout-btn{background:none;border:none;color:var(--text-lo);font-size:12px;cursor:pointer;padding:0;}
.admin-logout-btn:hover{color:var(--spark-pink);}

.admin-content{flex:1;min-width:0;}
.admin-topbar{position:sticky;top:0;z-index:20;background:rgba(10,7,18,0.72);backdrop-filter:blur(14px);
  -webkit-backdrop-filter:blur(14px);border-bottom:1px solid var(--line);padding:18px 32px;}
.admin-topbar h1{font-family:var(--font-display);font-weight:800;font-size:19px;margin:0;}
.admin-main{padding:28px 32px 80px;max-width:1320px;}

@media (max-width:900px){
  .admin-shell{flex-direction:column;}
  .admin-sidebar{position:static;height:auto;width:100%;}
  .admin-main{padding:20px 16px 60px;}
}

/* ===== 공통 페이지 요소 ===== */
.mv-page-head{margin-bottom:22px;}
.mv-page-head h2{font-family:var(--font-display);font-weight:800;font-size:20px;letter-spacing:-0.01em;margin:0 0 6px;}
.mv-page-head p{color:var(--text-mid);font-size:13.5px;margin:0;}
.empty{padding:44px 20px;text-align:center;color:var(--text-lo);border:1px dashed var(--line);border-radius:var(--radius-md);font-size:14px;}

.btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:10px 18px;border-radius:999px;
  font-weight:700;font-size:13.5px;border:none;cursor:pointer;transition:transform .15s, box-shadow .2s, background .2s, border-color .2s;
  font-family:var(--font-body);}
.btn-primary{background:linear-gradient(95deg,var(--spark-orange),var(--spark-pink));color:var(--void-1);}
.btn-primary:hover{box-shadow:0 8px 22px -6px rgba(255,62,127,.55);transform:translateY(-1px);}
.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text-hi);}
.btn-outline:hover{background:rgba(246,242,251,.06);}
.btn-ghost{background:rgba(246,242,251,.04);border:1px solid var(--line);color:var(--text-mid);}
.btn-danger{background:rgba(255,62,127,.12);color:#ff88ab;border:1px solid rgba(255,62,127,.3);}
.btn-danger:hover{background:rgba(255,62,127,.2);}
.btn-sm{padding:7px 13px;font-size:12.5px;}
.btn-block{width:100%;}
.btn:disabled{opacity:.45;cursor:not-allowed;}

.pill{display:inline-block;padding:4px 11px;border-radius:999px;font-size:11.5px;font-weight:700;}
.pill-pending{background:rgba(255,138,61,.14);color:var(--spark-orange);border:1px solid rgba(255,138,61,.3);}
.pill-success{background:rgba(139,92,246,.16);color:#b9a4ff;border:1px solid rgba(139,92,246,.35);}
.pill-muted{background:rgba(246,242,251,.06);color:var(--text-lo);border:1px solid var(--line);}
.pill-open{background:rgba(139,92,246,.16);color:#b9a4ff;border:1px solid rgba(139,92,246,.35);}
.pill-closed{background:rgba(246,242,251,.06);color:var(--text-lo);border:1px solid var(--line);}

.field{display:flex;flex-direction:column;gap:7px;margin-bottom:16px;}
.field label{font-size:12.5px;font-weight:600;color:var(--text-mid);}
.field input, .field select, .field textarea{padding:10px 13px;border-radius:10px;border:1px solid var(--line);
  background:rgba(246,242,251,.03);color:var(--text-hi);font-family:var(--font-body);font-size:14px;width:100%;}
.field input:focus, .field select:focus, .field textarea:focus{outline:none;border-color:var(--spark-orange);}
.field-error{color:var(--spark-pink);font-size:12px;}
.form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.form-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
@media (max-width:700px){.form-row-2,.form-row-3{grid-template-columns:1fr;}}

.mv-card-block{border:1px solid var(--line);background:var(--card);border-radius:var(--radius-md);padding:20px;margin-bottom:14px;}
.mv-alert{padding:10px 14px;border-radius:10px;font-size:13px;margin-bottom:18px;}
.mv-alert-success{background:rgba(139,92,246,.14);color:#c9b8ff;border:1px solid rgba(139,92,246,.3);}

/* ===== 대시보드 ===== */
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:28px;}
@media (max-width:1100px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
@media (max-width:560px){.stat-grid{grid-template-columns:1fr;}}
.stat-card{border:1px solid var(--line);background:var(--card);border-radius:var(--radius-md);padding:18px 20px;}
.stat-card .label{font-size:12px;color:var(--text-mid);margin-bottom:8px;}
.stat-card .value{font-family:var(--font-display);font-weight:800;font-size:26px;
  background:linear-gradient(95deg,var(--spark-orange),var(--spark-violet));-webkit-background-clip:text;background-clip:text;color:transparent;}
.stat-card .sub{font-size:11.5px;color:var(--text-lo);margin-top:4px;}
.stat-card.warn{border-color:rgba(255,62,127,.35);}

.dash-grid{display:grid;grid-template-columns:1.3fr 1fr;gap:20px;align-items:start;}
@media (max-width:900px){.dash-grid{grid-template-columns:1fr;}}
.dash-section-title{font-size:13.5px;font-weight:700;margin-bottom:12px;color:var(--text-hi);}
.mini-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--line);font-size:13px;}
.mini-row:last-child{border-bottom:none;}
.mini-row .name{font-weight:600;}
.mini-row .meta{color:var(--text-lo);font-size:12px;}

/* ===== 리스트 / 테이블형 카드 ===== */
.item-card{border:1px solid var(--line);background:var(--card);border-radius:var(--radius-md);padding:16px 18px;
  margin-bottom:10px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;}
.item-main{display:flex;flex-direction:column;gap:4px;min-width:0;}
.item-name{font-weight:700;font-size:14.5px;}
.item-meta{font-size:12.3px;color:var(--text-mid);}
.item-actions{display:flex;gap:8px;flex-shrink:0;}

/* ===== 회차 리뷰 카드 (신청 승인) ===== */
.group-header{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:16px 20px;
  border-radius:var(--radius-md);background:linear-gradient(95deg, rgba(255,138,61,.10), rgba(255,62,127,.08));
  border:1px solid var(--line);margin-bottom:16px;}
.group-header .cat-pill{display:inline-flex;align-items:center;gap:8px;font-family:var(--font-display);font-weight:800;font-size:16px;}
.group-header .cat-dot{width:8px;height:8px;border-radius:50%;background:linear-gradient(95deg,var(--spark-orange),var(--spark-pink));flex-shrink:0;}
.group-header .meta{font-size:12.5px;color:var(--text-mid);}
.group-header .count{font-size:12px;color:var(--text-lo);background:rgba(246,242,251,.06);padding:4px 10px;border-radius:999px;border:1px solid var(--line);white-space:nowrap;}

.review-card{display:flex;gap:16px;border:1px solid var(--line);background:var(--card);border-radius:var(--radius-md);padding:18px;margin-bottom:12px;flex-wrap:wrap;}
.review-photo{width:120px;height:120px;border-radius:12px;object-fit:cover;border:1px solid var(--line);flex-shrink:0;background:rgba(246,242,251,.04);}
.review-photo-placeholder{width:120px;height:120px;border-radius:12px;border:1px dashed var(--line);flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--text-lo);font-size:11px;text-align:center;padding:8px;}
.review-photo-wrap{display:flex;flex-direction:column;align-items:center;gap:6px;flex-shrink:0;}
.photo-counter{font-size:11px;color:var(--text-lo);font-weight:600;}
.review-body{flex:1;min-width:220px;display:flex;flex-direction:column;gap:6px;}
.review-name-row{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.review-name{font-weight:700;font-size:16px;}
.review-sub{font-size:12.5px;color:var(--text-mid);}
.review-bio{font-size:13px;color:var(--text-hi);background:rgba(246,242,251,.03);border-radius:8px;padding:10px 12px;margin-top:4px;line-height:1.65;}
.review-tags{display:flex;gap:6px;flex-wrap:wrap;margin-top:4px;}
.review-tag{font-size:11.5px;padding:3px 9px;border-radius:999px;background:rgba(246,242,251,.06);color:var(--text-mid);border:1px solid var(--line);}
.review-actions{display:flex;gap:8px;margin-top:12px;}

.gender-count-bar{display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;}
.gc-pill{font-size:12.5px;font-weight:700;padding:7px 14px;border-radius:999px;border:1px solid var(--line);background:var(--card);color:var(--text-mid);}
.gc-pill.gc-total{color:var(--text-hi);}
.gc-pill.gc-male{color:#8ec5ff;border-color:rgba(142,197,255,.3);}
.gc-pill.gc-female{color:#ffa9c9;border-color:rgba(255,169,201,.3);}

@keyframes mv-pulse{0%,100%{box-shadow:0 0 0 0 rgba(255,62,127,.45);}50%{box-shadow:0 0 0 6px rgba(255,62,127,0);}}
.event-side-item.urgent{border-color:rgba(255,62,127,.5);animation:mv-pulse 1.8s ease-in-out infinite;}
.urgent-tag{color:var(--spark-pink);font-size:10px;font-weight:800;margin-left:6px;white-space:nowrap;}

.admin-layout{display:flex;gap:24px;align-items:flex-start;}
.event-sidebar{width:270px;flex-shrink:0;position:sticky;top:88px;max-height:calc(100vh - 110px);overflow-y:auto;}
.event-sidebar-group{margin-bottom:20px;}
.event-sidebar-group-toggle{display:flex;align-items:center;justify-content:space-between;width:100%;background:none;border:none;cursor:pointer;padding:0 4px;margin:0 0 8px;font-family:var(--font-body);}
.event-sidebar-group-toggle h3{margin:0;font-family:var(--font-display);font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:var(--text-lo);}
.cat-count{color:var(--text-lo);font-weight:400;}
.chev{color:var(--text-lo);font-size:11px;transition:transform .2s;flex-shrink:0;}
.chev.rot{transform:rotate(180deg);}
.event-side-item{display:block;width:100%;text-align:left;padding:10px 11px;border-radius:10px;border:1px solid transparent;background:transparent;color:var(--text-mid);cursor:pointer;margin-bottom:4px;font-family:var(--font-body);}
.event-side-item:hover{background:rgba(246,242,251,.05);}
.event-side-item.active{background:var(--card);border-color:var(--line);color:var(--text-hi);}
.event-side-item .row{display:flex;justify-content:space-between;align-items:center;gap:8px;}
.event-side-item .d{font-weight:700;font-size:12.5px;}
.event-side-item .t{font-size:11px;color:var(--text-lo);margin-top:2px;}
.event-side-badge{font-size:10px;font-weight:700;padding:2px 7px;border-radius:999px;background:rgba(255,138,61,.16);color:var(--spark-orange);flex-shrink:0;}
.event-side-badge.zero{background:rgba(246,242,251,.06);color:var(--text-lo);}
.admin-main-inner{flex:1;min-width:0;}
@media (max-width:900px){.admin-layout{flex-direction:column;}.event-sidebar{width:100%;position:static;max-height:none;}}

/* ===== CRUD 폼/리스트 (회차/지점/카테고리 관리) ===== */
.crud-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:10px;}
.crud-form{border:1px solid var(--line);background:var(--card);border-radius:var(--radius-md);padding:20px;margin-bottom:22px;}
.crud-form-title{font-weight:700;font-size:14.5px;margin-bottom:14px;}
.crud-table-head{display:grid;font-size:11.5px;color:var(--text-lo);text-transform:uppercase;letter-spacing:.04em;
  padding:0 18px 8px;font-weight:700;}
.check-row{display:flex;align-items:center;gap:10px;font-size:14px;color:var(--text-hi);padding:9px 0;}
.check-row input[type=checkbox]{width:17px;height:17px;accent-color:var(--spark-orange);}
.toggle-switch{position:relative;display:inline-block;width:38px;height:22px;}
.toggle-switch input{opacity:0;width:0;height:0;}
.toggle-slider{position:absolute;cursor:pointer;inset:0;background:rgba(246,242,251,.12);border-radius:999px;transition:.2s;}
.toggle-slider:before{content:"";position:absolute;height:16px;width:16px;left:3px;top:3px;background:white;border-radius:50%;transition:.2s;}
.toggle-switch input:checked + .toggle-slider{background:linear-gradient(95deg,var(--spark-orange),var(--spark-pink));}
.toggle-switch input:checked + .toggle-slider:before{transform:translateX(16px);}

/* ===== 회원 관리 테이블 ===== */
.mv-table{width:100%;border-collapse:separate;border-spacing:0 8px;}
.mv-table th{text-align:left;font-size:11px;color:var(--text-lo);text-transform:uppercase;letter-spacing:.04em;padding:0 14px 6px;font-weight:700;}
.mv-table td{background:var(--card);border-top:1px solid var(--line);border-bottom:1px solid var(--line);padding:12px 14px;font-size:13px;}
.mv-table tr td:first-child{border-left:1px solid var(--line);border-top-left-radius:10px;border-bottom-left-radius:10px;}
.mv-table tr td:last-child{border-right:1px solid var(--line);border-top-right-radius:10px;border-bottom-right-radius:10px;}
.search-bar{margin-bottom:16px;max-width:320px;}
    </style>
</head>
<body>
<div class="mv-ambient"></div>

<div class="admin-shell">
    <aside class="admin-sidebar">
        <a href="/admin/dashboard" class="admin-sidebar-logo">
            <img class="admin-logo-mark" src="/images/logo.png" alt="MEEWV">
            MEEWV <span class="admin-logo-tag">Admin</span>
        </a>

        <nav class="admin-sidebar-nav">
            <a href="/admin/dashboard" class="admin-nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <svg class="admin-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                대시보드
            </a>

            <div class="admin-nav-section">운영</div>
            <a href="/admin/today" class="admin-nav-link {{ request()->is('admin/today') ? 'active' : '' }}">
                <svg class="admin-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                오늘 진행
            </a>
            <a href="/admin/attendees" class="admin-nav-link {{ request()->is('admin/attendees') ? 'active' : '' }}">
                <svg class="admin-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
                신청 승인
            </a>
            <a href="/admin/checkin" class="admin-nav-link {{ request()->is('admin/checkin') ? 'active' : '' }}">
                <svg class="admin-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 3v4M8 3v4M3 9h18"/><rect x="3" y="5" width="18" height="16" rx="2"/></svg>
                현장 체크인
            </a>

            <div class="admin-nav-section">콘텐츠 관리</div>
            <a href="/admin/events" class="admin-nav-link {{ request()->is('admin/events') ? 'active' : '' }}">
                <svg class="admin-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 19.5V6a2 2 0 012-2h12a2 2 0 012 2v13.5M4 19.5h16M4 19.5A1.5 1.5 0 005.5 21h13a1.5 1.5 0 001.5-1.5"/></svg>
                회차 관리
            </a>
            <a href="/admin/locations" class="admin-nav-link {{ request()->is('admin/locations') ? 'active' : '' }}">
                <svg class="admin-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 21s-7-4.5-7-10a7 7 0 0114 0c0 5.5-7 10-7 10z"/><circle cx="12" cy="11" r="2.5"/></svg>
                지점 관리
            </a>
            <a href="/admin/categories" class="admin-nav-link {{ request()->is('admin/categories') ? 'active' : '' }}">
                <svg class="admin-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="13" y="3" width="8" height="8" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/><rect x="13" y="13" width="8" height="8" rx="1.5"/></svg>
                카테고리 관리
            </a>
            <a href="/admin/reviews" class="admin-nav-link {{ request()->is('admin/reviews') ? 'active' : '' }}">
                <svg class="admin-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                후기 관리
            </a>

            <div class="admin-nav-section">사용자</div>
            <a href="/admin/users" class="admin-nav-link {{ request()->is('admin/users') ? 'active' : '' }}">
                <svg class="admin-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3.5"/><path d="M4.5 20a7.5 7.5 0 0115 0"/></svg>
                회원 관리
            </a>
            <a href="/admin/signals" class="admin-nav-link {{ request()->is('admin/signals') ? 'active' : '' }}">
                <svg class="admin-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M13 2 4 14h6l-1 8 9-12h-6l1-8z"/></svg>
                시그널 현황
            </a>

            <div class="admin-nav-section">설정</div>
            <a href="/admin/settings" class="admin-nav-link {{ request()->is('admin/settings') ? 'active' : '' }}">
                <svg class="admin-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.34 1.87l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.7 1.7 0 00-1.87-.34 1.7 1.7 0 00-1 1.55V21a2 2 0 01-4 0v-.09a1.7 1.7 0 00-1-1.55 1.7 1.7 0 00-1.87.34l-.06.06a2 2 0 11-2.83-2.83l.06-.06a1.7 1.7 0 00.34-1.87 1.7 1.7 0 00-1.55-1H3a2 2 0 010-4h.09a1.7 1.7 0 001.55-1 1.7 1.7 0 00-.34-1.87l-.06-.06a2 2 0 112.83-2.83l.06.06a1.7 1.7 0 001.87.34H9a1.7 1.7 0 001-1.55V3a2 2 0 014 0v.09a1.7 1.7 0 001 1.55 1.7 1.7 0 001.87-.34l.06-.06a2 2 0 112.83 2.83l-.06.06a1.7 1.7 0 00-.34 1.87V9a1.7 1.7 0 001.55 1H21a2 2 0 010 4h-.09a1.7 1.7 0 00-1.55 1z"/></svg>
                사이트 설정
            </a>
        </nav>

        <div class="admin-sidebar-footer">
            @auth
                <div class="name">{{ auth()->user()->name }}</div>
                <div class="role">관리자</div>
            @endauth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="admin-logout-btn">로그아웃</button>
            </form>
        </div>
    </aside>

    <div class="admin-content">
        <header class="admin-topbar">
            <h1>{{ $title ?? 'MEEWV Admin' }}</h1>
        </header>
        <main class="admin-main">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
