<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExerciseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('exercise'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $exerciseId = $this->route('exercise')->id;

        return [
            'is_active' => ['sometimes', 'bool'],
            'description' => ['sometimes', 'string', 'max:1024'],
            // Ignore current client when checking uniqueness
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('exercises', 'name')->ignore($exerciseId),
            ],
        ];
    }
}
