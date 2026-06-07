import { useEffect, useState } from 'react';
import {
    BriefcaseIcon,
    CheckIcon,
    ClockIcon,
    CoffeeIcon,
    RotateCcwIcon,
    SaveIcon,
    TreePineIcon,
} from '../Icons.jsx';

const defaults = {
    work_minutes: 25,
    short_break_minutes: 5,
    long_break_minutes: 15,
};

function SuccessToast({ message, visible }) {
    if (!visible || !message) {
        return null;
    }

    return (
        <div className="glass-panel fixed top-4 right-4 z-50 flex max-w-xs items-center gap-3 rounded-xl p-4 text-slate-200" role="alert">
            <div className="inline-flex size-9 shrink-0 items-center justify-center rounded-lg bg-emerald-500/15 text-emerald-400">
                <CheckIcon className="size-5" />
                <span className="sr-only">Success</span>
            </div>
            <div className="text-sm font-medium">{message}</div>
        </div>
    );
}

function DurationRow({ id, label, description, value, icon, accent, onChange }) {
    const changeBy = (amount) => {
        onChange(Math.max(1, Number(value) + amount));
    };

    return (
        <div className="grid gap-5 border-t border-slate-700/60 py-6 first:border-t-0 sm:grid-cols-[1fr_auto] sm:items-center">
            <div className="flex items-center gap-4">
                <div className={`inline-flex size-14 shrink-0 items-center justify-center rounded-xl ${accent}`}>
                    {icon}
                </div>
                <div>
                    <label htmlFor={id} className="text-lg font-semibold text-white">{label}</label>
                    <p className="mt-1 text-sm text-slate-400">{description}</p>
                </div>
            </div>

            <div className="flex h-14 overflow-hidden rounded-lg border border-slate-600/60 bg-slate-950/50">
                <input
                    type="number"
                    id={id}
                    name={id}
                    min="1"
                    value={value}
                    required
                    onChange={(event) => onChange(Math.max(1, Number(event.target.value)))}
                    className="w-24 bg-transparent px-4 font-semibold text-white outline-none sm:w-28"
                />
                <span className="flex items-center border-r border-slate-700/60 px-3 text-sm text-slate-400">min</span>
                <button
                    type="button"
                    aria-label={`Decrease ${label}`}
                    onClick={() => changeBy(-1)}
                    className="w-12 border-r border-slate-700/60 text-2xl text-slate-300 transition hover:bg-white/10 hover:text-white"
                >
                    &minus;
                </button>
                <button
                    type="button"
                    aria-label={`Increase ${label}`}
                    onClick={() => changeBy(1)}
                    className="w-12 text-2xl text-slate-300 transition hover:bg-white/10 hover:text-white"
                >
                    +
                </button>
            </div>
        </div>
    );
}

export default function SettingsApp({ settings, csrf, successMessage }) {
    const [values, setValues] = useState({
        work_minutes: settings.work_minutes,
        short_break_minutes: settings.short_break_minutes,
        long_break_minutes: settings.long_break_minutes,
    });
    const [showToast, setShowToast] = useState(Boolean(successMessage));

    useEffect(() => {
        if (!showToast) {
            return undefined;
        }

        const timeout = window.setTimeout(() => setShowToast(false), 3000);

        return () => window.clearTimeout(timeout);
    }, [showToast]);

    const updateValue = (key) => (value) => {
        setValues((current) => ({
            ...current,
            [key]: value,
        }));
    };

    return (
        <div>
            <section className="text-center">
                <h1 className="section-title text-4xl font-bold text-white sm:text-5xl">Settings</h1>
                <p className="mx-auto mt-5 max-w-2xl text-slate-400">
                    Customize your timer experience and preferences.
                </p>
            </section>

            <section className="glass-panel mx-auto mt-10 max-w-5xl rounded-2xl p-5 sm:p-8">
                <div className="flex items-center gap-4 pb-6">
                    <div className="inline-flex size-12 items-center justify-center rounded-full border-2 border-pink-500 text-pink-400">
                        <ClockIcon className="size-6" />
                    </div>
                    <div>
                        <h2 className="text-2xl font-bold text-white">Timer Settings</h2>
                        <p className="mt-1 text-slate-400">Set the default durations for your Pomodoro timer.</p>
                    </div>
                </div>

                <form method="POST" action="/settings">
                    <input type="hidden" name="_method" value="PUT" />
                    <input type="hidden" name="_token" value={csrf} />

                    <DurationRow
                        id="work_minutes"
                        label="Work Session Duration"
                        description="Focus time for deep work"
                        value={values.work_minutes}
                        icon={<BriefcaseIcon className="size-7" />}
                        accent="bg-pink-500/15 text-pink-400"
                        onChange={updateValue('work_minutes')}
                    />
                    <DurationRow
                        id="short_break_minutes"
                        label="Short Break Duration"
                        description="Quick break to relax"
                        value={values.short_break_minutes}
                        icon={<CoffeeIcon className="size-7" />}
                        accent="bg-blue-500/15 text-blue-400"
                        onChange={updateValue('short_break_minutes')}
                    />
                    <DurationRow
                        id="long_break_minutes"
                        label="Long Break Duration"
                        description="Longer break to recharge"
                        value={values.long_break_minutes}
                        icon={<TreePineIcon className="size-7" />}
                        accent="bg-emerald-500/15 text-emerald-400"
                        onChange={updateValue('long_break_minutes')}
                    />

                    <div className="flex flex-col-reverse gap-3 border-t border-slate-700/60 pt-6 sm:flex-row sm:justify-end">
                        <button
                            type="button"
                            onClick={() => setValues(defaults)}
                            className="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-600/70 bg-slate-950/40 px-5 py-3 font-semibold text-slate-300 transition hover:border-slate-400 hover:bg-slate-800/70 hover:text-white"
                        >
                            <RotateCcwIcon className="size-5" />
                            Reset to Default
                        </button>
                        <button
                            type="submit"
                            className="inline-flex items-center justify-center gap-2 rounded-lg bg-pink-600 px-6 py-3 font-semibold text-white shadow-xl shadow-pink-950/40 transition hover:bg-pink-500"
                        >
                            <SaveIcon className="size-5" />
                            Save Changes
                        </button>
                    </div>
                </form>
            </section>

            <SuccessToast message={successMessage} visible={showToast} />
        </div>
    );
}
