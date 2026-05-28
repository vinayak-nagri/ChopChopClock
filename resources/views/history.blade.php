@php
    $completedRows = $completedPaginator->getCollection()->map(function ($record) use ($timezone) {
        return [
            'id' => $record->id,
            'status' => $record->status,
            'date' => $record->started_at->toFormattedDayDateString(),
            'time' => $record->started_at->copy()->setTimezone($timezone)->format('h:i A') . ' - ' . $record->ended_at->copy()->setTimezone($timezone)->format('h:i A'),
            'minutes' => $record->duration_minutes,
        ];
    })->values();

    $cancelledRows = $cancelledPaginator->getCollection()
        ->filter(fn ($record) => $record->elapsed_seconds > 60)
        ->map(function ($record) use ($timezone) {
            return [
                'id' => $record->id,
                'status' => $record->status,
                'date' => $record->started_at->toFormattedDayDateString(),
                'time' => $record->started_at->copy()->setTimezone($timezone)->format('h:i A') . ' - ' . $record->ended_at->copy()->setTimezone($timezone)->format('h:i A'),
                'minutes' => round($record->elapsed_seconds / 60),
            ];
        })->values();

    $historyProps = [
        'summary' => [
            'formattedTotal' => $formattedTotal,
            'totalDays' => $totalDays,
            'streakCount' => $streakCount,
        ],
        'sessions' => [
            'completed' => $completedRows,
            'cancelled' => $cancelledRows,
        ],
        'paginators' => [
            'completed' => $completedPaginator->appends(request()->except('cancelled_page'))->links()->toHtml(),
            'cancelled' => $cancelledPaginator->appends(request()->except('completed_page'))->links()->toHtml(),
        ],
        'initialStatus' => request()->has('cancelled_page') ? 'cancelled' : 'completed',
    ];
@endphp

<x-layout>
    <x-slot:title>History | ChopChopClock</x-slot:title>

    <div id="history-root"></div>
    <script type="application/json" id="history-props">@json($historyProps)</script>
</x-layout>
