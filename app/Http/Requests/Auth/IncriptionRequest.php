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
            'prenom'               => 'required|string|max:100',
            'nom'                  => 'required|string|max:100',
            'email'                => 'required|email|unique:users,email',
            'password'             => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
            'tel'                  => 'nullable|string|max:20',
            'pays'                 => 'required|string|max:100',
            'role'                 => 'required|in:candidat,recruteur,annonceur',
            'entreprise'           => 'nullable|string|max:200',
            'metier'               => 'nullable|string|max:200',
        ];
    }
    public function messages()
    {
        return [
            'prenom.required'              => 'Le prénom est requis.',
            'nom.required'                 => 'Le nom est requis.',
            'email.required'               => 'L\'adresse e-mail est requise.',
            'email.email'                  => 'L\'adresse e-mail n\'est pas valide.',
            'email.unique'                 => 'Cette adresse e-mail est déjà utilisée.',
            'password.required'            => 'Le mot de passe est requis.',
            'password.min'                 => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed'           => 'Les mots de passe ne correspondent pas.',
            'password_confirmation.required' => 'Veuillez confirmer votre mot de passe.',
            'pays.required'                => 'Le pays est requis.',
            'role.required'                => 'Le rôle est requis.',
            'role.in'                      => 'Le rôle doit être : candidat, recruteur ou annonceur.',
        ];
    }
}
