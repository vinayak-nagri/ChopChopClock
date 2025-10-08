<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PomodoroSession extends Model
{
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    protected $guarded = [];
}
