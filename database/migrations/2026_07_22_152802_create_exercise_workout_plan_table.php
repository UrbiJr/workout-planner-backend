<?php

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
        Schema::create('exercise_workout_plan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workout_plan_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sets'); // non saranno mai negative
            $table->unsignedInteger('reps'); // non saranno mai negative
            $table->timestamps();

            $table->unique(['exercise_id', 'workout_plan_id']); // ogni workout plan non dovrebbe avere più volte lo stesso esercizio
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_workout_plan');
    }
};
