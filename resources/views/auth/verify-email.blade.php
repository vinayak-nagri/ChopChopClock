<x-layout>
    <x-page-heading class="text-center">Verify Email</x-page-heading>

    <section class="max-w-2xl mx-auto space-y-6 text-center">
        <p class="text-white/80">
            We sent a verification link to {{ auth()->user()->email }}. Open that link to finish setting up your account.
        </p>

        @if (session('message'))
            <p class="rounded-xl border border-emerald-400/40 bg-emerald-500/10 px-4 py-3 text-emerald-200">
                {{ session('message') }}
            </p>
        @endif

        <div class="flex flex-wrap items-center justify-center gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="px-4 py-2 border w-fit rounded-2xl font-semibold bg-emerald-600 hover:opacity-90 cursor-pointer">
                    Send Again
                </button>
            </form>

            <form method="POST" action="/logout">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 border border-white/40 w-fit rounded-2xl font-semibold hover:bg-white/10 cursor-pointer">
                    Log Out
                </button>
            </form>
        </div>
    </section>
</x-layout>
