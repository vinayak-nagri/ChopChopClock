<x-layout>
<div class="flex flex-col items-center"> {{--main div--}}
    <section> {{--section for page heading--}}
        <x-page-heading>Dashboard</x-page-heading>
        <div class="h-1 w-32 bg-white/60 mb-4"></div>
    </section>

    <section> {{--section for main timer--}}
        <div class="flex flex-col relative bg-red-400 rounded-xl p-6 border border-white/60 w-lg h-80 mb-2">
            <div class="self-center space-x-24 items-center">
                <button> Work </button>
                <button> Short Break </button>
                <button> Long Break </button>
            </div>

            <div class="text-center text-white font-bold text-8xl absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                25:00
            </div>

            <div class="self-center mt-auto">
                <button> Pause </button>
            </div>
        </div>
    </section>

    <section class="mb-2"> {{--section for today's recent sessions--}}
        <div class="w-lg mx-auto text-center">
            <x-section-heading class="mb-2"> Today's Sessions </x-section-heading>

            <div class="grid grid-cols-4 gap-4 w-lg">
                <div class="font-semibold"> Time </div>
                <div class="font-semibold"> Type </div>
                <div class="font-semibold"> Duration </div>
                <div class="font-semibold"> Status </div>

                <div>10:00</div>
                <div>Work</div>
                <div>25 min</div>
                <div>Done</div>

                <div>10:30</div>
                <div>Short Break</div>
                <div>5 min</div>
                <div>Done</div>

                <div>11:00</div>
                <div>Work</div>
                <div>25 min</div>
                <div>Abandoned</div>
            </div>
        </div>
    </section>
</div>
</x-layout>
