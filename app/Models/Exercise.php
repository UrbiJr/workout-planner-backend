<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['description', 'name', 'is_active'])]
class Exercise extends Model
{

    /**
     * Ritorna le schede di allenamento che includono questo esercizio
     */
    public function workoutPlans(): BelongsToMany
    {
        return $this->belongsToMany(WorkoutPlan::class)
            ->using(ExerciseWorkoutPlan::class)
            ->withPivot('sets', 'reps');
    }
}
