const presetLabels = {
    work: 'Work',
    short_break: 'Short Break',
    long_break: 'Long Break',
};

export default function PresetButtons({ presets, selectedType, disabled, onSelect }) {
    return (
        <div className="flex-row self-center space-x-16 items-center justify-center">
            {presets.map((preset) => (
                <button
                    key={preset.type}
                    type="button"
                    disabled={disabled}
                    onClick={() => onSelect(preset)}
                    className={`presetBtn preset-btn-base ${selectedType === preset.type ? 'preset-btn-selected' : ''}`}
                >
                    {presetLabels[preset.type]}
                </button>
            ))}
        </div>
    );
}
