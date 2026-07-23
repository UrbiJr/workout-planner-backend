<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('client'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $clientId = $this->route('client')->id;

        return [
            'first_name' => ['sometimes','string', 'max:255'],
            'last_name' => ['sometimes','string', 'max:255'],
            // Ignore current client when checking uniqueness
            'email' => [
                'sometimes', // valida solo se è presente nella $request
                'string',
                'max:255',
                Rule::unique('clients', 'email')->ignore($clientId),
            ],
        ];
    }
}
