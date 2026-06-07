import { useEffect, useMemo, useState } from 'react';
import { CalendarDaysIcon, ClockIcon, FlameIcon } from '../Icons.jsx';
import HistoryTable from './HistoryTable.jsx';
import StatusDropdown from './StatusDropdown.jsx';
import SummaryCard from './SummaryCard.jsx';

export default function HistoryApp({ summary, sessions, paginators, initialStatus }) {
    const [selectedStatus, setSelectedStatus] = useState(initialStatus);
    const [isOpen, setIsOpen] = useState(false);

    useEffect(() => {
        if (!isOpen) {
            return undefined;
        }

        const closeDropdown = () => setIsOpen(false);
        window.addEventListener('click', closeDropdown);

        return () => window.removeEventListener('click', closeDropdown);
    }, [isOpen]);

    const rows = useMemo(() => sessions[selectedStatus] ?? [], [selectedStatus, sessions]);

    return (
        <div>
            <section className="text-center">
                <h1 className="section-title text-4xl font-bold text-white sm:text-5xl">
                    History
                </h1>
                <p className="mx-auto mt-5 max-w-2xl text-slate-400">
                    Review your completed Pomodoro sessions and track your progress over time.
                </p>
            </section>

            <section className="mt-10">
                <div className="grid gap-5 md:grid-cols-3">
                    <SummaryCard
                        icon={<ClockIcon className="size-8" />}
                        title="Total Time"
                        value={summary.formattedTotal}
                        description="All completed sessions"
                    />
                    <SummaryCard
                        icon={<CalendarDaysIcon className="size-8" />}
                        title="Total Days Logged"
                        value={summary.totalDays}
                        description="Days with at least 1 session"
                        accent="blue"
                    />
                    <SummaryCard
                        icon={<FlameIcon className="size-8" />}
                        title="Streak"
                        value={summary.streakCount}
                        description="Current consecutive days"
                        accent="green"
                    />
                </div>
            </section>

            <section className="glass-panel mt-8 rounded-2xl p-4 sm:p-6">
                <div className="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 className="text-xl font-semibold text-white">Session History</h2>
                        <p className="mt-1 text-sm text-slate-500">Switch between completed and cancelled sessions.</p>
                    </div>
                    <StatusDropdown
                        selectedStatus={selectedStatus}
                        isOpen={isOpen}
                        onToggle={() => setIsOpen((current) => !current)}
                        onSelect={(status) => {
                            setSelectedStatus(status);
                            setIsOpen(false);
                        }}
                    />
                </div>
                <HistoryTable
                    rows={rows}
                    paginatorHtml={paginators[selectedStatus] ?? ''}
                />
            </section>
        </div>
    );
}
