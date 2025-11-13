<x-layout>
<x-page-heading class="text-center"> Sign Up </x-page-heading>

    <form action="/register" method="POST" class="max-w-2xl mx-auto space-y-6">
        @csrf
        <div class="flex flex-col space-y-1">
        <label for="first_name" class="font-bold text-shadow-md">First Name</label>
        <input type="text" id="first_name" name="first_name" placeholder="" value="{{ old('first_name') }}"
               class="block w-full h-10 rounded-xl bg-white/10 border border-white/10 px-5 py-4 my-2">
        @error('first_name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        <label for="last_name" class="font-bold text-shadow-md">Last Name</label>
        <input type="text" id="last_name" name="last_name" placeholder="" value="{{ old('last_name') }}"
               class="block w-full h-10 rounded-xl bg-white/10 border border-white/10 px-5 py-4 my-2">
        @error('last_name')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        <label for="email" class="font-bold text-shadow-md">Email</label>
        <input type="email" id="email" name="email" placeholder="" value="{{ old('email') }}"
               class="block w-full h-10 rounded-xl bg-white/10 border border-white/10 px-5 py-4 my-2">
        @error('email')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        <label for="timezone" class="font-bold text-shadow-md">Timezone</label>
        <select name="timezone" id="timezone" class="h-6">
            @foreach(timezone_identifiers_list(DateTimeZone::ALL_WITH_BC) as $tz)
                <option value="{{ $tz }}">{{ $tz }}</option>
            @endforeach
        </select>

        <label for="password" class="font-bold text-shadow-md">Password</label>
        <input type="password" id="password" name="password" placeholder=""
               class="block w-full h-10 rounded-xl bg-white/10 border border-white/10 px-5 py-4 my-2">
        @error('password')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        <label for="password_confirmation" class="font-bold text-shadow-md">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" placeholder=""
               class="block w-full h-10 rounded-xl bg-white/10 border border-white/10 px-5 py-4 my-2">

        <button type="submit" class="self-center px-4 py-2 border w-fit rounded-2xl mt-2 font-semibold bg-emerald-600
                     hover:opacity-90 cursor-pointer">
            Register</button>
        </div>
    </form>
</x-layout>

<script>
    document.getElementById('timezone').value = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
</script>
