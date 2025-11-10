document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('[data-type]');
    // const hiddenInput = document.getElementById('type');
    // const defaultType = hiddenInput.value;
    const defaultType = document.getElementById('type').value;
    const defaultBtn = document.querySelector(`[data-type="${defaultType}"]`);
    setSelectedPreset(defaultBtn);

    const startPauseBtn = document.getElementById('startPauseBtn');
    const timerForm = document.getElementById('timerForm');
    let activeBtnDuration = defaultBtn ? parseInt(defaultBtn.dataset.durationMinutes,10): 25;

    let lastTick = null;

    //Timer State
    const state = {
        sessionMs: activeBtnDuration * 60 * 1000,
        remainingMs: activeBtnDuration * 60 * 1000, //start at full session
        isRunning: false,
        intervalId: null,
        isFinishing:false,
    };

    function setSelectedPreset(button) {
        buttons.forEach(b => b.classList.remove('preset-btn-selected'));
        if(button) button.classList.add('preset-btn-selected');
    }

    function getSessionId() {
        const sessionId = document.getElementById('session_id');
        return sessionId?.value || '';
    }

    function setSessionData({session_id,
                                session_status,
                                elapsed_seconds,
                                type,
                                duration_minutes} = {}) {
        if(typeof session_id !== 'undefined') {
            document.getElementById('session_id').value = session_id ?? ''
        }

        if(typeof session_status !== 'undefined') {
            document.getElementById('session_status').value = session_status ?? ''
        }

        if(typeof elapsed_seconds !== 'undefined') {
            document.getElementById('elapsed_seconds').value = elapsed_seconds ?? ''
        }

        if(typeof type !== 'undefined') {
            document.getElementById('type').value = type ?? ''
        }

        if(typeof duration_minutes !== 'undefined') {
            document.getElementById('duration_minutes').value = duration_minutes ?? ''
        }
    }

    async function fetchJson(url, options = {}) {
        const response = await fetch(url, options);
        let errBody = null;
        if(!response.ok) {
            try {
                errBody = await response.json();
            } catch(e) {}

            const msg = (errBody && errBody.message) ?
                errBody.message : `HTTP ${response.status}`;

            const err = new Error(msg);
            err.status  = response.status;
            err.body = errBody;
            throw err;
        }

        const ct = response.headers.get('content-type') || '';
        if(! ct.includes('application/json')) {
            const err = new Error('Unexpected server response (Expected JSON)');
            err.status = response.status;
            throw err;
        }

        return response.json();
    }

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
            const data = await fetchJson('/sessions/start', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: formData
            });

            //validate expected keys minimally
            if(!data || typeof(data.session_id) === 'undefined') {
                alert('Invalid server response. Please try again.');
                return;
            }

            //update hidden inputs
            setSessionData({
                session_id: data.session_id,
                session_status: data.session_status,
                elapsed_seconds: data.elapsed_seconds,
                type: data.type,
                duration_minutes: data.duration_minutes
            })

            //initialize state and start
            activeBtnDuration = parseInt(data.duration_minutes,10) || activeBtnDuration;
            state.sessionMs = data.duration_minutes * 60 * 1000;
            state.remainingMs = state.sessionMs - (data.elapsed_seconds * 1000);

            // Start the timer (startTimer() will set button text to "Pause")
            startTimer();
        } catch(err) {
            //Network or unexpected error: Inform user and allow retry
            console.error('sendStart() network/error:', err);
            alert(err.message || 'Network error — could not start session. Check your connection and try again.');
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
            const data = await fetchJson(`/sessions/${sessionId}/pause`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                credentials: 'same-origin',
                body: JSON.stringify({elapsed_ms: elapsedMs}),
            });

            //validate expected keys minimally
            if(!data || typeof(data.session_id) === 'undefined') {
                alert('Invalid server response. Please try again.');
                return;
            }

            //update hidden fields
            setSessionData(
                {
                    elapsed_seconds: data.elapsed_seconds,
                    session_status: data.session_status
                }
            )

            //Pause the timer on the UI
            pauseTimer();
        } catch(err) {
            console.error('Network Error: ', err);
            alert(err.message || 'Network error while pausing. Please try again.');
        } finally {
            startPauseBtn.disabled = false;
        }
    }

    async function sendResume(sessionId, elapsedMs) {
        startPauseBtn.disabled = true;
        const csrf = document.querySelector(`meta[name="csrf-token"]`).content;

        try {
            const data = await fetchJson(`/sessions/${sessionId}/resume`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                credentials: 'same-origin',
                body: JSON.stringify({elapsed_ms: elapsedMs})
            })

            //validate expected keys minimally
            if(!data || typeof(data.session_id) === 'undefined') {
                alert('Invalid server response. Please try again.');
                return;
            }

            const serverSessionMs = Number(data.duration_minutes) * 60 * 1000;
            const serverElapsedSeconds = Number(data.elapsed_seconds || 0);
            const serverRemainingMs = serverSessionMs - (serverElapsedSeconds * 1000);

            setSessionData(
                {
                    elapsed_seconds: data.elapsed_seconds,
                    session_status: data.session_status
                }
            )

            state.sessionMs = serverSessionMs;
            state.remainingMs = (typeof serverRemainingMs === 'number')
                ? Math.min(state.remainingMs, serverRemainingMs)
                : serverRemainingMs;

            startTimer();
        } catch(err) {
            console.error('sendResume() Network error: ', err);
            alert(err.message||'Network error while resuming. Please try again.');
        } finally {
            startPauseBtn.disabled = false;
        }
    }

    async function sendReset(sessionId, elapsedMs) {
        resetBtn.disabled = true;
        const csrf = document.querySelector(`meta[name="csrf-token"]`).content;

        try {
            const data = await fetchJson(`/sessions/${sessionId}/cancel`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({elapsed_ms: elapsedMs}),
                credentials: 'same-origin'
            });

            if(!data) {
                alert('No data received!');
                return;
            }

            setSessionData({
                session_id: '',
                elapsed_seconds: '',
                duration_minutes: '',
                session_status: data.session_status,
                type: data.type
            })

            resetLocalTimer(data.duration_minutes);
        } catch(err)
        {
            console.error('sendReset() Network error: ', err);
            alert(err.message||'Network error while resetting. Please try again.');
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
            const data = await fetchJson(`/sessions/${sessionId}/finish`, {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                credentials: 'same-origin'
            });

            if(!data || typeof (data.session_id) === 'undefined') {
                alert('Invalid server response. Please try again.');
                return;
            }

            resetLocalTimer(data.duration_minutes);

            setSessionData({
                session_id: '',
                elapsed_seconds: '',
                duration_minutes: '',
                session_status: data.session_status,
                type: data.type
            })
        } catch(err)
        {
            console.error('sendFinish() Network error: ', err);
            alert(err.message||'Network error while finishing. Please try again.');
            // allow retry on failure:
        }finally {
            state.isFinishing = false;
        }
    }

    //convert ms to proper format HH:MM
    function formatTime(ms) {
        const totalSeconds = Math.floor(ms/1000); //drops ms
        const minutes = Math.floor(totalSeconds/60); //whole minutes
        const seconds = totalSeconds % 60; //leftover seconds
        return `${minutes}:${seconds.toString().padStart(2,'0')}`;
    }

    //show and update timer
    function render() {
        const display = document.getElementById('timerDisplay');
        display.textContent = formatTime(state.remainingMs);
    }

    render();

    buttons.forEach(button => {
        button.addEventListener('click', async (e) => {
            e.preventDefault(); //stop it from submitting the form on clicking the type buttons

            const sid = getSessionId();
            if(sid)
            {
                const sessionMs = state.sessionMs;
                const rawElapsedMs = sessionMs - state.remainingMs;
                const elapsedMs = Math.max(0, Math.min(sessionMs, rawElapsedMs));
                await sendReset(sid, elapsedMs);
            }

            //update type value
            setSessionData({type: button.getAttribute('data-type')});
            //update button duration and state objects accordingly
            activeBtnDuration = parseInt(button.dataset.durationMinutes, 10) || 25;
            setSessionData({duration_minutes: activeBtnDuration});

            state.sessionMs = activeBtnDuration * 60 * 1000;
            state.remainingMs = state.sessionMs;
            resetLocalTimer(activeBtnDuration); //stops the timer, sets UI to start and shows full session (remainingMs)
            // sendReset();

            //toggle active class
            setSelectedPreset(button);
            render();
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
        // const sid = document.getElementById('session_id')?.value;
        const sid = getSessionId();
        if (sid && !state.isFinishing) {
            sendFinish(sid);
        }
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
            const sid = getSessionId();
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
        } else {
            //going from paused to running
            const sid = getSessionId();
            const currentStatus = document.getElementById('session_status')?.value;
            const sessionMs = state.sessionMs;
            const rawElapsedMs = sessionMs - state.remainingMs;
            const elapsedSoFarMs = Math.max(0, Math.min(sessionMs,rawElapsedMs));

            if(sid && currentStatus === 'paused'){
                sendResume(sid, elapsedSoFarMs);
            } else {
                sendStart();
            }
        }
    });

    const resetBtn = document.getElementById('resetBtn');
    resetBtn.addEventListener('click', () => {
        const sid = getSessionId();
        if(!sid) return;

        const sessionMs = state.sessionMs;
        const rawElapsedMs = sessionMs - state.remainingMs;
        const elapsedMs = Math.max(0, Math.min(sessionMs, rawElapsedMs));

        sendReset(sid, elapsedMs);
    });
})
