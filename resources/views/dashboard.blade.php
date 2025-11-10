<x-layout>
<div class="flex flex-col items-center"> {{--main div--}}
    <section> {{--section for page heading--}}
        <x-page-heading>Dashboard</x-page-heading>
        <div class="h-1 w-32 bg-white/60 mb-4"></div>
    </section>

    <section> {{--section for main timer--}}
        <form action="/sessions/start" method="POST" id="timerForm">
            @csrf
            <div class="flex flex-col relative bg-rose-700/90 shadow-lg shadow-red-900 backdrop-blur-sm rounded-xl p-6 border border-white/60 w-xl h-90 mb-2 transition hover:scale-[1.01] hover:shadow-2xl">
                <div class="flex-row self-center space-x-16 items-center justify-center">
                    <button class="presetBtn preset-btn-base" data-type="work" data-duration-minutes="{{$defaultSettings -> work_minutes}}" type="button">
                        Work
                    </button>
                    <button class="presetBtn preset-btn-base" data-type="short_break" data-duration-minutes="{{$defaultSettings -> short_break_minutes}}" type="button">
                        Short Break
                    </button>
                    <button class="presetBtn preset-btn-base" data-type="long_break" data-duration-minutes="{{$defaultSettings -> long_break_minutes}}" type="button">
                        Long Break
                    </button>
                </div>

                <div id="timerDisplay" class="text-center text-white font-bold text-9xl absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    25:00
                </div>

                <input type="hidden" name="session_id" id="session_id" value="{{ $activeSession->id ?? '' }}">
                <input type="hidden" name="session_status" id="session_status" value="{{ $activeSession->status ?? '' }}">
                <input type="hidden" name="elapsed_seconds" id="elapsed_seconds" value="{{ $activeSession->elapsed_seconds ?? '' }}">
                <input type="hidden" name="type" id="type" value="{{$activeSession->type ?? 'work'}}"/>
                <input type="hidden" name="duration_minutes" id="duration_minutes" value="{{$activeSession->duration_minutes ?? '25'}}">

                <div class="flex flex-row justify-between mt-auto">
                    <button class="mx-auto cursor-pointer inline-flex items-center justify-center px-4 py-2 rounded-sm
                                   text-2xl w-56 text-rose-600 font-semibold
                                 bg-white border-solid border-1 border-white/50 tracking-wide
                                   transition transform duration-150 ease-out hover:scale-105
                                   active:scale-95 active:shadow-[0_2px_10px_rgba(0,0,0,0.3)]
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/30
                                   select-none disabled:opacity-50 disabled:cursor-not-allowed" id="startPauseBtn" type="button"> Start </button>
                    <button class="self-end cursor-pointer inline-flex items-center justify-center px-4 py-2 rounded-sm w-15
                                 bg-white/20 border-solid border-1 border-white/30 text-white/70 hover:text-white tracking-wide
                                   transition transform duration-150 ease-out hover:scale-105
                                   active:scale-95 active:shadow-[0_2px_10px_rgba(0,0,0,0.3)]
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/30
                                   select-none disabled:opacity-50 disabled:cursor-not-allowed" id="resetBtn" type="button"> Reset </button>
                </div>
            </div>
        </form>
    </section>

    <section class="mb-2 mt-4"> {{--section for today's recent sessions--}}
        <div class="w-lg mx-auto text-center">
            <x-section-heading class="mb-2"> Today's Sessions </x-section-heading>

{{--            <div class="grid grid-cols-4 gap-4 w-lg">--}}
{{--                <div class="font-semibold"> Time </div>--}}
{{--                <div class="font-semibold"> Type </div>--}}
{{--                <div class="font-semibold"> Duration </div>--}}
{{--                <div class="font-semibold"> Status </div>--}}

{{--                @foreach ($recentRecords as $record)--}}
{{--                    <div>{{ $record -> started_at -> format('h:i A')}}</div>--}}
{{--Pure PHP Method:<div>{{ ucwords(str_replace('_',' ',$record->type)) }}</div>--}}
{{--                    <div>{{ Str::title(str_replace('_',' ',$record->type)) }}</div>--}}
{{--                    <div>{{ $record -> duration_minutes }}</div>--}}
{{--                    <div>{{ $record -> status }}</div>--}}
{{--                @endforeach--}}
{{--            </div>--}}

            <div class="flex flex-row justify-center space-x-6 ">
                <div class="card">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="w-7 h-7 text-gray-500 dark:text-white mb-3 lucide lucide-target-icon lucide-target"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                    <div class="flex flex-col">
                        <h5 class="mb-2 text-xl font-semibold tracking-tight text-gray-900 dark:text-white">Work Sessions</h5>
                        <p class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">{{$countWorkSessions}}</p>
                    </div>
                </div>

                <div class="card">
                    <svg class="w-7 h-7 text-gray-500 dark:text-white mb-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                    </svg>
                    <div class="flex flex-col">
                        <h5 class="mb-2 text-xl font-semibold tracking-tight text-gray-900 dark:text-white">Total Hours</h5>
                        <p class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">{{$formattedTotal}}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</x-layout>
