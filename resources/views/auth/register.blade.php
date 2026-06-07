<x-layout>
    <x-slot:title>Sign Up | ChopChopClock</x-slot:title>

    <section class="auth-stage flex items-center justify-center py-4 sm:py-8">
        <div class="auth-card w-full max-w-3xl rounded-2xl p-6 sm:p-10">
            <div class="text-center">
                <div class="auth-icon mx-auto inline-flex size-16 items-center justify-center rounded-full text-blue-300">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="size-8" aria-hidden="true">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4.5 21a7.5 7.5 0 0 1 15 0"/>
                    </svg>
                </div>
                <h1 class="mt-5 text-4xl font-bold text-white sm:text-5xl">Sign Up</h1>
                <p class="mt-3 text-slate-400">Create your account and start building a better focus routine.</p>
            </div>

            <form action="/register" method="POST" class="mt-8 space-y-5">
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="first_name" class="mb-2 block text-sm font-semibold text-slate-100">First Name</label>
                        <div class="auth-field-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="auth-field-icon" aria-hidden="true">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M4.5 21a7.5 7.5 0 0 1 15 0"/>
                            </svg>
                            <input type="text" id="first_name" name="first_name" placeholder="Enter your first name"
                                   value="{{ old('first_name') }}" autocomplete="given-name" required class="auth-field">
                        </div>
                        @error('first_name')
                            <p class="mt-2 text-sm text-pink-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="mb-2 block text-sm font-semibold text-slate-100">Last Name</label>
                        <div class="auth-field-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="auth-field-icon" aria-hidden="true">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M4.5 21a7.5 7.5 0 0 1 15 0"/>
                            </svg>
                            <input type="text" id="last_name" name="last_name" placeholder="Enter your last name"
                                   value="{{ old('last_name') }}" autocomplete="family-name" required class="auth-field">
                        </div>
                        @error('last_name')
                            <p class="mt-2 text-sm text-pink-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-100">Email</label>
                    <div class="auth-field-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="auth-field-icon" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="14" rx="2"/>
                            <path d="m3 7 9 6 9-6"/>
                        </svg>
                        <input type="email" id="email" name="email" placeholder="Enter your email address"
                               value="{{ old('email') }}" autocomplete="email" required class="auth-field">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-pink-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="timezone" class="mb-2 block text-sm font-semibold text-slate-100">Timezone</label>
                    <div class="auth-field-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="auth-field-icon" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/>
                        </svg>
                        <select name="timezone" id="timezone" required class="auth-field appearance-none pr-12">
                            @foreach(timezone_identifiers_list(DateTimeZone::ALL_WITH_BC) as $tz)
                                <option value="{{ $tz }}" @selected(old('timezone') === $tz)>{{ $tz }}</option>
                            @endforeach
                        </select>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="pointer-events-none absolute right-4 size-4 text-slate-400" aria-hidden="true">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </div>
                    @error('timezone')
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
                        <input type="password" id="password" name="password" placeholder="Create a strong password"
                               autocomplete="new-password" required class="auth-field pr-12">
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

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-100">Confirm Password</label>
                    <div class="auth-field-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="auth-field-icon" aria-hidden="true">
                            <rect x="5" y="10" width="14" height="11" rx="2"/>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                        </svg>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password"
                               autocomplete="new-password" required class="auth-field pr-12">
                        <button type="button" class="auth-password-toggle" data-password-toggle="password_confirmation" aria-label="Show password confirmation">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/>
                                <circle cx="12" cy="12" r="2.5"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="auth-submit">Register</button>
            </form>

            <div class="my-6 flex items-center gap-4 text-sm text-slate-400">
                <span class="h-px flex-1 bg-slate-700/70"></span>
                <span>OR</span>
                <span class="h-px flex-1 bg-slate-700/70"></span>
            </div>

            <p class="text-center text-slate-400">
                Already have an account?
                <a href="/login" class="font-semibold text-blue-400 transition hover:text-blue-300">Log In</a>
            </p>
        </div>
    </section>

    <script>
        const timezone = document.getElementById('timezone');
        const oldTimezone = @json(old('timezone'));

        if (!oldTimezone) {
            timezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
        }

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
