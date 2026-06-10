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
            'avatar'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // ── Profil étendu ──────────────────────────────────────
            'titre_professionnel' => 'nullable|string|max:200',
            'bio'                 => 'nullable|string|max:1000',
            'ville'               => 'nullable|string|max:100',
            'disponibilite'       => 'nullable|in:immediatement,1_mois,2_mois,3_mois,plus_3_mois',
            'remote'              => 'nullable|in:non,partiel,total',
            'salaire_min'         => 'nullable|integer|min:0|max:10000000',
            'salaire_max'         => 'nullable|integer|min:0|max:10000000|gte:salaire_min',
            'linkedin'            => 'nullable|url|max:500',
            'portfolio'           => 'nullable|url|max:500',

            // ── Préférences normalisées ────────────────────────────
            'types_contrat_ids'   => 'nullable|array',
            'types_contrat_ids.*' => 'integer|exists:type_contrats,id',

            'secteurs_ids'        => 'nullable|array',
            'secteurs_ids.*'      => 'integer|exists:secteurs_activite,id',

            'metiers_ids'         => 'nullable|array',
            'metiers_ids.*'       => 'integer|exists:metiers,id',

            // ── Niveaux (scalaires) ────────────────────────────────
            'niveau_etude_id'      => 'nullable|integer|exists:niveaux_etudes,id',
            'niveau_experience_id' => 'nullable|integer|exists:niveaux_experience,id',
        ];
    }

    public function messages(): array
    {
        return [
            // Identité
            'prenom.required'              => 'Le prénom est obligatoire.',
            'prenom.max'                   => 'Le prénom ne doit pas dépasser 100 caractères.',
            'nom.required'                 => 'Le nom est obligatoire.',
            'nom.max'                      => 'Le nom ne doit pas dépasser 100 caractères.',
            'tel.max'                      => 'Le téléphone ne doit pas dépasser 20 caractères.',
            'pays.max'                     => 'Le pays ne doit pas dépasser 100 caractères.',
            'avatar.image'                 => 'L\'avatar doit être une image.',
            'avatar.mimes'                 => 'Formats acceptés : jpg, jpeg, png, webp.',
            'avatar.max'                   => 'L\'avatar ne doit pas dépasser 2 Mo.',

            // Profil étendu
            'titre_professionnel.max'      => 'Le titre professionnel ne doit pas dépasser 200 caractères.',
            'bio.max'                      => 'La biographie ne doit pas dépasser 1000 caractères.',
            'ville.max'                    => 'La ville ne doit pas dépasser 100 caractères.',
            'disponibilite.in'             => 'La disponibilité sélectionnée est invalide.',
            'remote.in'                    => 'La valeur de télétravail est invalide.',
            'salaire_min.integer'          => 'Le salaire minimum doit être un entier.',
            'salaire_min.min'              => 'Le salaire minimum doit être positif.',
            'salaire_max.integer'          => 'Le salaire maximum doit être un entier.',
            'salaire_max.gte'              => 'Le salaire maximum doit être supérieur ou égal au salaire minimum.',
            'linkedin.url'                 => 'Le lien LinkedIn doit être une URL valide.',
            'portfolio.url'                => 'Le portfolio doit être une URL valide.',

            // Préférences normalisées
            'types_contrat_ids.array'      => 'Les types de contrat doivent être un tableau.',
            'types_contrat_ids.*.integer'  => 'Identifiant de contrat invalide.',
            'types_contrat_ids.*.exists'   => 'Un type de contrat sélectionné n\'existe pas.',

            'secteurs_ids.array'           => 'Les secteurs doivent être un tableau.',
            'secteurs_ids.*.integer'       => 'Identifiant de secteur invalide.',
            'secteurs_ids.*.exists'        => 'Un secteur sélectionné n\'existe pas.',

            'metiers_ids.array'            => 'Les métiers doivent être un tableau.',
            'metiers_ids.*.integer'        => 'Identifiant de métier invalide.',
            'metiers_ids.*.exists'         => 'Un métier sélectionné n\'existe pas.',

            // Niveaux
            'niveau_etude_id.exists'       => 'Le niveau d\'étude sélectionné est invalide.',
            'niveau_experience_id.exists'  => 'Le niveau d\'expérience sélectionné est invalide.',
        ];
    }
}
