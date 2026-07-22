<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Table('exercise_workout_plan', incrementing: true)]
#[Fillable(['reps', 'sets'])]
class ExerciseWorkoutPlan extends Pivot
{
    //
}
