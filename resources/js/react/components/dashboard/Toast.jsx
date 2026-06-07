import { CheckIcon } from '../Icons.jsx';

export default function Toast({ visible, message }) {
    if (!visible) {
        return null;
    }

    return (
        <div className="glass-panel fixed top-4 right-4 z-[9999] flex w-full max-w-xs items-center gap-3 rounded-xl p-4 text-slate-200" role="alert">
            <div className="inline-flex size-9 shrink-0 items-center justify-center rounded-lg bg-emerald-500/15 text-emerald-400">
                <CheckIcon className="w-5 h-5" />
                <span className="sr-only">Check icon</span>
            </div>
            <div className="text-sm font-medium">{message}</div>
        </div>
    );
}
