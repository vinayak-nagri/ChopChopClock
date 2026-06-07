<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title ?? 'ChopChopClock'}}</title>
    <script src="https://cdn.jsdelivr.net/npm/howler@2.2.4/dist/howler.min.js"></script>
    {{--Remove the arrow buttons from Number text fields in form inputs--}}
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="app-shell min-h-screen text-white antialiased">
<div class="min-h-screen flex flex-col">
    <header class="px-4 pt-4 sm:px-6 sm:pt-5">
        <nav class="navbar-shell mx-auto w-full max-w-[1600px] font-semibold">
            <a href="/" class="shrink-0" aria-label="ChopChopClock home">
                <img src="{{ asset('images/chopchopclock-logo.png') }}" alt="ChopChopClock" class="h-auto w-36 sm:w-44"/>
            </a>

        @auth()
            @if(auth()->user()->hasVerifiedEmail())
            <div class="navbar-links flex items-center justify-center gap-6 sm:gap-12">
                <x-nav-link href="/dashboard">Dashboard</x-nav-link>
                <x-nav-link href="/history">History</x-nav-link>
                <x-nav-link href="/settings">Settings</x-nav-link>
            </div>
            @else
            <div class="navbar-links flex items-center justify-center">
                <x-nav-link href="{{ route('verification.notice') }}">Verify Email</x-nav-link>
            </div>
            @endif

        <form method="POST" action="/logout" class="navbar-actions shrink-0">
            @csrf
            @method('DELETE')
            <button class="navbar-logout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                    <path d="M10 17l5-5-5-5M15 12H3"/>
                    <path d="M14 4h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4"/>
                </svg>
                Log Out
            </button>
        </form>
        @endauth

        @guest
        <div class="navbar-actions flex items-center gap-3 sm:gap-5">
            <a href="/login" class="text-sm text-slate-200 transition hover:text-white sm:text-base">Log In</a>
            <a href="/register" class="rounded-lg border border-blue-400/40 bg-gradient-to-r from-blue-600 to-fuchsia-600 px-4 py-2 text-sm text-white shadow-lg shadow-blue-600/25 transition hover:brightness-110 sm:px-5 sm:text-base">Sign Up</a>
        </div>
        @endguest
        </nav>
    </header>

    <main class="mx-auto w-full max-w-[1440px] flex-1 px-5 py-10 sm:px-8 lg:px-12 lg:py-12">
        {{ $slot }}
    </main>

    <footer class="mx-auto w-full max-w-[1440px] px-5 pb-8 sm:px-8 lg:px-12">
        <div class="border-t border-slate-700/60 pt-6 text-center text-sm text-slate-400">
            © ChopChopClock
        </div>
    </footer>
</div>
</body>
</html>
