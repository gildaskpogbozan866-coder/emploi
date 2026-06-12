<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanRequest extends FormRequest
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
            'name'          => 'required|string|max:200',
            'slug'          => [
                'required', 'string', 'max:200',
                Rule::unique('plans', 'slug')->ignore($this->route('plan')),
                'regex:/^[a-z0-9\-]+$/',
            ],
            'description'   => 'nullable|string|max:1000',
            'target_type'   => 'required|in:candidat,recruteur,both',
            'price'         => 'required|integer|min:0',
            'currency'      => 'required|string|max:10',
            'duration_days' => 'nullable|integer|min:1|max:3650',
            'features'      => 'nullable|array|max:20',
            'features.*.key'   => 'nullable|string|max:100',
            'features.*.value' => 'nullable|string|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'Le slug ne peut contenir que des lettres minuscules, des chiffres et des tirets.',
            'features.*.key.required_with' => 'La clé de chaque fonctionnalité est requise.',
            'features.*.value.string' => 'La valeur de chaque fonctionnalité doit être une chaîne de caractères.',
            'features.*.value.max' => 'La valeur de chaque fonctionnalité ne peut pas dépasser 200 caractères.',
            'features.array' => 'Les fonctionnalités doivent être fournies sous forme de tableau.',
            'features.max' => 'Vous ne pouvez ajouter que 20 fonctionnalités au maximum.',
            'duration_days.integer' => 'La durée en jours doit être un nombre entier.',
            'duration_days.min' => 'La durée en jours doit être au moins de 1 jour.',
            'duration_days.max' => 'La durée en jours ne peut pas dépasser 3650 jours.',
            'price.integer' => 'Le prix doit être un nombre entier.',
            'price.min' => 'Le prix ne peut pas être négatif.', 
            
        ];
    }
}
