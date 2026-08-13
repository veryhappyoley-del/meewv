<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>MEEWV 입장 확인</title>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/variable/pretendardvariable.css" />
    <style>
        :root{
            --peach-bg:#FFF3E9; --peach-orange:#FF7A3D; --salmon:#FF9770; --peach-text:#3A2418; --peach-text-mid:#8A6A56;
            --font-display:'Bricolage Grotesque','Pretendard Variable',sans-serif;
            --font-body:'Pretendard Variable',-apple-system,sans-serif;
        }
        *{box-sizing:border-box;}
        html,body{margin:0;padding:0;height:100%;}
        body{
            font-family:var(--font-body);
            display:flex;align-items:center;justify-content:center;
            min-height:100vh;padding:24px;
        }
        .scan-card{
            width:100%;max-width:480px;border-radius:32px;padding:52px 36px;text-align:center;
            box-shadow:0 24px 60px -20px rgba(0,0,0,.25);
        }
        .scan-icon{width:88px;height:88px;border-radius:50%;margin:0 auto 26px;
            display:flex;align-items:center;justify-content:center;font-size:44px;}
        .scan-title{font-family:var(--font-display);font-weight:800;font-size:30px;margin:0 0 14px;line-height:1.3;}
        .scan-sub{font-size:16px;line-height:1.7;margin:0;}
        .scan-meta{margin-top:26px;font-size:13.5px;padding-top:20px;border-top:1px solid rgba(0,0,0,.08);}

        .state-already{background:#FFF1EC;}
        .state-already .scan-icon{background:#FFDCC9;color:#C4471C;}
        .state-already .scan-title{color:#C4471C;}
        .state-already .scan-sub{color:#8A4A2E;}

        .state-welcome{background:linear-gradient(160deg,var(--peach-bg),#FFE1C6);}
        .state-welcome .scan-icon{background:linear-gradient(135deg,var(--peach-orange),var(--salmon));color:#fff;}
        .state-welcome .scan-title{color:var(--peach-text);}
        .state-welcome .scan-sub{color:var(--peach-text-mid);}

        .state-not_approved{background:#F4F1EC;}
        .state-not_approved .scan-icon{background:#E4DCCB;color:#8A6A45;}
        .state-not_approved .scan-title{color:#5C4A2E;}
        .state-not_approved .scan-sub{color:#8A7A5E;}

        .state-invalid{background:#F2F2F2;}
        .state-invalid .scan-icon{background:#DDDDDD;color:#666;}
        .state-invalid .scan-title{color:#444;}
        .state-invalid .scan-sub{color:#777;}

        .badge-no{font-family:var(--font-display);font-weight:800;font-size:15px;
            background:rgba(255,122,61,.16);color:var(--peach-orange);
            padding:5px 16px;border-radius:999px;display:inline-block;margin-top:18px;}
    </style>
</head>
<body class="state-{{ $state }}">

    @if ($state === 'welcome')
        <div class="scan-card state-welcome">
            <div class="scan-icon">🎉</div>
            <h1 class="scan-title">환영합니다,<br>{{ $attendee->user->nickname ?: $attendee->user->name }}님!</h1>
            <p class="scan-sub">로비로 가서 명찰을 지급받고<br>자리에 착석해주세요~</p>
            <div class="badge-no">{{ $attendee->badge_no }}번</div>
            <div class="scan-meta">{{ $attendee->event->location?->name }} · {{ $attendee->event->event_date }}</div>
        </div>
    @elseif ($state === 'already')
        <div class="scan-card state-already">
            <div class="scan-icon">⚠️</div>
            <h1 class="scan-title">이미 입장했습니다!</h1>
            <p class="scan-sub">{{ $attendee->user->nickname ?: $attendee->user->name }}님은 이미 체크인 처리가 완료됐어요.<br>중복 입장이 의심되면 스태프에게 알려주세요.</p>
            <div class="badge-no">{{ $attendee->badge_no }}번</div>
            <div class="scan-meta">입장 시각: {{ $attendee->checked_in_at?->format('H:i') }}</div>
        </div>
    @elseif ($state === 'not_approved')
        <div class="scan-card state-not_approved">
            <div class="scan-icon">⏳</div>
            <h1 class="scan-title">아직 승인 전이에요</h1>
            <p class="scan-sub">신청은 됐지만 아직 승인이 완료되지 않았어요.<br>스태프에게 회원코드를 알려주고 확인을 받아주세요.</p>
        </div>
    @else
        <div class="scan-card state-invalid">
            <div class="scan-icon">❌</div>
            <h1 class="scan-title">유효하지 않은 코드예요</h1>
            <p class="scan-sub">QR코드를 다시 확인해주시거나, 스태프에게 문의해주세요.</p>
        </div>
    @endif

</body>
</html>