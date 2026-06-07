export function TargetIcon({ className = '' }) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
             className={className}>
            <circle cx="12" cy="12" r="10" />
            <circle cx="12" cy="12" r="6" />
            <circle cx="12" cy="12" r="2" />
        </svg>
    );
}

export function ClockIcon({ className = '' }) {
    return (
        <svg className={className} aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
             viewBox="0 0 20 20">
            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z" />
        </svg>
    );
}

export function CheckIcon({ className = '' }) {
    return (
        <svg className={className} aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
             viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
        </svg>
    );
}

export function CalendarDaysIcon({ className = '' }) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
             className={className}>
            <path d="M8 2v4" />
            <path d="M16 2v4" />
            <rect width="18" height="18" x="3" y="4" rx="2" />
            <path d="M3 10h18" />
            <path d="M8 14h.01" />
            <path d="M12 14h.01" />
            <path d="M16 14h.01" />
            <path d="M8 18h.01" />
            <path d="M12 18h.01" />
            <path d="M16 18h.01" />
        </svg>
    );
}

export function FlameIcon({ className = '' }) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
             className={className}>
            <path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4" />
        </svg>
    );
}

export function ChevronDownIcon({ className = '' }) {
    return (
        <svg className={className} aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 10 6">
            <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                  d="m1 1 4 4 4-4" />
        </svg>
    );
}

export function PlayIcon({ className = '' }) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" className={className}>
            <path d="M8 5.8v12.4c0 .8.9 1.3 1.6.9l9.1-6.2a1.1 1.1 0 0 0 0-1.8L9.6 4.9A1.1 1.1 0 0 0 8 5.8Z" />
        </svg>
    );
}

export function PauseIcon({ className = '' }) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" className={className}>
            <path d="M7 5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V5Zm6 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V5Z" />
        </svg>
    );
}

export function RotateCcwIcon({ className = '' }) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
             className={className}>
            <path d="M3 12a9 9 0 1 0 3-6.7L3 8" />
            <path d="M3 3v5h5" />
        </svg>
    );
}

export function BriefcaseIcon({ className = '' }) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
             className={className}>
            <rect x="3" y="7" width="18" height="13" rx="2" />
            <path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18M10 12v2h4v-2" />
        </svg>
    );
}

export function CoffeeIcon({ className = '' }) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
             className={className}>
            <path d="M4 9h13v5a5 5 0 0 1-5 5H9a5 5 0 0 1-5-5V9Z" />
            <path d="M17 10h2a2 2 0 1 1 0 4h-2M6 3v2M10 3v2M14 3v2M3 21h16" />
        </svg>
    );
}

export function TreePineIcon({ className = '' }) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
             className={className}>
            <path d="m12 3-4 5h2l-4 5h3l-4 5h14l-4-5h3l-4-5h2l-4-5Z" />
            <path d="M12 18v3" />
        </svg>
    );
}

export function SaveIcon({ className = '' }) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
             className={className}>
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z" />
            <path d="M17 21v-8H7v8M7 3v5h8" />
        </svg>
    );
}

export function InboxIcon({ className = '' }) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
             className={className}>
            <path d="M4 4h16l2 10v6H2v-6L4 4Z" />
            <path d="M2 14h5l2 3h6l2-3h5" />
        </svg>
    );
}

export function LightbulbIcon({ className = '' }) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
             className={className}>
            <path d="M9 18h6M10 22h4" />
            <path d="M8.5 14.5A7 7 0 1 1 15.5 14.5c-.9.7-1.5 1.7-1.5 2.5h-4c0-.8-.6-1.8-1.5-2.5Z" />
        </svg>
    );
}
