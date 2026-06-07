import PresetButtons from './PresetButtons.jsx';
import { LightbulbIcon, PauseIcon, PlayIcon, RotateCcwIcon } from '../Icons.jsx';

export default function TimerCard({
    presets,
    selectedType,
    displayTime,
    isRunning,
    isBusy,
    hasSession,
    onPresetSelect,
    onStartPause,
    onReset,
}) {
    return (
        <section className="h-full">
            <div className="flex h-full min-h-[470px] flex-col rounded-2xl border border-pink-500/70 bg-[linear-gradient(145deg,rgba(129,8,48,0.95),rgba(74,5,35,0.92))] p-5 shadow-2xl shadow-pink-950/40 sm:p-8">
                <PresetButtons
                    presets={presets}
                    selectedType={selectedType}
                    disabled={isBusy}
                    onSelect={onPresetSelect}
                />

                <div className="flex flex-1 flex-col items-center justify-center py-8 text-center">
                    <div className="font-mono text-6xl font-bold text-white drop-shadow-[0_0_30px_rgba(255,255,255,0.15)] sm:text-8xl lg:text-9xl">
                        {displayTime}
                    </div>
                    <p className="mt-4 text-base font-medium capitalize text-pink-100 sm:text-lg">
                        {selectedType.replace('_', ' ')} session
                    </p>
                </div>

                <div className="flex flex-col gap-4 sm:flex-row">
                    <button
                        className="inline-flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-lg bg-white px-5 py-4 text-lg font-semibold text-pink-700 shadow-xl shadow-black/20 transition hover:bg-pink-50 disabled:cursor-not-allowed disabled:opacity-50"
                        type="button"
                        disabled={isBusy}
                        onClick={onStartPause}
                    >
                        {isRunning ? <PauseIcon className="size-5" /> : <PlayIcon className="size-5" />}
                        {isRunning ? 'Pause' : 'Start'}
                    </button>
                    <button
                        className="inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg border border-white/25 bg-black/10 px-5 py-4 font-medium text-pink-100 transition hover:bg-white/10 hover:text-white disabled:cursor-not-allowed disabled:opacity-50"
                        type="button"
                        disabled={isBusy || !hasSession}
                        onClick={onReset}
                    >
                        <RotateCcwIcon className="size-4" />
                        Reset
                    </button>
                </div>

                <p className="mt-6 flex items-center justify-center gap-2 text-sm text-pink-100/80">
                    <LightbulbIcon className="size-4" />
                    Stay focused. You've got this!
                </p>
            </div>
        </section>
    );
}
