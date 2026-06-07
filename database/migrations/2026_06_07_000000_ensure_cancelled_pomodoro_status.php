<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('pomodoro_sessions', function (Blueprint $table) {
                $table->enum('status', ['idle', 'running', 'paused', 'completed', 'cancelled'])
                    ->default('idle')
                    ->change();
            });

            return;
        }

        DB::statement("
            ALTER TABLE pomodoro_sessions
            MODIFY status ENUM('idle', 'running', 'paused', 'completed', 'cancelled')
            NOT NULL DEFAULT 'idle'
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('pomodoro_sessions', function (Blueprint $table) {
                $table->enum('status', ['pending', 'running', 'paused', 'completed'])
                    ->default('pending')
                    ->change();
            });

            return;
        }

        DB::statement("
            ALTER TABLE pomodoro_sessions
            MODIFY status ENUM('pending', 'running', 'paused', 'completed')
            NOT NULL DEFAULT 'pending'
        ");
    }
};
