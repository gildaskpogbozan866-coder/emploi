<?php

namespace App\Http\Requests\Candidat;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FormationRequest extends FormRequest
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
            'diplome'       => 'required|string|max:200',
            'etablissement' => 'required|string|max:200',
            'domaine'       => 'nullable|string|max:150',
            'date_debut'    => 'required|date|before_or_equal:today',
            'date_fin'      => 'nullable|date|after_or_equal:date_debut',
            'en_cours'      => 'boolean',
            'activites'     => 'nullable|array|max:20',
            'activites.*'   => 'nullable|string|max:500',
        ];
    }

    public function messages(){
        return [
            'diplome.required'       => 'Le diplôme est requis.',
            'diplome.string'         => 'Le diplôme doit être une chaîne de caractères.',
            'diplome.max'            => 'Le diplôme ne doit pas dépasser 200 caractères.',

            'etablissement.required' => 'L\'établissement est requis.',
            'etablissement.string'   => 'L\'établissement doit être une chaîne de caractères.',
            'etablissement.max'      => 'L\'établissement ne doit pas dépasser 200 caractères.',

            'domaine.string'         => 'Le domaine doit être une chaîne de caractères.',
            'domaine.max'            => 'Le domaine ne doit pas dépasser 150 caractères.',

            'date_debut.required'    => 'La date de début est requise.',
            'date_debut.date'        => 'La date de début doit être une date valide.',
            'date_debut.before_or_equal' => 'La date de début doit être antérieure ou égale à aujourd\'hui.',

            'date_fin.date'          => 'La date de fin doit être une date valide.',
            'date_fin.after_or_equal'=> 'La date de fin doit être postérieure ou égale à la date de début.',

            'en_cours.boolean'       => 'Le champ en cours doit être vrai ou faux.',

            'activites.array'        => 'Les activités doivent être un tableau.',
            'activites.max'          => 'Le nombre d\'activités ne doit pas dépasser 20.',
            'activites.*.string'     => 'Chaque activité doit être une chaîne de caractères.',
            'activites.*.max'        => 'Chaque activité ne doit pas dépasser 500 caractères.',
        ];
    }
}
