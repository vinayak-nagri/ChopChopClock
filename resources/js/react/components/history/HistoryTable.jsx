import { InboxIcon } from '../Icons.jsx';

export default function HistoryTable({ rows, paginatorHtml }) {
    return (
        <>
            <div className="overflow-x-auto rounded-xl border border-slate-700/60">
            <table className={`${rows.length === 0 ? 'w-full' : 'w-full min-w-[680px]'} text-left text-sm text-slate-300`}>
                <thead className="bg-slate-800/75 text-xs uppercase text-slate-400">
                <tr>
                    <th scope="col" className="px-3 py-3 sm:px-6">Date</th>
                    <th scope="col" className="px-3 py-3 sm:px-6">Status</th>
                    <th scope="col" className="px-3 py-3 sm:px-6">Start Time</th>
                    <th scope="col" className="px-3 py-3 sm:px-6">Duration</th>
                </tr>
                </thead>
                <tbody>
                {rows.length === 0 ? (
                    <tr>
                        <td className="px-3 py-12 text-center sm:px-6" colSpan="4">
                            <div className="mx-auto flex max-w-md flex-col items-center py-6">
                                <div className="inline-flex size-20 items-center justify-center rounded-full bg-slate-800/80 text-slate-500">
                                    <InboxIcon className="size-10" />
                                </div>
                                <h3 className="mt-5 text-xl font-semibold text-white">No sessions found.</h3>
                                <p className="mt-2 text-slate-400">Complete your first Pomodoro session to see it here.</p>
                            </div>
                        </td>
                    </tr>
                ) : rows.map((row) => (
                    <tr
                        key={row.id}
                        data-status={row.status}
                        className="border-t border-slate-700/50 bg-slate-950/20 transition hover:bg-slate-800/35"
                    >
                        <th scope="row" className="whitespace-nowrap px-3 py-4 font-medium text-white sm:px-6">
                            {row.date}
                        </th>
                        <td className="px-3 py-4 sm:px-6">
                            <span className={`inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize ${
                                row.status === 'completed'
                                    ? 'bg-emerald-500/15 text-emerald-400'
                                    : 'bg-pink-500/15 text-pink-400'
                            }`}>
                                {row.status}
                            </span>
                        </td>
                        <td className="px-3 py-4 sm:px-6">{row.time}</td>
                        <td className="px-3 py-4 sm:px-6">{row.minutes} min</td>
                    </tr>
                ))}
                </tbody>
            </table>
            </div>

            <div className="pagination-shell mt-4" dangerouslySetInnerHTML={{ __html: paginatorHtml }} />
        </>
    );
}
