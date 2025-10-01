<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChopChopClock</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-black text-white pb-20 min-h-screen flex flex-col">

{{-- Main Div --}}
<div class="px-10 flex-grow">
    {{--Nav Bar--}}
    <nav class="flex justify-between items-center py-4 border-b border-white/60 font-bold">
        {{--Div for Logo--}}
        <div>
            <a href="/">
                <img src="{{ Vite::asset('resources/images/img.png') }}" alt="" height="100" width="100"/>
            </a>
        </div>

        @auth()
        <div class="space-x-6">
            <a href="#">Dashboard</a>
            <a href="#">History</a>
            <a href="#">Settings</a>
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
    {{--Space for Social Media Buttons--}}
    {{--Space for Trademark--}}
</footer>
</body>
</html>
