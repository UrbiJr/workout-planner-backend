<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkoutPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('workout_plan'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['sometimes', 'integer', Rule::exists('clients', 'id')],
            'exercises' => ['sometimes', 'array'],
            'exercises.*.id' => ['required', 'integer', Rule::exists('exercises', 'id')],
            'exercises.*.sets' => ['required', 'integer', 'min:1'],
            'exercises.*.reps' => ['required', 'integer', 'min:1'],
        ];
    }

    // Custom error messages
    public function messages(): array
    {
        return [
            'client_id.exists' => 'Non esiste un cliente con questo ID.',
        ];
    }
}
