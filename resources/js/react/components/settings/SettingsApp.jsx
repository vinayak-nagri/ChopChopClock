import { useEffect, useState } from 'react';
import { CheckIcon } from '../Icons.jsx';

function SuccessToast({ message, visible }) {
    if (!visible || !message) {
        return null;
    }

    return (
        <div className="flex items-center w-full max-w-xs p-4 mb-4 mt-2 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800" role="alert">
            <div className="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                <CheckIcon className="w-5 h-5" />
                <span className="sr-only">Check icon</span>
            </div>
            <div className="ms-3 text-sm font-normal">{message}</div>
        </div>
    );
}

function DurationInput({ id, label, value, onChange }) {
    return (
        <div className="mb-5">
            <label htmlFor={id} className="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {label}
            </label>
            <input
                type="number"
                id={id}
                name={id}
                className="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light"
                value={value}
                required
                onChange={(event) => onChange(event.target.value)}
            />
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
        <div className="flex flex-col items-center">
            <section>
                <h1 className="font-bold text-4xl text-center">
                    Settings
                </h1>
                <div className="h-1 w-28 bg-white/60 mb-4 mt-1" />
            </section>

            <section>
                <form method="POST" className="max-w-sm mx-auto" action="/settings">
                    <input type="hidden" name="_method" value="PUT" />
                    <input type="hidden" name="_token" value={csrf} />

                    <DurationInput
                        id="work_minutes"
                        label="Work Session Duration"
                        value={values.work_minutes}
                        onChange={updateValue('work_minutes')}
                    />
                    <DurationInput
                        id="short_break_minutes"
                        label="Short Break Duration"
                        value={values.short_break_minutes}
                        onChange={updateValue('short_break_minutes')}
                    />
                    <DurationInput
                        id="long_break_minutes"
                        label="Long Break Duration"
                        value={values.long_break_minutes}
                        onChange={updateValue('long_break_minutes')}
                    />

                    <button
                        type="submit"
                        className="text-white bg-blue-700 hover:bg-rose-700/90 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:focus:ring-blue-800 cursor-pointer"
                    >
                        Change Settings
                    </button>
                </form>
            </section>

            <SuccessToast message={successMessage} visible={showToast} />
        </div>
    );
}
