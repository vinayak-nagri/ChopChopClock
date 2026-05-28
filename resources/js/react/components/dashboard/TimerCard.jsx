import PresetButtons from './PresetButtons.jsx';

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
        <section>
            <div className="flex flex-col relative bg-rose-700/90 shadow-lg shadow-red-900 backdrop-blur-sm rounded-xl p-6 border border-white/60 w-xl h-90 mb-2 transition hover:scale-[1.01] hover:shadow-2xl">
                <PresetButtons
                    presets={presets}
                    selectedType={selectedType}
                    disabled={isBusy}
                    onSelect={onPresetSelect}
                />

                <div className="text-center text-white font-bold text-9xl absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    {displayTime}
                </div>

                <div className="flex flex-row justify-between mt-auto">
                    <button
                        className="mx-auto cursor-pointer inline-flex items-center justify-center px-4 py-2 rounded-sm text-2xl w-56 text-rose-600 font-semibold bg-white border-solid border-1 border-white/50 tracking-wide transition transform duration-150 ease-out hover:scale-105 active:scale-95 active:shadow-[0_2px_10px_rgba(0,0,0,0.3)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/30 select-none disabled:opacity-50 disabled:cursor-not-allowed"
                        type="button"
                        disabled={isBusy}
                        onClick={onStartPause}
                    >
                        {isRunning ? 'Pause' : 'Start'}
                    </button>
                    <button
                        className="self-end cursor-pointer inline-flex items-center justify-center px-4 py-2 rounded-sm w-15 bg-white/20 border-solid border-1 border-white/30 text-white/70 hover:text-white tracking-wide transition transform duration-150 ease-out hover:scale-105 active:scale-95 active:shadow-[0_2px_10px_rgba(0,0,0,0.3)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/30 select-none disabled:opacity-50 disabled:cursor-not-allowed"
                        type="button"
                        disabled={isBusy || !hasSession}
                        onClick={onReset}
                    >
                        Reset
                    </button>
                </div>
            </div>
        </section>
    );
}
