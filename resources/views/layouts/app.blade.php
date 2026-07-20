<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'MEEWV' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white border-b px-6 py-4 flex items-center justify-between">
        <a href="/" class="font-bold text-lg">MEEWV</a>
        <div class="flex gap-4 text-sm">
            <a href="/admin/attendees" class="text-gray-600 hover:text-black">신청 승인</a>
            <a href="/admin/checkin" class="text-gray-600 hover:text-black">체크인</a>
            @auth
                <span class="text-gray-400">{{ auth()->user()->name }}</span>
            @endauth
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
