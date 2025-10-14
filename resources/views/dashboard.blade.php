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

                <input type="hidden" name="type" id="type" value="{{session('type') ?? 'work'}}"/>
                <input type="hidden" name="action" id="action" value="start">
                <input type="hidden" name="duration_minutes" id="duration_minutes" value="{{session('duration_minutes') ?? '25'}}">
                <input type="hidden" name="session_id" id="session_id" value="{{ session('session_id') ?? '' }}">
                <input type="hidden" name="session_status" id="session_status" value="{{ session('session_status') ?? '' }}">
                <input type="hidden" name="elapsed_seconds" id="elapsed_seconds" value="{{ session('elapsed_seconds') ?? '' }}">
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('[data-type]');
            const hiddenInput = document.getElementById('type');
            const defaultType = hiddenInput.value;
            const defaultBtn = document.querySelector(`[data-type="${defaultType}"]`);
            if(defaultBtn) defaultBtn.classList.add('bg-white','text-black');
            const timerForm = document.getElementById('timerForm');

            let durationMinutes = document.getElementById('duration_minutes');
            let activeBtnDuration = defaultBtn ? parseInt(defaultBtn.dataset.durationMinutes,10): 25;


            const sessionId = document.getElementById('session_id')?.value || null;
            const sessionStatus = document.getElementById('session_status')?.value || null;
            const flashedElapsed = document.getElementById('elapsed_seconds')?.value || ''; // seconds (string) or ''


            const startPauseBtn = document.getElementById('startPauseBtn');

            let lastTick = null;

            //Timer State
            const state = {
                sessionMs: activeBtnDuration * 60 * 1000,
                remainingMs: activeBtnDuration * 60 * 1000, //start at full session
                isRunning: false,
                intervalId: null,
                runStartRemaining: null,
            };

            if(flashedElapsed !== '')
            {
                const elapsedSec = parseInt(flashedElapsed, 10) || 0;
                state.remainingMs = Math.max(0, state.sessionMs - (elapsedSec * 1000)); //compute remaining time in ms
            } else {
                state.remainingMs = state.sessionMs;
            }

            //UI Text & Auto-Start Decision
            if(sessionId && sessionStatus === 'running')
            {
                setTimeout(() => {
                    startPauseBtn.textContent = 'Pause';
                    startTimer();
                    startPauseBtn.disabled = false;
                }, 150);
            } else {
                //either no session or session is paused/finished - no need to start timer
                //now, specifically for pause functionality
                if(sessionStatus === 'paused')
                {
                    startPauseBtn.textContent = 'Start';
                    startPauseBtn.disabled = false;
                }
                render(); //to ensure correct remainingMs is shown
            }

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

            //Helper function to dynamically update form's action and method
            function setFormAction(url, method='POST')
            {
                timerForm.action = url;

                const methodInput = document.getElementById('_method');
                methodInput.value = (method && method.toUpperCase()!== 'POST')? method.toUpperCase() : '' ;
            }

            buttons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault(); //stop it from submitting the form on clicking the type buttons

                    //update hidden input
                    hiddenInput.value = button.getAttribute('data-type');
                    //update button duration and state objects accordingly
                    activeBtnDuration = parseInt(button.dataset.durationMinutes, 10) || 25;
                    document.getElementById('duration_minutes').value = activeBtnDuration;

                    state.sessionMs = activeBtnDuration * 60 * 1000;
                    state.remainingMs = state.sessionMs;
                    resetTimer(); //stops the timer, sets UI to start and shows full session (remainingMs)

                    //toggle active class
                    buttons.forEach(button => button.classList.remove('bg-white','text-black'));
                    button.classList.add('bg-white','text-black');

                    render();
                })
            })

            // async function sendStart()
            // {
            //     const payload = {
            //         type: hiddenInput.value,
            //         duration_minutes: activeBtnDuration,
            //     };
            //
            //     try{
            //         const res = await fetch('/sessions/start', {
            //             method: 'POST',
            //             headers: {
            //                 'Content-Type': 'application/json',
            //                 'Accept': 'application/json',
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            //             },
            //             body: JSON.stringify(payload),
            //         });
            //
            //         if(!res.ok) {
            //             const text = await res.text();
            //             console.error("Start error response (non-JSON)", res.status, text);
            //             return;
            //         }
            //         const json = await res.json();
            //         console.log('Start Response:', json);
            //     } catch(err) {
            //         console.error('Start Error:', err);
            //     }
            // }

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

                const sid = document.getElementById('session_id')?.value;
                if(!sid) return;

                const totalElapsedSeconds = Math.floor(state.sessionMs/1000);
                document.getElementById('elapsed_seconds').value = totalElapsedSeconds;

                setFormAction(`/sessions/${sid}/finish`, 'PATCH');
                document.getElementById('action').value = 'Finish';

                timerForm.submit();
            }

            function resetTimer()
            {
                const sid = document.getElementById('session_id')?.value;
                if(!sid) return;

                const sessionMs = state.sessionMs;
                const rawElapsedMs = sessionMs - state.remainingMs;
                const elapsedMs = Math.max(0, Math.min(sessionMs, rawElapsedMs));
                const totalElapsedSeconds = Math.floor(elapsedMs/1000);

                document.getElementById('elapsed_seconds').value = totalElapsedSeconds;

                setFormAction(`/sessions/${sid}/cancel`, 'PATCH');
                document.getElementById('action').value = 'Cancel';

                timerForm.submit();

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



            startPauseBtn.addEventListener('click', () => {
                if(state.isRunning) {
                    //step 1: pause locally
                    pauseTimer();

                    //step 2: get SID
                    const sid = document.getElementById('session_id')?.value;
                    if(!sid) {
                        console.warn('No session id found - skipping server side pause');
                        return;
                    }

                    //to compute up-to-date elapsed seconds on UI side. because the initial Date.now() on controller
                    //method won't work. it will also calculate break time and add it to elapsed_seconds.

                    const sessionMs = state.sessionMs;
                    const rawElapsedMs = sessionMs - state.remainingMs;
                    const elapsedSoFarMs = Math.max(0, Math.min(sessionMs,rawElapsedMs));
                    const totalElapsedSeconds = Math.floor(elapsedSoFarMs/1000);

                    document.getElementById('elapsed_seconds').value = totalElapsedSeconds;

                    //step 3:update form's action and method
                    setFormAction(`/sessions/${sid}/pause`, 'PATCH');
                    document.getElementById('action').value = 'Pause';

                    //synchronous form submit
                    timerForm.submit();
                } else {
                    //going from paused to running
                    // sendStart();
                    const sid = document.getElementById('session_id')?.value;
                    const currentStatus = document.getElementById('session_status')?.value;
                    durationMinutes.value = activeBtnDuration;

                    //set action intent and disable the button to avoid double form submission
                    document.getElementById('action').value = "start";
                    startPauseBtn.disabled = true;

                    if(sid && currentStatus === 'paused')
                    {
                        setFormAction(`/sessions/${sid}/resume`, 'PATCH');
                    } else {
                        setFormAction("/sessions/start", 'POST');
                    }

                    //submit the form(POST /sessions/start)
                    timerForm.submit();
                }
            });

            const resetBtn = document.getElementById('resetBtn');
            resetBtn.addEventListener('click', resetTimer);
        })
    </script>
</x-layout>
