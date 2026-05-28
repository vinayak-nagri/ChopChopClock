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
        <div className="flex flex-col items-center">
            <section>
                <h1 className="font-bold text-4xl text-center">
                    History
                </h1>
                <div className="h-1 w-24 bg-white/60 mb-4 mt-1" />
            </section>

            <section>
                <div className="flex flex-row space-x-6">
                    <SummaryCard
                        icon={<ClockIcon className="w-7 h-7 text-gray-500 dark:text-white mb-3" />}
                        title="Total Time"
                        value={summary.formattedTotal}
                    />
                    <SummaryCard
                        icon={<CalendarDaysIcon className="w-8 h-8 text-gray-500 dark:text-white mb-3" />}
                        title="Total Days Logged"
                        value={summary.totalDays}
                    />
                    <SummaryCard
                        icon={<FlameIcon className="w-8 h-8 text-gray-500 dark:text-white mb-3" />}
                        title="Streak"
                        value={summary.streakCount}
                    />
                </div>
            </section>

            <section className="mt-4 mb-3">
                <StatusDropdown
                    selectedStatus={selectedStatus}
                    isOpen={isOpen}
                    onToggle={() => setIsOpen((current) => !current)}
                    onSelect={(status) => {
                        setSelectedStatus(status);
                        setIsOpen(false);
                    }}
                />

                <HistoryTable
                    rows={rows}
                    paginatorHtml={paginators[selectedStatus] ?? ''}
                />
            </section>
        </div>
    );
}
