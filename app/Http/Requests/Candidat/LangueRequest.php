<?php

namespace App\Http\Requests\Candidat;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LangueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'langue_id' => 'required|exists:langues,id',
            'niveau_id' => 'required|exists:niveaux_langue,id',
        ];
    }

    public function messages(): array
    {
        return [
            'langue_id.required' => 'Le champ langue est obligatoire.',
            'langue_id.exists' => 'La langue sélectionnée est invalide.',
            'niveau_id.required' => 'Le champ niveau est obligatoire.',
            'niveau_id.exists' => 'Le niveau sélectionné est invalide.',
        ];
    }
}
