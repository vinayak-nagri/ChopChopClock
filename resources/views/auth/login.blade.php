<x-layout>
    <x-slot:title>Log In | ChopChopClock</x-slot:title>

    <section class="auth-stage flex min-h-[620px] items-center justify-center py-4 sm:py-8">
        <div class="auth-card w-full max-w-2xl rounded-2xl p-6 sm:p-10">
            <div class="text-center">
                <div class="auth-icon mx-auto inline-flex size-16 items-center justify-center rounded-full text-blue-300">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="size-8" aria-hidden="true">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4.5 21a7.5 7.5 0 0 1 15 0"/>
                    </svg>
                </div>
                <h1 class="mt-5 text-4xl font-bold text-white sm:text-5xl">Log In</h1>
                <p class="mt-3 text-slate-400">Welcome back! Please enter your credentials.</p>
            </div>

            <form action="/login" method="POST" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-100">Email</label>
                    <div class="auth-field-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="auth-field-icon" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="14" rx="2"/>
                            <path d="m3 7 9 6 9-6"/>
                        </svg>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter your email address"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                            class="auth-field"
                        >
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-pink-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-100">Password</label>
                    <div class="auth-field-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="auth-field-icon" aria-hidden="true">
                            <rect x="5" y="10" width="14" height="11" rx="2"/>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                            class="auth-field pr-12"
                        >
                        <button type="button" class="auth-password-toggle" data-password-toggle="password" aria-label="Show password">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/>
                                <circle cx="12" cy="12" r="2.5"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-pink-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="auth-submit">
                    Log In
                </button>
            </form>

            <p class="mt-7 text-center text-slate-400">
                Don't have an account?
                <a href="/register" class="font-semibold text-blue-400 transition hover:text-blue-300">Sign up</a>
            </p>
        </div>
    </section>

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.passwordToggle);
                const showPassword = input.type === 'password';

                input.type = showPassword ? 'text' : 'password';
                button.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');
            });
        });
    </script>
</x-layout>
