<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
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
          'nom' => ['required', 'string', 'max:255'],
          'prenom' => ['required', 'string', 'max:255'],
          'image' => ['required', 'file', 'image', 'max:2048'],
          'statut' => ['required', 'in:inactif,en attente,actif'],
        ];
    }
}
