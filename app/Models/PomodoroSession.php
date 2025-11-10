<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PomodoroSession extends Model
{
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    protected $guarded = [];

    public static function formatTotalTime($completedMinutes, $cancelledSeconds = 0)
    {
        $totalSeconds = ($completedMinutes * 60) + $cancelledSeconds;
        $totalHours = intDiv($totalSeconds, 3600);
        $remainingMinutes = intDiv($totalSeconds % 3600, 60);
        return sprintf("%d hr %02d min", $totalHours, $remainingMinutes);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
