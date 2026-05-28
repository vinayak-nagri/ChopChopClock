export default function HistoryTable({ rows, paginatorHtml }) {
    return (
        <>
            <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-xl">
                <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" className="px-6 py-3">Date</th>
                    <th scope="col" className="px-6 py-3">Time</th>
                    <th scope="col" className="px-6 py-3">Minutes</th>
                </tr>
                </thead>
                <tbody>
                {rows.length === 0 ? (
                    <tr className="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td className="px-6 py-4 text-center" colSpan="3">
                            No sessions found.
                        </td>
                    </tr>
                ) : rows.map((row) => (
                    <tr
                        key={row.id}
                        data-status={row.status}
                        className="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600"
                    >
                        <th scope="row" className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {row.date}
                        </th>
                        <td className="px-6 py-4">{row.time}</td>
                        <td className="px-6 py-4">{row.minutes}</td>
                    </tr>
                ))}
                </tbody>
            </table>

            <div className="mt-4" dangerouslySetInnerHTML={{ __html: paginatorHtml }} />
        </>
    );
}
