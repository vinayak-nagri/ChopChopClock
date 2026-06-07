export default function SummaryCard({ icon, title, value, description, accent = 'pink' }) {
    const accents = {
        pink: 'bg-pink-500/15 text-pink-400',
        blue: 'bg-blue-500/15 text-blue-400',
        green: 'bg-emerald-500/15 text-emerald-400',
    };

    return (
        <div className="glass-panel flex min-h-40 items-center gap-5 rounded-xl p-6">
            <div className={`inline-flex size-16 shrink-0 items-center justify-center rounded-xl ${accents[accent]}`}>
                {icon}
            </div>
            <div className="min-w-0">
                <h5 className="font-semibold text-slate-400">
                    {title}
                </h5>
                <p className="mt-2 text-3xl font-bold text-white">
                    {value}
                </p>
                <p className="mt-2 text-sm text-slate-500">{description}</p>
            </div>
        </div>
    );
}
