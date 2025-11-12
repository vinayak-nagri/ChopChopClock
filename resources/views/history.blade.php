<x-layout>
    <x-slot:title>History | ChopChopClock</x-slot:title>
    <div class="flex flex-col items-center"> {{--main div--}}
        <section> {{--section for page heading--}}
            <x-page-heading>History</x-page-heading>
            <div class="h-1 w-24 bg-white/60 mb-4 mt-1"></div>
        </section>

        <section> {{--Cards for Total Hours and Total Days--}}
            <div class="flex flex-row space-x-6 ">
                <div class="card">
                    <svg class="w-7 h-7 text-gray-500 dark:text-white mb-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                    </svg>
                    <div class="flex flex-col">
                        <h5 class="mb-2 text-xl font-semibold tracking-tight text-gray-900 dark:text-white">Total Time</h5>
                        <p class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">{{$formattedTotal}}</p>
                    </div>
                </div>

                <div class="card">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="w-8 h-8 text-gray-500 dark:text-white mb-3 lucide lucide-calendar-days-icon lucide-calendar-days"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/>
                    </svg>
                    <div class="flex flex-col">
                    <h5 class="mb-2 text-xl font-semibold tracking-tight text-gray-900 dark:text-white">Total Days Logged</h5>
                    <p class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">{{$totalDays}}</p>
                    </div>
                </div>

                <div class="card">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="w-8 h-8 text-gray-500 dark:text-white mb-3 lucide lucide-flame-icon lucide-flame"><path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"/>
                    </svg>
                    <div class="flex flex-col">
                        <h5 class="mb-2 text-xl font-semibold tracking-tight text-gray-900 dark:text-white">Streak</h5>
                        <p class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">{{$streakCount}}</p>
                    </div>
                </div>

            </div>
        </section>

        <section class="mt-4 mb-3"> {{--Table for History Records--}}

                <div class="relative mb-2">
                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="inline-flex items-center text-gray-500
                    bg-white border border-gray-300 focus:outline-none cursor-pointer
                    hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3
                    py-1.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700
                    dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">
                    <span id="dropdownLabel">Select Work Session Status</span>
                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>

                <!-- Dropdown menu -->
                    <div id="dropdown" class="z-10 mt-2 absolute hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44
                    dark:bg-gray-700">
                        <ul class="pt-1 pb-1/2 text-sm text-gray-700 dark:text-gray-200 mb-2" aria-labelledby="dropdownDefaultButton">
                            <li>
                                <a href="#" data-status="completed" class="block px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Completed Sessions</a>
                            </li>
                            <li>
                                <a href="#" data-status="cancelled" class="block px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Cancelled Sessions</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-xl  ">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Time
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Minutes
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($completedPaginator as $completedRecord)
                    <tr data-status="{{$completedRecord->status}}" class="hidden bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$completedRecord -> started_at -> toFormattedDayDateString()}}
                        </th>
                        <td class="px-6 py-4">
                            {{$completedRecord -> started_at -> setTimezone($timezone) -> format('h:i A')}} - {{$completedRecord -> ended_at ->setTimezone($timezone) -> format('h:i A')}}
                        </td>
                        <td class="px-6 py-4">
                            {{$completedRecord -> duration_minutes}}
                        </td>
                    </tr>
                    @endforeach

                    @foreach ($cancelledPaginator as $cancelledRecord)
                        @if ($cancelledRecord->elapsed_seconds > 60)
                        <tr data-status="{{$cancelledRecord->status}}" class="hidden bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$cancelledRecord -> started_at -> toFormattedDayDateString()}}
                            </th>
                            <td class="px-6 py-4">
                                {{$cancelledRecord -> started_at -> setTimezone($timezone) -> format('h:i A')}} - {{$cancelledRecord -> ended_at -> setTimezone($timezone) -> format('h:i A')}}
                            </td>
                            <td class="px-6 py-4">
                                {{round($cancelledRecord->elapsed_seconds / 60)}}
                            </td>
                        </tr>
                        @endif
                    @endforeach


                    </tbody>
                </table>

                <div id="completedPaginator" class="hidden mt-4">
                    {{$completedPaginator->appends(request()->except('cancelled_page'))->links()}}
                </div>
                <div id="cancelledPaginator" class="hidden mt-4">
                    {{$cancelledPaginator->appends(request()->except('completed_page'))->links()}}
                </div>

        </section>
    </div>
</x-layout>
