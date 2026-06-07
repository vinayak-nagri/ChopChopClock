import { ChevronDownIcon } from '../Icons.jsx';

const labels = {
    completed: 'Completed',
    cancelled: 'Cancelled',
};

export default function StatusDropdown({ selectedStatus, isOpen, onToggle, onSelect }) {
    return (
        <div className="relative">
            <button
                className="inline-flex min-w-40 cursor-pointer items-center justify-between rounded-lg border border-slate-600/70 bg-slate-950/50 px-4 py-3 text-sm font-semibold text-white transition hover:border-slate-400 hover:bg-slate-900"
                type="button"
                onClick={(event) => {
                    event.stopPropagation();
                    onToggle();
                }}
            >
                <span>{labels[selectedStatus]}</span>
                <ChevronDownIcon className="ms-4 size-2.5" />
            </button>

            {isOpen && (
                <div
                    className="glass-panel absolute right-0 z-10 mt-2 w-48 overflow-hidden rounded-lg p-1"
                    onClick={(event) => event.stopPropagation()}
                >
                    <ul className="text-sm text-slate-200">
                        <li>
                            <button
                                type="button"
                                onClick={() => onSelect('completed')}
                                className="block w-full rounded-md px-3 py-2 text-left hover:bg-white/10 hover:text-white"
                            >
                                Completed Sessions
                            </button>
                        </li>
                        <li>
                            <button
                                type="button"
                                onClick={() => onSelect('cancelled')}
                                className="block w-full rounded-md px-3 py-2 text-left hover:bg-white/10 hover:text-white"
                            >
                                Cancelled Sessions
                            </button>
                        </li>
                    </ul>
                </div>
            )}
        </div>
    );
}
