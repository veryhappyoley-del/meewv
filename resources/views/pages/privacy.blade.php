<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::marketing', ['title' => '개인정보 수집·이용 동의 · MEEWV'])] class extends Component
{
}; ?>

<div>
    <style>
        .privacy-wrap{max-width:720px;margin:0 auto;padding:80px 24px 100px;position:relative;z-index:1;}
        .privacy-wrap h1{font-family:var(--font-display);font-weight:800;font-size:clamp(26px,3.5vw,34px);margin:0 0 12px;}
        .privacy-wrap .updated{color:var(--text-lo);font-size:13px;margin-bottom:44px;}
        .privacy-wrap h2{font-family:var(--font-display);font-weight:700;font-size:18px;margin:36px 0 12px;color:var(--text-hi);}
        .privacy-wrap p, .privacy-wrap li{color:var(--text-mid);font-size:14.5px;line-height:1.8;}
        .privacy-wrap ul{padding-left:20px;margin:8px 0;}
        .privacy-wrap table{width:100%;border-collapse:collapse;margin:14px 0;font-size:13.5px;}
        .privacy-wrap th, .privacy-wrap td{border:1px solid var(--line);padding:10px 14px;text-align:left;color:var(--text-mid);}
        .privacy-wrap th{background:var(--card);color:var(--text-hi);font-weight:700;}
        .privacy-highlight{background:rgba(255,138,61,.08);border:1px solid rgba(255,138,61,.25);border-radius:12px;padding:16px 18px;margin:16px 0;color:var(--text-hi);font-size:14px;}
    </style>

    <div class="privacy-wrap">
        <h1>개인정보 수집·이용 동의</h1>
        <p class="updated">최종 개정일 2026년 7월 20일</p>

        <p>MEEWV(이하 "회사")는 모임 참가 신청 과정에서 아래와 같이 개인정보를 수집·이용합니다. 신청자는 내용을 확인하신 후 동의 여부를 선택하실 수 있습니다.</p>

        <h2>1. 수집하는 개인정보 항목</h2>
        <table>
            <tr><th>구분</th><th>항목</th></tr>
            <tr><td>필수</td><td>이름, 생년월일, 성별, 전화번호, 사진, 소개글</td></tr>
            <tr><td>선택</td><td>직업, 인스타그램 계정, 취미 및 관심사</td></tr>
        </table>

        <h2>2. 개인정보의 수집 및 이용 목적</h2>
        <ul>
            <li>모임 참가자 본인 확인 및 신원 확인</li>
            <li>성비·연령대를 고려한 자리(테이블) 배정</li>
            <li>원활하고 안전한 모임 운영</li>
            <li>참가자 간 시그널(매칭) 서비스 제공</li>
            <li>참가 승인 여부 및 모임 안내 전달(카카오톡 알림 등)</li>
        </ul>

        <h2>3. 개인정보의 보유 및 이용 기간</h2>
        <div class="privacy-highlight">
            수집된 개인정보는 원칙적으로 <strong>해당 모임 종료일로부터 2주 이내에 파기</strong>됩니다.
        </div>
        <p>단, 결제·환불과 관련된 기록은 전자상거래 등에서의 소비자보호에 관한 법률 등 관계 법령에 따라 일정 기간 별도로 보관될 수 있습니다.</p>

        <h2>4. 개인정보의 제3자 제공</h2>
        <p>회사는 이용자의 개인정보를 원칙적으로 외부에 제공하지 않습니다. 다만 시그널(매칭) 기능을 통해 상대방이 직접 공개를 선택한 정보에 한해, 매칭이 성사된 상대방에게 제공될 수 있습니다.</p>

        <h2>5. 동의 거부 권리 및 불이익</h2>
        <p>이용자는 개인정보 수집·이용에 대한 동의를 거부할 권리가 있습니다. 다만, 신원 확인이 필요한 서비스 특성상 동의하지 않으실 경우 모임 참가 신청이 제한될 수 있습니다.</p>

        <h2>6. 문의처</h2>
        <p>개인정보 관련 문의사항은 사이트 하단의 고객센터 정보로 연락해주세요.</p>
    </div>
</div>
