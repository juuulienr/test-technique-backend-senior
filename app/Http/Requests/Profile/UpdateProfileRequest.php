<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
          'nom' => ['sometimes', 'string', 'max:255'],
          'prenom' => ['sometimes', 'string', 'max:255'],
          'image' => ['sometimes', 'file', 'image', 'max:2048'],
          'statut' => ['sometimes', 'in:inactif,en attente,actif'],
        ];
    }
}
