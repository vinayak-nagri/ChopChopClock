<x-layout>
    <x-slot:title>Settings | ChopChopClock</x-slot:title>
    <div class="flex flex-col items-center"> {{--main div--}}
        <section> {{--section for page heading--}}
            <x-page-heading>Settings</x-page-heading>
            <div class="h-1 w-28 bg-white/60 mb-4 mt-1"></div>
        </section>

        <section> {{--section for form--}}
            <form method="POST" class="max-w-sm mx-auto" action="/settings">
                @method('PUT')
                @csrf
                <div class="mb-5">
                    <label for="work_minutes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Work Session Duration</label>
                    <input type="number" id="work_minutes" name="work_minutes"
                           class="shadow-xs bg-gray-50 border border-gray-300
                           text-gray-900 text-sm rounded-lg focus:ring-blue-500
                           focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700
                           dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                           dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light"
                           value="{{$defaultSettings -> work_minutes}}" required />
                </div>
                <div class="mb-5">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Short Break Duration</label>
                    <input type="number" id="short_break_minutes" name="short_break_minutes"
                           class="shadow-xs bg-gray-50 border border-gray-300
                           text-gray-900 text-sm rounded-lg focus:ring-blue-500
                           focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700
                           dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                           dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light"
                           value="{{$defaultSettings -> short_break_minutes}}" required />
                </div>
                <div class="mb-5">
                    <label for="long_break_minutes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Long Break Duration</label>
                    <input type="number" id="long_break_minutes" name="long_break_minutes"
                           class="shadow-xs bg-gray-50 border border-gray-300
                           text-gray-900 text-sm rounded-lg focus:ring-blue-500
                           focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700
                           dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                           dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light"
                           value="{{$defaultSettings -> long_break_minutes}}" required />
                </div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-rose-700/90 focus:ring-4
                focus:outline-none focus:ring-blue-300 font-medium rounded-lg
                text-sm px-5 py-2.5 text-center dark:bg-blue-600
                dark:focus:ring-blue-800 cursor-pointer">Change Settings</button>
            </form>
        </section>
        @if(session('success'))
            <div id="toast-success" class="flex items-center w-full max-w-xs p-4 mb-4 mt-2 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800" role="alert">
                <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{session('success')}}</div>
            </div>
        @endif
    </div>
    <script>
        const toast = document.getElementById('toast-success');
        setTimeout(() => toast?.remove(),3000);
    </script>
</x-layout>
