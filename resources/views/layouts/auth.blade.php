<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'MEEWV' }}</title>
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
    <meta name="msapplication-TileColor" content="#FFF3E9">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#FFF3E9">

    @livewireStyles
    <style>
:root{
  --void-1:#FFF3E9;
  --void-2:#FFE1C6;
  --void-3:#FFD4B0;
  --spark-orange:#FF7A3D;
  --spark-pink:#FF9770;
  --spark-violet:#E85D2E;
  --spark-blue:#D4881F;
  --text-hi:#3A2418;
  --text-mid:#8A6A56;
  --text-lo:#B39880;
  --line:rgba(58,36,24,0.10);
  --card:#FFFFFF;
  --radius-lg:24px;
  --radius-md:14px;
  --radius-sm:10px;
  --font-display:'Bricolage Grotesque','Pretendard Variable',Pretendard,sans-serif;
  --font-body:'Pretendard Variable',Pretendard,-apple-system,sans-serif;
}
*{box-sizing:border-box;}
html,body{margin:0;padding:0;}
body{background:var(--void-1);color:var(--text-hi);font-family:var(--font-body);line-height:1.5;min-height:100vh;-webkit-font-smoothing:antialiased;overflow-x:hidden;}
a{color:inherit;text-decoration:none;}
::selection{background:var(--spark-pink);color:#fff;}

::-webkit-scrollbar{width:8px;height:8px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:rgba(58,36,24,.15);border-radius:999px;}
html{scrollbar-width:thin;scrollbar-color:rgba(58,36,24,.15) transparent;}

.mv-ambient{
  position:fixed;inset:0;z-index:0;pointer-events:none;
  background:linear-gradient(180deg, var(--void-1) 0%, var(--void-2) 60%, var(--void-1) 100%);
}

.mv-nav{
  position:sticky;top:0;z-index:50;
  background:rgba(255,243,233,0.82);
  backdrop-filter:blur(14px);
  -webkit-backdrop-filter:blur(14px);
  border-bottom:1px solid var(--line);
}
.mv-nav-inner{
  max-width:900px;margin:0 auto;padding:16px 24px;
  display:flex;align-items:center;justify-content:space-between;gap:16px;
}
.mv-logo{
  display:flex;align-items:center;gap:8px;
  font-family:var(--font-display);font-weight:800;font-size:17px;letter-spacing:0.01em;
}
.mv-logo-mark{width:50px;height:auto;object-fit:contain;}
.mv-logo-tag{font-size:11px;font-weight:600;color:var(--text-lo);border:1px solid var(--line);padding:2px 8px;border-radius:999px;}
.mv-nav-links{display:flex;align-items:center;gap:22px;font-size:14px;color:var(--text-mid);}
.mv-nav-links a{padding-bottom:3px;border-bottom:1px solid transparent;transition:color .2s;}
.mv-nav-links a:hover{color:var(--text-hi);}
.mv-nav-links a.active{color:var(--text-hi);border-bottom-color:var(--spark-pink);}
.mv-user{font-size:13px;color:var(--text-lo);}

.mv-page-wrap{position:relative;z-index:1;max-width:900px;margin:0 auto;padding:40px 24px 80px;}
.mv-page-narrow{max-width:440px;padding-top:56px;}

.mv-page-head{margin-bottom:28px;}
.mv-page-head h1{font-family:var(--font-display);font-weight:800;font-size:24px;letter-spacing:-0.01em;margin:0 0 8px;}
.mv-page-head p{color:var(--text-mid);font-size:14.5px;margin:0;}

.mv-footer{position:relative;z-index:1;text-align:center;padding:32px;color:var(--text-lo);font-size:12px;}

/* cards & list items */
.item-card{
  border:1px solid var(--line);background:var(--card);border-radius:var(--radius-md);
  padding:18px 20px;margin-bottom:12px;
  display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;
  box-shadow:0 8px 20px -14px rgba(58,36,24,.14);
}
.item-main{display:flex;flex-direction:column;gap:4px;min-width:0;}
.item-name{font-weight:700;font-size:15.5px;}
.item-meta{font-size:12.5px;color:var(--text-mid);}
.item-actions{display:flex;gap:8px;flex-shrink:0;}

.empty{
  padding:44px 20px;text-align:center;color:var(--text-lo);
  border:1px dashed var(--line);border-radius:var(--radius-md);font-size:14px;
}

/* buttons */
.btn{
  display:inline-flex;align-items:center;justify-content:center;gap:6px;
  padding:10px 18px;border-radius:999px;font-weight:700;font-size:13.5px;
  border:none;cursor:pointer;transition:transform .15s, box-shadow .2s, background .2s, border-color .2s;
  font-family:var(--font-body);
}
.btn-primary{background:linear-gradient(95deg,var(--spark-orange),var(--spark-pink));color:#fff;}
.btn-primary:hover{box-shadow:0 8px 22px -6px rgba(255,151,112,.55);transform:translateY(-1px);}
.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text-hi);}
.btn-outline:hover{background:rgba(58,36,24,.05);}
.btn-ghost{background:rgba(58,36,24,.04);border:1px solid var(--line);color:var(--text-mid);}
.btn-ghost:hover{color:var(--text-hi);}
.btn-block{width:100%;}
.btn:disabled{opacity:.45;cursor:not-allowed;}

/* pills / status badges */
.pill{display:inline-block;padding:4px 11px;border-radius:999px;font-size:11.5px;font-weight:700;letter-spacing:0.01em;}
.pill-pending{background:rgba(255,122,61,.14);color:var(--spark-orange);border:1px solid rgba(255,122,61,.3);}
.pill-success{background:rgba(232,93,46,.14);color:#C24A1E;border:1px solid rgba(232,93,46,.3);}
.pill-muted{background:rgba(58,36,24,.05);color:var(--text-lo);border:1px solid var(--line);}

/* forms */
.field{display:flex;flex-direction:column;gap:7px;margin-bottom:18px;}
.field label{font-size:13px;font-weight:600;color:var(--text-mid);}
.field input, .field select, .field textarea{
  padding:12px 14px;border-radius:10px;border:1px solid var(--line);
  background:rgba(58,36,24,.02);color:var(--text-hi);
  font-family:var(--font-body);font-size:14.5px;width:100%;
}
.field input:focus, .field select:focus, .field textarea:focus{outline:none;border-color:var(--spark-orange);}
.field textarea{resize:vertical;min-height:88px;}
.field-error{color:#C24A1E;font-size:12px;}
.form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
@media (max-width:520px){.form-row-2{grid-template-columns:1fr;}}

/* auth card */
.mv-auth-card{
  border:1px solid var(--line);background:var(--card);border-radius:var(--radius-lg);
  padding:36px 30px;position:relative;z-index:1;
  box-shadow:0 20px 44px -20px rgba(58,36,24,.2);
}
.mv-auth-logo{
  font-family:var(--font-display);font-weight:800;font-size:15px;letter-spacing:0.14em;
  color:var(--text-lo);text-align:center;margin-bottom:18px;text-transform:uppercase;
}
.mv-form-title{font-family:var(--font-display);font-weight:800;font-size:21px;margin:0 0 8px;text-align:center;}
.mv-form-sub{color:var(--text-mid);font-size:13.5px;text-align:center;margin:0 0 26px;line-height:1.6;}

.mv-alert{padding:10px 14px;border-radius:10px;font-size:13px;margin-bottom:18px;}
.mv-alert-success{background:rgba(255,151,112,.16);color:#B8501F;border:1px solid rgba(255,151,112,.35);}
.back-link{display:inline-flex;align-items:center;gap:4px;font-size:12.5px;color:var(--text-lo);
    margin-bottom:16px;cursor:pointer;background:none;border:none;font-family:var(--font-body);text-decoration:none;}
.back-link:hover{color:var(--text-hi);}

/* step indicator */
.steps-bar{display:flex;gap:6px;margin-bottom:26px;}
.step-dot{flex:1;height:4px;border-radius:2px;background:var(--line);}
.step-dot.on{background:linear-gradient(95deg,var(--spark-orange),var(--spark-pink));}

/* event picker */
.event-pick{
  width:100%;text-align:left;border:1px solid var(--line);background:var(--card);
  border-radius:var(--radius-md);padding:16px 18px;margin-bottom:10px;cursor:pointer;
  transition:border-color .2s, background .2s;font-family:var(--font-body);
}
.event-pick:hover{border-color:rgba(58,36,24,.22);background:rgba(58,36,24,.03);}
.event-pick .cat{font-weight:700;font-size:14.5px;margin-bottom:3px;}
.event-pick .meta{font-size:12.5px;color:var(--text-mid);}

/* photo preview */
.photo-preview{width:88px;height:88px;object-fit:cover;border-radius:12px;border:1px solid var(--line);margin-top:8px;}

/* checkbox rows */
.check-row{display:flex;align-items:center;gap:10px;font-size:14px;color:var(--text-hi);padding:9px 0;}
.check-row input[type=checkbox]{width:17px;height:17px;accent-color:var(--spark-orange);}

.mv-card-block{border:1px solid var(--line);background:var(--card);border-radius:var(--radius-md);padding:20px;margin-bottom:14px;
  box-shadow:0 8px 20px -14px rgba(58,36,24,.14);}
.mv-card-block .head{font-weight:700;font-size:15.5px;margin-bottom:4px;}
.mv-card-block .sub{font-size:12.5px;color:var(--text-mid);margin-bottom:14px;}
.mv-divider{border-top:1px solid var(--line);margin:14px 0;padding-top:14px;}

.tab-btn{
    padding:11px 14px;border-radius:12px;border:1px solid var(--line);
    background:rgba(58,36,24,.02);color:var(--text-mid);font-weight:700;font-size:13.5px;
    cursor:pointer;transition:all .15s;font-family:var(--font-body);
}
.tab-btn:hover{background:rgba(58,36,24,.05);color:var(--text-hi);}
.tab-btn.active{
    background:linear-gradient(95deg,var(--spark-orange),var(--spark-pink));
    color:#fff;border-color:transparent;box-shadow:0 6px 16px -6px rgba(255,151,112,.5);
}

    </style>
</head>
<body>
<div class="mv-ambient"></div>

<header class="mv-nav">
    <div class="mv-nav-inner">
        <a href="/" class="mv-logo">
            <img class="mv-logo-mark" src="/images/logo_simbol.png" alt="MEEWV">MEEWV
        </a>
        @auth
            <div class="mv-user">{{ auth()->user()->name }}</div>
        @endauth
    </div>
</header>

<main class="mv-page-wrap mv-page-narrow">
    {{ $slot }}
</main>

@livewireScripts

<script>
    document.addEventListener("livewire:init", () => {
        if (typeof Livewire.onPageExpired === "function") {
            Livewire.onPageExpired(() => {
                if (confirm("세션이 만료됐어요. 페이지를 새로고침할까요?")) {
                    window.location.reload();
                }
            });
        }
    });
</script>

</body>
</html>