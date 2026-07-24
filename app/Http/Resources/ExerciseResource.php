<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'is_active' => $this->is_active,
            'name' => $this->name,
            'description' => $this->description,
            'sets' => $this->whenPivotLoaded('exercise_workout_plan', function () {
                return $this->pivot->sets;
            }),
            'reps' => $this->whenPivotLoaded('exercise_workout_plan', function () {
                return $this->pivot->reps;
            }),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
