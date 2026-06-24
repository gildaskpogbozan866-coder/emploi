<?php

namespace App\Http\Requests\Candidat;

use Illuminate\Foundation\Http\FormRequest;

class CompetenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'competence_id'     => 'required|integer|exists:competences,id',
            'annees_experience' => 'nullable|integer|min:0|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'competence_id.required' => 'Veuillez sélectionner une compétence.',
            'competence_id.exists'   => 'La compétence sélectionnée est invalide.',
            'annees_experience.min'  => 'Les années d\'expérience doivent être positives.',
            'annees_experience.max'  => 'Les années d\'expérience ne peuvent pas dépasser 50.',
        ];
    }
}
