@php
    $settingsProps = [
        'settings' => [
            'work_minutes' => $defaultSettings->work_minutes,
            'short_break_minutes' => $defaultSettings->short_break_minutes,
            'long_break_minutes' => $defaultSettings->long_break_minutes,
        ],
        'csrf' => csrf_token(),
        'successMessage' => session('success'),
    ];
@endphp

<x-layout>
    <x-slot:title>Settings | ChopChopClock</x-slot:title>

    <div id="settings-root"></div>
    <script type="application/json" id="settings-props">@json($settingsProps)</script>
</x-layout>
