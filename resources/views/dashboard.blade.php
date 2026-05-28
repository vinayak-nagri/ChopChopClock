@php
    $dashboardProps = [
        'settings' => [
            'work_minutes' => $defaultSettings->work_minutes,
            'short_break_minutes' => $defaultSettings->short_break_minutes,
            'long_break_minutes' => $defaultSettings->long_break_minutes,
        ],
        'activeSession' => $activeSession ? [
            'id' => $activeSession->id,
            'status' => $activeSession->status,
            'elapsed_seconds' => $activeSession->elapsed_seconds,
            'type' => $activeSession->type,
            'duration_minutes' => $activeSession->duration_minutes,
        ] : null,
        'metrics' => [
            'countWorkSessions' => $countWorkSessions,
            'formattedTotal' => $formattedTotal,
        ],
    ];
@endphp

<x-layout>
    <x-slot:title>Dashboard | ChopChopClock</x-slot:title>

    <div id="dashboard-root"></div>
    <script type="application/json" id="dashboard-props">@json($dashboardProps)</script>
</x-layout>
