<?php

namespace App\Http\Requests\Recruteur;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OffreRequest extends FormRequest
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
             'titre'        => 'required|string|max:200',
            'entreprise'   => 'required|string|max:200',
            'localisation' => 'required|string|max:200',
            'type'         => 'required|in:CDI,CDD,Stage,Bourse,Freelance,Temps partiel',
            'description'  => 'required|string|min:50',
            'date_limite'  => 'nullable|date|after_or_equal:today',
            'fichier'      => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'secteur'      => 'nullable|array',
            'secteur.*'    => 'string|max:100',
        ];
    }
}
