<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <meta name="referrer" content="no-referrer">
    <title>Glücksrad | Regulierungs-CHECK</title>
    <x-favicon-links />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        :root { --promotion-ink:#082f35; --promotion-teal:#0d9187; --promotion-gold:#f4c95d; --promotion-mist:#eef7f5; }
        .promotion-shell { background-color:var(--promotion-mist); background-image:radial-gradient(circle at 10% 8%,rgba(13,145,135,.12),transparent 28rem),radial-gradient(circle at 92% 4%,rgba(244,201,93,.17),transparent 24rem),linear-gradient(rgba(255,255,255,.35) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.35) 1px,transparent 1px); background-size:auto,auto,32px 32px,32px 32px; }
        .promotion-enter { animation:promotion-enter .65s cubic-bezier(.22,1,.36,1) both; }
        .promotion-enter-delayed { animation:promotion-enter .65s .12s cubic-bezier(.22,1,.36,1) both; }
        .promotion-bezel { position:relative; isolation:isolate; }
        .promotion-bezel::before { content:""; position:absolute; inset:-7px; z-index:-1; border:1px solid rgba(255,255,255,.9); border-radius:2.4rem; background:rgba(255,255,255,.36); box-shadow:0 26px 75px -42px rgba(8,47,53,.5); }
        .promotion-status-orbit { animation:promotion-orbit 12s linear infinite; }
        .promotion-status-pulse { animation:promotion-pulse 2.6s cubic-bezier(.22,1,.36,1) infinite; }
        @keyframes promotion-enter { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:translateY(0); } }
        @keyframes promotion-orbit { to { transform:rotate(360deg); } }
        @keyframes promotion-pulse { 0%,100% { transform:scale(1); box-shadow:0 0 0 0 rgba(244,201,93,.28); } 50% { transform:scale(1.035); box-shadow:0 0 0 14px rgba(244,201,93,0); } }
        @media (prefers-reduced-motion:reduce) { .promotion-enter,.promotion-enter-delayed,.promotion-status-orbit,.promotion-status-pulse { animation:none!important; } }
    </style>
</head>
<body class="promotion-shell min-h-[100dvh] text-slate-950 antialiased">
    {{ $slot }}
    @livewireScripts
</body>
</html>
