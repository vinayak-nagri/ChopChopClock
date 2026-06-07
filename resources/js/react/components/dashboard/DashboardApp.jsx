import { useCallback, useEffect, useMemo, useRef, useState } from 'react';
import { csrfToken, fetchJson } from '../../lib/dom.js';
import { ClockIcon, TargetIcon } from '../Icons.jsx';
import MetricCard from './MetricCard.jsx';
import TimerCard from './TimerCard.jsx';
import Toast from './Toast.jsx';

const minuteMs = 60 * 1000;

function formatTime(ms) {
    const totalSeconds = Math.max(0, Math.floor(ms / 1000));
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;

    return `${minutes}:${seconds.toString().padStart(2, '0')}`;
}

function clampElapsedMs(sessionMs, remainingMs) {
    return Math.max(0, Math.min(sessionMs, sessionMs - remainingMs));
}

export default function DashboardApp({ settings, activeSession, metrics: initialMetrics }) {
    const presets = useMemo(() => [
        { type: 'work', durationMinutes: Number(settings.work_minutes) || 25 },
        { type: 'short_break', durationMinutes: Number(settings.short_break_minutes) || 5 },
        { type: 'long_break', durationMinutes: Number(settings.long_break_minutes) || 15 },
    ], [settings]);

    const getPresetDuration = useCallback((type) => (
        presets.find((preset) => preset.type === type)?.durationMinutes ?? presets[0].durationMinutes
    ), [presets]);

    const initialType = activeSession?.type ?? 'work';
    const initialDuration = Number(activeSession?.duration_minutes) || getPresetDuration(initialType);
    const initialElapsedSeconds = Number(activeSession?.elapsed_seconds) || 0;

    const [sessionData, setSessionData] = useState({
        id: activeSession?.id ?? '',
        status: activeSession?.status ?? '',
        elapsedSeconds: initialElapsedSeconds,
        type: initialType,
        durationMinutes: initialDuration,
    });
    const [sessionMs, setSessionMs] = useState(initialDuration * minuteMs);
    const [remainingMs, setRemainingMs] = useState(
        Math.max(0, (initialDuration * minuteMs) - (initialElapsedSeconds * 1000))
    );
    const [isRunning, setIsRunning] = useState(activeSession?.status === 'running');
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [isFinishing, setIsFinishing] = useState(false);
    const [metrics, setMetrics] = useState(initialMetrics);
    const [toast, setToast] = useState({ visible: false, message: 'Session Complete!' });

    const bellRef = useRef(null);
    const audioUnlockedRef = useRef(false);
    const lastTickRef = useRef(null);
    const intervalRef = useRef(null);
    const sessionDataRef = useRef(sessionData);
    const sessionMsRef = useRef(sessionMs);
    const remainingMsRef = useRef(remainingMs);
    const finishRef = useRef(null);

    useEffect(() => {
        sessionDataRef.current = sessionData;
        sessionMsRef.current = sessionMs;
        remainingMsRef.current = remainingMs;
    }, [sessionData, sessionMs, remainingMs]);

    useEffect(() => {
        if (window.Howl) {
            bellRef.current = new window.Howl({ src: ['/sounds/notification.wav'] });
        }

        return () => {
            bellRef.current?.unload?.();
        };
    }, []);

    useEffect(() => {
        if (!toast.visible) {
            return undefined;
        }

        const timeout = window.setTimeout(() => {
            setToast((current) => ({ ...current, visible: false }));
        }, 6000);

        return () => window.clearTimeout(timeout);
    }, [toast.visible]);

    const jsonHeaders = useCallback(() => ({
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken(),
    }), []);

    const playBell = useCallback(() => {
        if (bellRef.current) {
            bellRef.current.play();
        }
    }, []);

    const unlockAudio = useCallback(() => {
        if (audioUnlockedRef.current || !bellRef.current) {
            return;
        }

        bellRef.current.play();
        bellRef.current.stop();
        audioUnlockedRef.current = true;
    }, []);

    const showCompletion = useCallback((type) => {
        setToast({
            visible: true,
            message: type === 'short_break' ? 'Break Finished!' : 'Session Complete!',
        });
        playBell();
    }, [playBell]);

    const resetLocalTimer = useCallback((durationMinutes, type, overrides = {}) => {
        const nextDuration = Number(durationMinutes) || getPresetDuration(type);

        setIsRunning(false);
        lastTickRef.current = null;
        setSessionMs(nextDuration * minuteMs);
        setRemainingMs(nextDuration * minuteMs);
        setSessionData({
            id: overrides.id ?? '',
            status: overrides.status ?? '',
            elapsedSeconds: overrides.elapsedSeconds ?? '',
            type,
            durationMinutes: nextDuration,
        });
    }, [getPresetDuration]);

    const refreshMetrics = useCallback(async () => {
        try {
            const nextMetrics = await fetchJson('/dashboard/metrics', {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });

            setMetrics({
                countWorkSessions: nextMetrics.count_work_sessions,
                formattedTotal: nextMetrics.formatted_total,
            });
        } catch (error) {
            console.error('Metric update failed', error);
        }
    }, []);

    const finishLocalTimer = useCallback(() => {
        const currentType = sessionDataRef.current.type;

        setRemainingMs(0);
        setIsRunning(false);
        lastTickRef.current = null;
        showCompletion(currentType);
    }, [showCompletion]);

    const sendFinish = useCallback(async (sessionId) => {
        if (isFinishing || !sessionId) {
            return;
        }

        setIsFinishing(true);
        finishLocalTimer();

        try {
            const data = await fetchJson(`/sessions/${sessionId}/finish`, {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                },
                credentials: 'same-origin',
            });

            const nextType = data.type === 'work' ? 'short_break' : 'work';
            const nextDuration = getPresetDuration(nextType);

            resetLocalTimer(nextDuration, nextType, { status: data.session_status });
            await refreshMetrics();
        } catch (error) {
            console.error('Network error while finishing', error);
            alert(error.message || 'Network error while finishing. Please try again.');
        } finally {
            setIsFinishing(false);
        }
    }, [finishLocalTimer, getPresetDuration, isFinishing, refreshMetrics, resetLocalTimer]);

    useEffect(() => {
        finishRef.current = () => {
            const sessionId = sessionDataRef.current.id;

            if (sessionId) {
                void sendFinish(sessionId);
            } else {
                finishLocalTimer();
            }
        };
    }, [finishLocalTimer, sendFinish]);

    useEffect(() => {
        if (!isRunning) {
            return undefined;
        }

        lastTickRef.current = Date.now();
        intervalRef.current = window.setInterval(() => {
            const now = Date.now();
            const delta = now - lastTickRef.current;
            lastTickRef.current = now;

            setRemainingMs((currentRemaining) => {
                const nextRemaining = currentRemaining - delta;

                if (nextRemaining <= 0) {
                    window.clearInterval(intervalRef.current);
                    intervalRef.current = null;
                    finishRef.current?.();
                    return 0;
                }

                return nextRemaining;
            });
        }, 300);

        return () => {
            window.clearInterval(intervalRef.current);
            intervalRef.current = null;
        };
    }, [isRunning]);

    const sendStart = useCallback(async () => {
        setIsSubmitting(true);

        try {
            const data = await fetchJson('/sessions/start', {
                method: 'POST',
                headers: jsonHeaders(),
                credentials: 'same-origin',
                body: JSON.stringify({
                    type: sessionDataRef.current.type,
                    duration_minutes: sessionDataRef.current.durationMinutes,
                }),
            });

            const nextDuration = Number(data.duration_minutes) || sessionDataRef.current.durationMinutes;

            setSessionData({
                id: data.session_id,
                status: data.session_status,
                elapsedSeconds: Number(data.elapsed_seconds) || 0,
                type: data.type,
                durationMinutes: nextDuration,
            });
            setSessionMs(nextDuration * minuteMs);
            setRemainingMs((nextDuration * minuteMs) - ((Number(data.elapsed_seconds) || 0) * 1000));
            setIsRunning(true);
        } catch (error) {
            console.error('Network error while starting', error);
            alert(error.message || 'Network error - could not start session. Check your connection and try again.');
        } finally {
            setIsSubmitting(false);
        }
    }, [jsonHeaders]);

    const sendPause = useCallback(async (sessionId, elapsedMs) => {
        setIsSubmitting(true);

        try {
            const data = await fetchJson(`/sessions/${sessionId}/pause`, {
                method: 'PATCH',
                headers: jsonHeaders(),
                credentials: 'same-origin',
                body: JSON.stringify({ elapsed_ms: elapsedMs }),
            });

            setSessionData((current) => ({
                ...current,
                elapsedSeconds: data.elapsed_seconds,
                status: data.session_status,
            }));
            setIsRunning(false);
        } catch (error) {
            console.error('Network error while pausing', error);
            alert(error.message || 'Network error while pausing. Please try again.');
        } finally {
            setIsSubmitting(false);
        }
    }, [jsonHeaders]);

    const sendResume = useCallback(async (sessionId, elapsedMs) => {
        setIsSubmitting(true);

        try {
            const data = await fetchJson(`/sessions/${sessionId}/resume`, {
                method: 'PATCH',
                headers: jsonHeaders(),
                credentials: 'same-origin',
                body: JSON.stringify({ elapsed_ms: elapsedMs }),
            });

            const serverSessionMs = Number(data.duration_minutes) * minuteMs;
            const serverRemainingMs = serverSessionMs - ((Number(data.elapsed_seconds) || 0) * 1000);

            setSessionData((current) => ({
                ...current,
                elapsedSeconds: data.elapsed_seconds,
                status: data.session_status,
                durationMinutes: Number(data.duration_minutes) || current.durationMinutes,
            }));
            setSessionMs(serverSessionMs);
            setRemainingMs((current) => Math.min(current, serverRemainingMs));
            setIsRunning(true);
        } catch (error) {
            console.error('Network error while resuming', error);
            alert(error.message || 'Network error while resuming. Please try again.');
        } finally {
            setIsSubmitting(false);
        }
    }, [jsonHeaders]);

    const sendCancel = useCallback(async (sessionId, elapsedMs) => {
        return fetchJson(`/sessions/${sessionId}/cancel`, {
            method: 'PATCH',
            headers: jsonHeaders(),
            credentials: 'same-origin',
            body: JSON.stringify({ elapsed_ms: elapsedMs }),
        });
    }, [jsonHeaders]);

    const handlePresetSelect = useCallback(async (preset) => {
        const sessionId = sessionDataRef.current.id;
        const elapsedMs = clampElapsedMs(sessionMsRef.current, remainingMsRef.current);

        setIsSubmitting(true);

        try {
            if (sessionId) {
                await sendCancel(sessionId, elapsedMs);
            }

            resetLocalTimer(preset.durationMinutes, preset.type);
        } catch (error) {
            console.error('Network error while switching presets', error);
            alert(error.message || 'Network error while resetting. Please try again.');
        } finally {
            setIsSubmitting(false);
        }
    }, [resetLocalTimer, sendCancel]);

    const handleStartPause = useCallback(() => {
        unlockAudio();

        const { id, status } = sessionDataRef.current;
        const elapsedMs = clampElapsedMs(sessionMsRef.current, remainingMsRef.current);

        if (isRunning) {
            if (!id) {
                console.warn('No session id found - skipping server side pause');
                return;
            }

            void sendPause(id, elapsedMs);
            return;
        }

        if (id && status === 'paused') {
            void sendResume(id, elapsedMs);
            return;
        }

        void sendStart();
    }, [isRunning, sendPause, sendResume, sendStart, unlockAudio]);

    const handleReset = useCallback(async () => {
        const { id, type, durationMinutes } = sessionDataRef.current;

        if (!id) {
            return;
        }

        setIsSubmitting(true);

        try {
            const elapsedMs = clampElapsedMs(sessionMsRef.current, remainingMsRef.current);
            const data = await sendCancel(id, elapsedMs);

            resetLocalTimer(data.duration_minutes || durationMinutes, data.type || type, {
                status: data.session_status,
            });
        } catch (error) {
            console.error('Network error while resetting', error);
            alert(error.message || 'Network error while resetting. Please try again.');
        } finally {
            setIsSubmitting(false);
        }
    }, [resetLocalTimer, sendCancel]);

    const isBusy = isSubmitting || isFinishing;

    return (
        <div>
            <section className="mb-8 text-center">
                <h1 className="section-title text-4xl font-bold text-white sm:text-5xl">
                    Dashboard
                </h1>
            </section>

            <div className="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
                <TimerCard
                    presets={presets}
                    selectedType={sessionData.type}
                    displayTime={formatTime(remainingMs)}
                    isRunning={isRunning}
                    isBusy={isBusy}
                    hasSession={Boolean(sessionData.id)}
                    onPresetSelect={handlePresetSelect}
                    onStartPause={handleStartPause}
                    onReset={handleReset}
                />

                <section className="glass-panel rounded-2xl p-6 sm:p-8">
                    <h2 className="text-2xl font-bold text-white">
                        Today's Sessions
                    </h2>
                    <p className="mt-2 text-sm text-slate-400">Your completed focus work for today.</p>

                    <div className="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
                        <MetricCard
                            icon={<TargetIcon className="size-6" />}
                            title="Work Sessions"
                            value={metrics.countWorkSessions}
                            accent="pink"
                        />
                        <MetricCard
                            icon={<ClockIcon className="size-6" />}
                            title="Total Hours"
                            value={metrics.formattedTotal}
                        />
                    </div>
                </section>
            </div>

            <Toast visible={toast.visible} message={toast.message} />
        </div>
    );
}
