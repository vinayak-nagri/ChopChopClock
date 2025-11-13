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
<body class="bg-black text-white pb-10 min-h-screen flex flex-col">

{{-- Main Div --}}
<div class="px-10 flex-grow">
    {{--Nav Bar--}}
    <nav class="flex justify-between items-center py-4 border-b border-white/60 font-bold">
        {{--Div for Logo--}}
        <div>
            <a href="/">
                <img src="{{ asset('images/img.png') }}" alt="" height="100" width="100"/>
            </a>
        </div>

        @auth()
        <div class="space-x-6">
            <x-nav-link href="/dashboard">Dashboard</x-nav-link>
            <x-nav-link href="/history">History</x-nav-link>
            <x-nav-link href="/settings">Settings</x-nav-link>
        </div>

        <form method="POST" action="/logout">
            @csrf
            @method('DELETE')
            <button class="cursor-pointer">Log Out</button>
        </form>
        @endauth

        @guest
        <div class="space-x-6">
            <a href="/register">Sign Up</a>
            <a href="/login">Log In</a>
        </div>
        @endguest
    </nav>

    <main class="mt-10 max-w-[986px] mx-auto">
        {{ $slot }}
    </main>

</div>
<footer class="px-10 mb-auto mt-2">
    <div class="border-t border-white/60"></div>
    <div class="flex justify-center items-center mt-3 ">
        © ChopChopClock
    </div>
    {{--Space for Social Media Buttons--}}
    {{--Space for Trademark--}}
</footer>
</body>
</html>
