<?php

namespace App\Http\Requests\Profile;

use App\Enums\ProfileStatut;
use Illuminate\Validation\Rules\Enum;

class UpdateProfileRequest extends BaseProfileRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nom' => $this->getNameRules(false),
            'prenom' => $this->getNameRules(false),
            'image' => $this->getImageRules(false),
            'statut' => [
                'sometimes',
                new Enum(ProfileStatut::class),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return array_merge($this->getCommonMessages(), [
            'statut.enum' => 'Le statut doit être une valeur valide.',
        ]);
    }
}
