<x-layout>
<div class="flex flex-col items-center"> {{--main div--}}
    <section> {{--section for page heading--}}
        <x-page-heading>Dashboard</x-page-heading>
        <div class="h-1 w-32 bg-white/60 mb-4"></div>
    </section>

    <section> {{--section for main timer--}}
        <form action="/sessions" method="POST">
            @csrf
            <div class="flex flex-col relative bg-red-400 rounded-xl p-6 border border-white/60 w-lg h-80 mb-2">
                <div class="self-center space-x-24 items-center">
                    <button class="presetBtn" data-type="work" data-duration-minutes="{{$defaultSettings -> work_minutes}}" type="button">
                        Work
                    </button>
                    <button class="presetBtn" data-type="short_break" data-duration-minutes="{{$defaultSettings -> short_break_minutes}}" type="button">
                        Short Break
                    </button>
                    <button class="presetBtn" data-type="long_break" data-duration-minutes="{{$defaultSettings -> long_break_minutes}}" type="button">
                        Long Break
                    </button>
                </div>

                <div id="timerDisplay" class="text-center text-white font-bold text-8xl absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    25:00
                </div>

                <input type="hidden" name="type" id="type" value="work"/>
                <div class="flex flex-row justify-between mt-auto">
                    <button class="mx-auto" id="startPauseBtn" type="button"> Start </button>
                    <button class="self-end" id="resetBtn" type="button"> Reset </button>
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('[data-type]');
            const hiddenInput = document.getElementById('type');
            const defaultType = hiddenInput.value;
            const defaultBtn = document.querySelector(`[data-type="${defaultType}"]`);
            let activeBtnDuration = defaultBtn ? parseInt(defaultBtn.dataset.durationMinutes,10): 25;

            defaultBtn.classList.add('bg-white','text-black');

            //Timer State
            const state = {
                sessionMs: activeBtnDuration * 60 * 1000,  //25 minutes in ms
                remainingMs: activeBtnDuration * 60 * 1000, //start at full session
                isRunning: false,
                intervalId: null
            };


            buttons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault(); //stop it from submitting the form on clicking the type buttons

                    //update hidden input
                    hiddenInput.value = button.getAttribute('data-type');
                    //update button duration and state objects accordingly
                    const minutes = parseInt(button.dataset.durationMinutes, 10) || 25;
                    state.sessionMs = minutes * 60 * 1000;
                    state.remainingMs = state.sessionMs;
                    resetTimer(); //stops the timer, sets UI to start and shows full session (remainingMs)

                    //toggle active class
                    buttons.forEach(button => button.classList.remove('bg-white','text-black'));
                    button.classList.add('bg-white','text-black');

                    render();
                })
            })


            //convert ms to proper format HH:MM
            function formatTime(ms) {
                const totalSeconds = Math.floor(ms/1000); //drops ms
                const minutes = Math.floor(totalSeconds/60); //whole minutes
                const seconds = totalSeconds % 60; //leftover seconds
                return `${minutes}:${seconds.toString().padStart(2,'0')}`;
            }

            //
            function render() {
                const display = document.getElementById('timerDisplay');
                display.textContent = formatTime(state.remainingMs);
            }

            render();

            let lastTick = null;

            function tick() {
                const now = Date.now();
                const delta = now - lastTick; //how much real time has passed
                state.remainingMs = state.remainingMs - delta; //subtract the real time from our remaining time
                lastTick = now; //update lastTick baseline for next tick

                if(state.remainingMs <=0) {
                    finishTimer();
                } else{
                    render();
                }
            }

            function startTimer() {
                if(state.intervalId) return; //don't start clock iff intervalId already exists ie clock is already running

                state.isRunning = true;
                startPauseBtn.textContent = "Pause";

                lastTick = Date.now();  //set baseline for calculation of delta in tick()
                tick();     //do an immediate update
                state.intervalId = setInterval(tick, 300);  //repeat tick() every 300ms
            }

            function pauseTimer() {
                if(state.intervalId) {
                    clearInterval(state.intervalId);
                    state.intervalId = null;
                }

                state.isRunning = false;
                startPauseBtn.textContent = "Start";
                lastTick = null;        //reset baseline
                render();       //redraw current value
            }

            function finishTimer()
            {
                state.remainingMs = 0; //set it to zero

                if(state.intervalId) {
                    clearInterval(state.intervalId); //stop loop
                    state.intervalId = null;
                }

                state.isRunning = false;
                startPauseBtn.textContent = "Start";
                render();
            }

            function resetTimer()
            {
                state.remainingMs = state.sessionMs; //reset to full session length

                if(state.intervalId) {
                    clearInterval(state.intervalId);
                    state.intervalId = null;
                }

                lastTick = null;
                state.isRunning = false;
                startPauseBtn.textContent = "Start";
                render();
            }

            const startPauseBtn = document.getElementById('startPauseBtn');

            startPauseBtn.addEventListener('click', () => {
                if(state.isRunning) {
                    //going from running to paused
                    pauseTimer();
                } else {
                    //going from paused to running
                    startTimer();
                }
            });

            const resetBtn = document.getElementById('resetBtn');
            resetBtn.addEventListener('click', resetTimer);
        })
    </script>
</x-layout>
