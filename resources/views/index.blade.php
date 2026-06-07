<x-layout>
    <x-slot:title>ChopChopClock | Focus Better</x-slot:title>

    <div class="space-y-16 lg:space-y-20">
        <section class="mx-auto grid max-w-6xl items-center gap-10 py-12 lg:min-h-[520px] lg:grid-cols-[1.05fr_0.95fr] lg:gap-6 lg:py-16">
            <div class="max-w-3xl">
                <h1 class="text-4xl font-bold leading-tight text-white sm:text-6xl lg:text-7xl">
                    Focus better.
                    <span class="block bg-gradient-to-r from-blue-500 via-sky-400 to-cyan-300 bg-clip-text text-transparent">
                        Build momentum.
                    </span>
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-8 text-slate-300">
                    A clean Pomodoro timer to plan focused work sessions, track progress, and build a steady routine.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ auth()->check() ? '/dashboard' : '/register' }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white shadow-xl shadow-blue-600/25 transition hover:bg-blue-500">
                        Get Started
                        <span aria-hidden="true">→</span>
                    </a>
                    @guest
                        <a href="/login" class="inline-flex items-center gap-2 rounded-lg border border-slate-600/70 bg-slate-900/60 px-6 py-3 font-semibold text-slate-100 transition hover:border-slate-400 hover:bg-slate-800/80">
                            Log In
                        </a>
                    @endguest
                </div>
            </div>

            <div class="relative mx-auto flex w-full max-w-[560px] items-center justify-center lg:max-w-none">
                <img
                    src="{{ asset('images/neon-pomodoro-transparent.png') }}"
                    alt="Neon Pomodoro timer"
                    class="h-auto w-full lg:w-[110%] lg:max-w-none"
                />
            </div>
        </section>

        <section class="mx-auto grid max-w-6xl gap-5 md:grid-cols-3">
            <article class="glass-panel rounded-xl p-6">
                <div class="mb-5 inline-flex size-14 items-center justify-center rounded-full bg-cyan-500/15 text-cyan-300">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-7" aria-hidden="true">
                        <path d="M8 2v4M16 2v4M3 10h18"/>
                        <rect x="3" y="4" width="18" height="17" rx="2"/>
                        <path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-white">Plan Sessions</h2>
                <p class="mt-2 leading-7 text-slate-400">Estimate your work blocks before you begin.</p>
            </article>

            <article class="glass-panel rounded-xl p-6">
                <div class="mb-5 inline-flex size-14 items-center justify-center rounded-full bg-sky-500/15 text-sky-300">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-7" aria-hidden="true">
                        <circle cx="12" cy="13" r="8"/>
                        <path d="M12 9v4l2.5 1.5M9 2h6M12 2v3"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-white">Stay Focused</h2>
                <p class="mt-2 leading-7 text-slate-400">Use work, short break, and long break timers.</p>
            </article>

            <article class="glass-panel rounded-xl p-6">
                <div class="mb-5 inline-flex size-14 items-center justify-center rounded-full bg-violet-500/15 text-violet-300">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-7" aria-hidden="true">
                        <path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-white">Track Progress</h2>
                <p class="mt-2 leading-7 text-slate-400">See your daily sessions, total time, and streak.</p>
            </article>
        </section>

        <section class="mx-auto max-w-6xl">
            <h2 class="section-title text-center text-3xl font-bold text-white sm:text-4xl">How It Works</h2>
            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach([
                    ['Choose Custom Work Duration', 'Set custom desired duration for Work Sessions', '01'],
                    ['Start the timer', 'Hit start and dive into focused work.', '02'],
                    ['Take breaks', 'Recharge with short or long breaks.', '03'],
                    ['Track your progress', 'Review your sessions, time, and streak.', '04'],
                ] as [$title, $copy, $number])
                    <article class="glass-panel relative rounded-xl p-6 pt-8">
                        <span class="absolute -top-3 left-5 inline-flex size-8 items-center justify-center rounded-full border border-blue-400/50 bg-blue-600 text-xs font-bold text-white shadow-lg shadow-blue-600/30">
                            {{ $number }}
                        </span>
                        <h3 class="text-lg font-semibold text-white">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-400">{{ $copy }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="mx-auto max-w-6xl">
            <div class="glass-panel flex flex-col items-start justify-between gap-6 rounded-xl border-blue-500/60 p-7 sm:flex-row sm:items-center lg:px-10">
                <div>
                    <h2 class="text-2xl font-bold text-white sm:text-3xl">Ready to build a better focus routine?</h2>
                    @guest
                        <p class="mt-2 text-slate-400">Join ChopChopClock and take control of your time.</p>
                    @endguest
                </div>
                <a href="{{ auth()->check() ? '/dashboard' : '/register' }}" class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white shadow-xl shadow-blue-600/25 transition hover:bg-blue-500">
                    {{ auth()->check() ? 'Start Now' : 'Create Your Account' }}
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </section>
    </div>
</x-layout>
