export default function MetricCard({ icon, title, value, accent = 'blue' }) {
    const accentClasses = accent === 'pink'
        ? 'bg-pink-500/15 text-pink-400'
        : 'bg-blue-500/15 text-blue-400';

    return (
        <div className="rounded-xl border border-slate-700/70 bg-slate-950/25 p-5">
            <div className="flex items-center gap-4">
                <div className={`inline-flex size-12 shrink-0 items-center justify-center rounded-xl ${accentClasses}`}>
                    {icon}
                </div>
                <div className="min-w-0">
                <h5 className="text-sm font-semibold text-slate-400 sm:text-base">
                    {title}
                </h5>
                <p className="mt-2 text-2xl font-bold text-white">
                    {value}
                </p>
                </div>
            </div>
        </div>
    );
}
