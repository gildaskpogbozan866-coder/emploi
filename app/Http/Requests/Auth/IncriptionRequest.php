<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IncriptionRequest extends FormRequest
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
           
            'prenom'     => 'required|string|max:100',
            'nom'        => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'tel'        => 'nullable|string|max:20',
            'pays'       => 'required|string|max:100',
            'role'       => 'required|in:candidat,talent,recruteur',
            'entreprise' => 'nullable|string|max:200',
            'metier'     => 'nullable|string|max:200',
      
        ];
    }
    public function messages()
    {
        return [
            'prenom.required' => 'Le prénom est requis.',
            'nom.required' => 'Le nom est requis.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'email doit être une adresse email valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'pays.required' => 'Le pays est requis.',
            'role.required' => 'Le rôle est requis.',
            'role.in' => 'Le rôle doit être l\'un des suivants : candidat, talent, recruteur.',
        ];
    }
}
