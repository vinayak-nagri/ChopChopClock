const presetLabels = {
    work: 'Work',
    short_break: 'Short Break',
    long_break: 'Long Break',
};

export default function PresetButtons({ presets, selectedType, disabled, onSelect }) {
    return (
        <div className="grid w-full grid-cols-3 gap-2 sm:gap-4">
            {presets.map((preset) => (
                <button
                    key={preset.type}
                    type="button"
                    disabled={disabled}
                    onClick={() => onSelect(preset)}
                    className={`cursor-pointer rounded-full border px-3 py-3 text-xs font-semibold transition disabled:cursor-not-allowed disabled:opacity-50 sm:px-5 sm:text-base ${
                        selectedType === preset.type
                            ? 'border-pink-400/70 bg-pink-500/25 text-white shadow-lg shadow-pink-950/40'
                            : 'border-white/25 bg-black/10 text-pink-100 hover:border-white/50 hover:bg-white/10'
                    }`}
                >
                    {presetLabels[preset.type]}
                </button>
            ))}
        </div>
    );
}
