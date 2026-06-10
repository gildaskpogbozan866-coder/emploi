<?php

namespace App\Http\Requests\Candidat;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ExperienceRequest extends FormRequest
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
            'poste'        => 'required|string|max:200',
            'entreprise'   => 'required|string|max:200',
            'lieu'         => 'nullable|string|max:150',
            'secteur'      => 'nullable|string|max:150',
            'date_debut'   => 'required|date|before_or_equal:today',
            'date_fin'     => 'nullable|date|after_or_equal:date_debut',
            'en_cours'     => 'boolean',
            'missions'     => 'nullable|array|max:20',
            'missions.*'   => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'poste.required'      => 'Le poste est requis.',
            'poste.string'        => 'Le poste doit être une chaîne de caractères.',
            'poste.max'           => 'Le poste ne doit pas dépasser 200 caractères.',

            'entreprise.required' => 'L\'entreprise est requise.',
            'entreprise.string'   => 'L\'entreprise doit être une chaîne de caractères.',
            'entreprise.max'      => 'L\'entreprise ne doit pas dépasser 200 caractères.',

            'lieu.string'         => 'Le lieu doit être une chaîne de caractères.',
            'lieu.max'            => 'Le lieu ne doit pas dépasser 150 caractères.',

            'secteur.string'      => 'Le secteur doit être une chaîne de caractères.',
            'secteur.max'         => 'Le secteur ne doit pas dépasser 150 caractères.',

            'date_debut.required' => 'La date de début est requise.',
            'date_debut.date'     => 'La date de début doit être une date valide.',
            'date_debut.before_or_equal' => 'La date de début doit être antérieure ou égale à aujourd\'hui.',

            'date_fin.date'       => 'La date de fin doit être une date valide.',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',

            'en_cours.boolean'    => 'Le champ en_cours doit être vrai ou faux.',

            'missions.array'      => 'Les missions doivent être un tableau.',
            'missions.max'        => 'Le nombre de missions ne doit pas dépasser 20.',
            'missions.*.string'   => 'Chaque mission doit être une chaîne de caractères.',
            'missions.*.max'      => 'Chaque mission ne doit pas dépasser 500 caractères.',
        ];
    }
}
