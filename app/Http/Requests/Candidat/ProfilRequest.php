<?php

namespace App\Http\Requests\Candidat;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class ProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // ── Identité ───────────────────────────────────────────
            'prenom'              => 'required|string|max:100',
            'nom'                 => 'required|string|max:100',
            'tel'                 => 'nullable|string|max:20',
            'pays'                => 'nullable|string|max:100',

            // ── Profil étendu ──────────────────────────────────────
            'titre_professionnel' => 'nullable|string|max:200',
            'bio'                 => 'nullable|string|max:1000',
            'ville'               => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'prenom.required'         => 'Le prénom est obligatoire.',
            'prenom.max'              => 'Le prénom ne doit pas dépasser 100 caractères.',
            'nom.required'            => 'Le nom est obligatoire.',
            'nom.max'                 => 'Le nom ne doit pas dépasser 100 caractères.',
            'tel.max'                 => 'Le téléphone ne doit pas dépasser 20 caractères.',
            'titre_professionnel.max' => 'Le titre professionnel ne doit pas dépasser 200 caractères.',
            'bio.max'                 => 'La biographie ne doit pas dépasser 1000 caractères.',
            'ville.max'               => 'La ville ne doit pas dépasser 100 caractères.',
        ];
    }
}
