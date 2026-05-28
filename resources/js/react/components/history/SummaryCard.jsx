export default function SummaryCard({ icon, title, value }) {
    return (
        <div className="card">
            {icon}
            <div className="flex flex-col">
                <h5 className="mb-2 text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                    {title}
                </h5>
                <p className="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                    {value}
                </p>
            </div>
        </div>
    );
}
