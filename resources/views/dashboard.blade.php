<x-layout>
<div class="flex flex-col items-center"> {{--main div--}}
    <section> {{--section for page heading--}}
        <x-page-heading>Dashboard</x-page-heading>
        <div class="h-1 w-32 bg-white/60 mb-4"></div>
    </section>

    <section> {{--section for main timer--}}
        <form action="/sessions/start" method="POST" id="timerForm">
            <input type="hidden" name="_method" id="_method" value="">
            @csrf
            <div class="flex flex-col relative bg-red-400 rounded-xl p-6 border border-white/60 w-lg h-80 mb-2">
                <div class="self-center space-x-24 items-center">
                    <button class="presetBtn cursor-pointer" data-type="work" data-duration-minutes="{{$defaultSettings -> work_minutes}}" type="button">
                        Work
                    </button>
                    <button class="presetBtn cursor-pointer" data-type="short_break" data-duration-minutes="{{$defaultSettings -> short_break_minutes}}" type="button">
                        Short Break
                    </button>
                    <button class="presetBtn cursor-pointer" data-type="long_break" data-duration-minutes="{{$defaultSettings -> long_break_minutes}}" type="button">
                        Long Break
                    </button>
                </div>

                <div id="timerDisplay" class="text-center text-white font-bold text-8xl absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    25:00
                </div>

                <input type="hidden" name="session_id" id="session_id" value="{{ $activeSession->id ?? '' }}">
                <input type="hidden" name="session_status" id="session_status" value="{{ $activeSession->status ?? '' }}">
                <input type="hidden" name="elapsed_seconds" id="elapsed_seconds" value="{{ $activeSession->elapsed_seconds ?? '' }}">
                <input type="hidden" name="type" id="type" value="{{$activeSession->type ?? 'work'}}"/>
                <input type="hidden" name="duration_minutes" id="duration_minutes" value="{{$activeSession->duration_minutes ?? '25'}}">

                <div class="flex flex-row justify-between mt-auto">
                    <button class="mx-auto cursor-pointer" id="startPauseBtn" type="button"> Start </button>
                    <button class="self-end cursor-pointer" id="resetBtn" type="button"> Reset </button>
                </div>
            </div>
        </form>
    </section>

    <section class="mb-2"> {{--section for today's recent sessions--}}
        <div class="w-lg mx-auto text-center">
            <x-section-heading class="mb-2"> Today's Sessions </x-section-heading>

            <div class="grid grid-cols-4 gap-4 w-lg">
                <div class="font-semibold"> Time </div>
                <div class="font-semibold"> Type </div>
                <div class="font-semibold"> Duration </div>
                <div class="font-semibold"> Status </div>

                @foreach ($recentRecords as $record)
                    <div>{{ $record -> started_at -> format('H:i A')}}</div>
{{--Pure PHP Method:<div>{{ ucwords(str_replace('_',' ',$record->type)) }}</div>--}}
                    <div>{{ Str::title(str_replace('_',' ',$record->type)) }}</div>
                    <div>{{ $record -> duration_minutes }}</div>
                    <div>{{ $record -> status }}</div>
                @endforeach
            </div>
        </div>
    </section>
</div>
</x-layout>
