<?php

namespace App\Http\Requests\Candidat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\DB;

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

    /**
     * Vérifie que la compétence appartient à au moins un métier
     * déjà associé au candidat connecté.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $competenceId = (int) $this->input('competence_id');
            $candidatId   = $this->user()->id;

            // Métiers actuellement associés au candidat
            $metiersIds = DB::table('candidat_metier')
                ->where('candidat_id', $candidatId)
                ->pluck('metier_id')
                ->all();

            if (empty($metiersIds)) {
                $v->errors()->add(
                    'competence_id',
                    'Vous devez d\'abord sélectionner au moins un métier avant d\'ajouter des compétences.'
                );
                return;
            }

            $valide = DB::table('metier_competence')
                ->whereIn('metier_id', $metiersIds)
                ->where('competence_id', $competenceId)
                ->exists();

            if (!$valide) {
                $v->errors()->add(
                    'competence_id',
                    'Cette compétence n\'appartient à aucun de vos métiers sélectionnés.'
                );
            }
        });
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