<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pomodoro_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->enum('type',['work','short_break','long_break'])->default('work');
            $table->integer('duration_minutes');
            $table->integer('elapsed_seconds');
            $table->enum('status',['pending','running','paused','completed'])->default('pending');
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['user_id','started_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pomodoro_sessions');
    }
};
