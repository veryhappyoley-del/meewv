<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>접근 권한 없음 · MEEWV</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,700;12..96,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/variable/pretendardvariable.css" />
    <style>
        :root{
            --void-1:#0a0712; --void-2:#150c24;
            --spark-orange:#ff8a3d; --spark-pink:#ff3e7f; --spark-violet:#8b5cf6;
            --text-hi:#f6f2fb; --text-mid:#c7bcdb; --text-lo:#8d81a6;
            --line:rgba(246,242,251,0.10); --card:rgba(246,242,251,0.045);
            --font-display:'Bricolage Grotesque','Pretendard Variable',Pretendard,sans-serif;
            --font-body:'Pretendard Variable',Pretendard,-apple-system,sans-serif;
        }
        *{box-sizing:border-box;}
        html,body{margin:0;padding:0;height:100%;}
        body{
            background:var(--void-1);color:var(--text-hi);font-family:var(--font-body);
            min-height:100vh;display:flex;align-items:center;justify-content:center;
            padding:24px;-webkit-font-smoothing:antialiased;position:relative;overflow:hidden;
        }
        a{color:inherit;text-decoration:none;}
        .ambient{
            position:fixed;inset:0;z-index:0;pointer-events:none;
            background:radial-gradient(ellipse 700px 500px at 15% 10%, rgba(255,62,127,0.16), transparent 60%),
                       radial-gradient(ellipse 600px 500px at 85% 90%, rgba(139,92,246,0.16), transparent 60%),
                       linear-gradient(180deg, var(--void-1) 0%, var(--void-2) 60%, var(--void-1) 100%);
        }
        .card{
            position:relative;z-index:1;max-width:440px;width:100%;text-align:center;
            border:1px solid var(--line);background:var(--card);border-radius:24px;padding:48px 36px;
        }
        .logo{
            display:inline-flex;align-items:center;gap:8px;font-family:var(--font-display);
            font-weight:800;font-size:15px;color:var(--text-lo);letter-spacing:0.08em;margin-bottom:32px;
        }
        .logo-mark{width:16px;height:16px;}
        .code{
            font-family:var(--font-display);font-weight:800;font-size:72px;line-height:1;
            background:linear-gradient(95deg,var(--spark-orange),var(--spark-pink),var(--spark-violet));
            -webkit-background-clip:text;background-clip:text;color:transparent;margin-bottom:8px;
        }
        h1{font-family:var(--font-display);font-weight:800;font-size:19px;margin:0 0 10px;}
        p{color:var(--text-mid);font-size:14px;line-height:1.65;margin:0 0 30px;}
        .btn{
            display:inline-flex;align-items:center;justify-content:center;gap:6px;
            padding:12px 26px;border-radius:999px;font-weight:700;font-size:14px;
            background:linear-gradient(95deg,var(--spark-orange),var(--spark-pink));color:var(--void-1);
            transition:transform .15s, box-shadow .2s;
        }
        .btn:hover{transform:translateY(-1px);box-shadow:0 8px 22px -6px rgba(255,62,127,.55);}
    </style>
</head>
<body>
    <div class="ambient"></div>
    <div class="card">
        <div class="logo">
            <svg class="logo-mark" viewBox="0 0 24 24" fill="none"><path d="M12 0 L14.6 9.4 L24 12 L14.6 14.6 L12 24 L9.4 14.6 L0 12 L9.4 9.4 Z" fill="url(#g1)"/><defs><linearGradient id="g1" x1="0" y1="0" x2="24" y2="24"><stop stop-color="#ff8a3d"/><stop offset="1" stop-color="#ff3e7f"/></linearGradient></defs></svg>
            MEEWV ADMIN
        </div>
        <div class="code">403</div>
        <h1>접근 권한이 없어요</h1>
        <p>{{ $exception->getMessage() ?: '관리자만 접근할 수 있는 페이지예요.' }}</p>
        <a href="/admin/login" class="btn">관리자 로그인으로 이동</a>
    </div>
</body>
</html>