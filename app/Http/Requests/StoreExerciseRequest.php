<?php

namespace App\Http\Requests;

use App\Models\Exercise;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreExerciseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Exercise::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'is_active' => ['bool'],
            'name' => ['required', 'string', 'unique:exercises,name', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }

    // Custom error messages
    public function messages(): array
    {
        return [
            'name.unique' => 'Esiste già un esercizio con questo nome.',
        ];
    }
}
