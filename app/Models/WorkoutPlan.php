<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['client_id'])]
class WorkoutPlan extends Model
{
    /**
     *  Restituisce il cliente a cui è assegnata questa scheda di allenamento
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Ritorna gli esercizi inclusi in una scheda di allenamento
     */
    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class)
            ->using(ExerciseWorkoutPlan::class)
            ->withPivot('sets', 'reps');
    }
}
