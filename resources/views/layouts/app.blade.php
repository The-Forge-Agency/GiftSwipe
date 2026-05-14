<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'GiftSwipe — Swipe, vote, offrez.')</title>

    <meta property="og:title" content="@yield('title', 'GiftSwipe — Swipe, vote, offrez.')">
    <meta property="og:description" content="Le cadeau de groupe parfait — sans la galère.">
    <meta property="og:image" content="{{ asset('images/logo-square.svg') }}">
    <meta property="og:type" content="website">

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bg text-ink font-body min-h-screen flex flex-col">

    <header class="flex items-center justify-between px-6 py-4">
        <a href="/">
            <img src="{{ asset('images/logo-horizontal.svg') }}" alt="GiftSwipe" class="h-9">
        </a>
        @if(request()->cookie('giftswipe_owner_token'))
            <a href="{{ route('my-spaces') }}" class="text-sm font-medium text-accent hover:underline">
                Mes espaces
            </a>
        @endif
    </header>

    <main class="flex-1 flex flex-col">
        @yield('content')
    </main>

    <footer class="py-6 text-center text-xs text-ink-alt">
        Giftswipe — App #02/52 par Sprint Factory
    </footer>

    @stack('scripts')
</body>
</html>
