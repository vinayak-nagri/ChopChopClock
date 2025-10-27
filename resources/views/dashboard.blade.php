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

                <input type="hidden" name="action" id="action" value="start">
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
                isFinishing:false,
            };

            async function sendStart()
            {
                //disable start button to prevent accidental double click submissions
                startPauseBtn.disabled = true;

                //collect form data
                const formData = new FormData(timerForm);

                //read csrf
                const csrf = document.querySelector("meta[name='csrf-token']").content;


                try {
                    //send fetch() to /sessions/start
                    const response = await fetch('/sessions/start', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: formData
                    });

                    //if error in response, throw error message
                    if(!response.ok) {
                        startPauseBtn.disabled = false;
                        throw new Error(
                            `Unexpected response status ${response.status} or content type`
                        );
                    }

                    //ensure we have JSON in response
                    const ct = response.headers.get('content-type') || '';
                    if(!ct.includes('application/json')) {
                        alert('Unexpected server response. Please try again.');
                        return;
                    }

                    //receive json data from server
                    const data = await response.json();

                    //validate expected keys minimally
                    if(!data || typeof(data.session_id) === 'undefined') {
                        alert('Invalid server response. Please try again.');
                        return;
                    }

                    //update hidden inputs
                    document.getElementById('session_id').value = data.session_id;
                    document.getElementById('type').value = data.type;
                    document.getElementById('duration_minutes').value = data.duration_minutes;
                    document.getElementById('elapsed_seconds').value = data.elapsed_seconds;
                    document.getElementById('session_status').value = data.session_status;

                    //initialize state and start
                    activeBtnDuration = parseInt(data.duration_minutes,10) || activeBtnDuration;
                    state.sessionMs = data.duration_minutes * 60 * 1000;
                    state.remainingMs = state.sessionMs - (data.elapsed_seconds * 1000);

                    // Start the timer (startTimer() will set button text to "Pause")
                    startTimer();
                } catch(err) {
                    //Network or unexpected error: Inform user and allow retry
                    console.error('sendStart() network/error:', err);
                    alert('Network error — could not start session. Check your connection and try again.');
                } finally{
                    startPauseBtn.disabled = false;
                }
            }

            async function sendPause(sessionId, elapsedMs)
            {
                //disable button to avoid double click submissions
                startPauseBtn.disabled = true;
                //read csrf
                const csrf = document.querySelector(`meta[name="csrf-token"]`).content;

                try {
                    //send a patch request to the pause route
                    const response = await fetch(`/sessions/${sessionId}/pause`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({elapsed_ms: elapsedMs}),
                    });

                    // check if response is not ok
                    if(!response.ok)
                    {
                        startPauseBtn.disabled = false;
                        const err = await response.json().catch(()=>({}));
                        alert(err.message || `Pause Failed due to ${response.status} Error`);
                        return;
                    }

                    //ensure we have JSON in response
                    const ct = response.headers.get('content-type') || '';
                    if(!ct.includes('application/json')) {
                        alert('Unexpected server response. Please try again.');
                        return;
                    }

                    //parse JSON response from the server and update hidden fields
                    const data = await response.json();

                    //validate expected keys minimally
                    if(!data || typeof(data.session_id) === 'undefined') {
                        alert('Invalid server response. Please try again.');
                        return;
                    }

                    //update hidden fields
                    document.getElementById('elapsed_seconds').value = (typeof data.elapsed_seconds !== 'undefined'
                    ? data.elapsed_seconds
                    : elapsedMs/1000);
                    document.getElementById('session_status').value = data.session_status;

                    //Pause the timer on the UI
                    pauseTimer();
                } catch(err) {
                    console.error('Network Error: ', err);
                    alert('Network error while pausing. Please try again.');
                } finally {
                    startPauseBtn.disabled = false;
                }
            }

            async function sendResume(sessionId, elapsedMs) {
                startPauseBtn.disabled = true;
                const csrf = document.querySelector(`meta[name="csrf-token"]`).content;

                try {
                    const response = await fetch(`/sessions/${sessionId}/resume`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({elapsed_ms: elapsedMs})
                    })

                    if(!response.ok) {
                        startPauseBtn.disabled = false;
                        const err = await response.json().catch(()=>({}));
                        alert(err.message || `Resume Failed due to ${response.status} Error`);
                        return;
                    }

                    //ensure we have JSON in response
                    const ct = response.headers.get('content-type') || '';
                    if(!ct.includes('application/json')) {
                        alert('Unexpected server response. Please try again.');
                        return;
                    }

                    const data = await response.json();

                    //validate expected keys minimally
                    if(!data || typeof(data.session_id) === 'undefined') {
                        alert('Invalid server response. Please try again.');
                        return;
                    }


                    const serverSessionMs = Number(data.duration_minutes) * 60 * 1000;
                    const serverElapsedSeconds = Number(data.elapsed_seconds || 0);
                    const serverRemainingMs = serverSessionMs - (serverElapsedSeconds * 1000);

                    document.getElementById('session_status').value = data.session_status;
                    document.getElementById('elapsed_seconds').value = (typeof data.elapsed_seconds !== 'undefined')
                        ? data.elapsed_seconds
                        : elapsedMs/1000;

                    state.sessionMs = serverSessionMs;
                    state.remainingMs = (typeof serverRemainingMs === 'number')
                        ? Math.min(state.remainingMs, serverRemainingMs)
                        : serverRemainingMs;

                    startTimer();
                } catch(err) {
                    console.error('sendResume() Network error: ', err);
                    alert('Network error while resuming. Please try again.');
                } finally {
                    startPauseBtn.disabled = false;
                }
            }

            async function sendReset(sessionId, elapsedMs) {
                resetBtn.disabled = true;
                const csrf = document.querySelector(`meta[name="csrf-token"]`).content;

                try {
                    const response = await fetch(`/sessions/${sessionId}/cancel`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify({elapsed_ms: elapsedMs}),
                        credentials: 'same-origin'
                    });

                    if(!response.ok) {
                        resetBtn.disabled = false;
                        const err = await response.json().catch(() => ({}));
                        alert(err.message || `Reset Failed: ${response.status} error`);
                        return;
                    }

                    const ct = response.headers.get('content-type') || '';
                    if(!ct.includes('application/json')) {
                        alert('Wrong response!');
                        return;
                    }

                    const data = await response.json();

                    if(!data) {
                        alert('No data received!');
                        return;
                    }

                    if(data.session_status === 'cancelled')
                    {
                        document.getElementById('session_id').value = '';
                        document.getElementById('elapsed_seconds').value = '';
                            document.getElementById('duration_minutes').value = '';
                    } else {
                        document.getElementById('session_id').value = data.session_id;
                        document.getElementById('elapsed_seconds').value = (typeof data.elapsed_seconds !== 'undefined')
                            ? data.elapsed_seconds
                            : elapsedMs/1000;
                        document.getElementById('duration_minutes').value = data.duration_minutes;
                    }
                    document.getElementById('session_status').value = data.session_status;
                    document.getElementById('type').value = data.type;
                    resetLocalTimer(data.duration_minutes);
                } catch(err)
                {
                    console.error('sendReset() Network error: ', err);
                    alert('Network error while resetting. Please try again.');
                } finally {
                    resetBtn.disabled = false;
                }
            }

            async function sendFinish(sessionId)
            {
                // idempotency guard
                if (state.isFinishing) return;
                state.isFinishing = true;

                const csrf = document.querySelector(`meta[name="csrf-token"]`).content;

                try {
                    const response = await fetch(`/sessions/${sessionId}/finish`, {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        credentials: 'same-origin'
                    });

                    if(!response.ok) {
                        const err = await response.json().catch(() => ({}));
                        alert(err.message || `Finish Failed: ${response.status} error`);

                        return;
                    }

                    const data = await response.json();

                    if(!data || typeof (data.session_id) === 'undefined') {
                        alert('Invalid server response. Please try again.');
                        return;
                    }

                    if(data.session_status === 'completed')
                    {
                        document.getElementById('session_id').value = '';
                        document.getElementById('elapsed_seconds').value = '';
                        document.getElementById('duration_minutes').value = '';

                    } else {
                        document.getElementById('session_id').value = data.session_id;
                        document.getElementById('elapsed_seconds').value = data.elapsed_seconds;
                        document.getElementById('duration_minutes').value = data.duration_minutes;

                        document.getElementById('session_status').value = data.session_status;
                        document.getElementById('type').value = data.type;


                    }
                } catch(err)
                {
                    console.error('sendFinish() Network error: ', err);
                    alert('Network error while finishing. Please try again.');
                    // allow retry on failure:
                    state.isFinishing = false;
                }

            }

            // if(flashedElapsed !== '')
            // {
            //     const elapsedSec = parseInt(flashedElapsed, 10) || 0;
            //     state.remainingMs = Math.max(0, state.sessionMs - (elapsedSec * 1000)); //compute remaining time in ms
            // } else {
            //     state.remainingMs = state.sessionMs;
            // }

            //UI Text & Auto-Start Decision:

            // if(sessionId && sessionStatus === 'running')
            // {
            //     setTimeout(() => {
            //         startPauseBtn.textContent = 'Pause';
            //         startTimer();
            //         startPauseBtn.disabled = false;
            //     }, 150);
            // } else {
            //     //either no session or session is paused/finished - no need to start timer
            //     //now, specifically for pause functionality
            //     if(sessionStatus === 'paused')
            //     {
            //         startPauseBtn.textContent = 'Start';
            //         startPauseBtn.disabled = false;
            //     }
            //     render(); //to ensure correct remainingMs is shown
            // }

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
                button.addEventListener('click', async (e) => {
                    e.preventDefault(); //stop it from submitting the form on clicking the type buttons

                    const sid = document.getElementById('session_id')?.value;
                    if(sid)
                    {
                        const sessionMs = state.sessionMs;
                        const rawElapsedMs = sessionMs - state.remainingMs;
                        const elapsedMs = Math.max(0, Math.min(sessionMs, rawElapsedMs));
                        await sendReset(sid, elapsedMs);
                    }

                    //update hidden input
                    hiddenInput.value = button.getAttribute('data-type');
                    //update button duration and state objects accordingly
                    activeBtnDuration = parseInt(button.dataset.durationMinutes, 10) || 25;
                    document.getElementById('duration_minutes').value = activeBtnDuration;

                    state.sessionMs = activeBtnDuration * 60 * 1000;
                    state.remainingMs = state.sessionMs;
                    resetLocalTimer(); //stops the timer, sets UI to start and shows full session (remainingMs)
                    // sendReset();

                    //toggle active class
                    buttons.forEach(button => button.classList.remove('bg-white','text-black'));
                    button.classList.add('bg-white','text-black');

                    // render();
                })
            })

            function tick() {
                const now = Date.now();
                const delta = now - lastTick; //how much real time has passed
                state.remainingMs = state.remainingMs - delta; //subtract the real time from our remaining time
                lastTick = now; //update lastTick baseline for next tick

                if(state.remainingMs <=0) {
                    finishLocalTimer();
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

            function finishLocalTimer()
            {
                state.remainingMs = 0; //set it to zero

                if(state.intervalId) {
                    clearInterval(state.intervalId); //stop loop
                    state.intervalId = null;
                }

                state.isRunning = false;
                startPauseBtn.textContent = "Start";
                render();

                // notify server once (guard prevents duplicate calls)
                const sid = document.getElementById('session_id')?.value;
                if (sid && !state.isFinishing) {
                    sendFinish(sid);
                }

                // const sid = document.getElementById('session_id')?.value;
                // if(!sid) return;
                //
                // const totalElapsedSeconds = Math.floor(state.sessionMs/1000);
                // document.getElementById('elapsed_seconds').value = totalElapsedSeconds;
                //
                // setFormAction(`/sessions/${sid}/finish`, 'PATCH');
                // document.getElementById('action').value = 'Finish';
                //
                // timerForm.submit();
            }

            function resetLocalTimer(durationMinutes)
            {
                //reset sessionMs to total session minutes (server-authoritative)
                state.sessionMs = (Number(durationMinutes) || activeBtnDuration) * 60 * 1000;
                state.remainingMs = state.sessionMs; //set remainingMs to sessionMs

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
                    // pauseTimer();

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

                    sendPause(sid, elapsedSoFarMs);

                    // //step 3:update form's action and method
                    // setFormAction(`/sessions/${sid}/pause`, 'PATCH');
                    // document.getElementById('action').value = 'Pause';
                    //
                    // //synchronous form submit
                    // timerForm.submit();
                } else {
                    //going from paused to running
                    const sid = document.getElementById('session_id')?.value;
                    const currentStatus = document.getElementById('session_status')?.value;
                    const sessionMs = state.sessionMs;
                    const rawElapsedMs = sessionMs - state.remainingMs;
                    const elapsedSoFarMs = Math.max(0, Math.min(sessionMs,rawElapsedMs));

                    if(sid && currentStatus === 'paused'){
                        sendResume(sid, elapsedSoFarMs);
                    } else {
                        sendStart();
                    }

                    // //SYNCHRONOUS LOGIC:


                    // durationMinutes.value = activeBtnDuration;
                    //
                    // //set action intent and disable the button to avoid double form submission
                    // document.getElementById('action').value = "start";
                    // startPauseBtn.disabled = true;
                    //
                    // if(sid && currentStatus === 'paused')
                    // {
                    //     setFormAction(`/sessions/${sid}/resume`, 'PATCH');
                    // } else {
                    //     setFormAction("/sessions/start", 'POST');
                    // }
                    //
                    // //submit the form(POST /sessions/start)
                    // timerForm.submit();
                }
            });

            const resetBtn = document.getElementById('resetBtn');
            resetBtn.addEventListener('click', () => {
                const sid = document.getElementById('session_id')?.value;
                if(!sid) return;

                const sessionMs = state.sessionMs;
                const rawElapsedMs = sessionMs - state.remainingMs;
                const elapsedMs = Math.max(0, Math.min(sessionMs, rawElapsedMs));

                sendReset(sid, elapsedMs);
            });
        })
    </script>
</x-layout>
