<?php

namespace App\UI\Http\Requests\Profile;

use App\Domain\ValueObjects\ProfileStatut;
use Illuminate\Validation\Rules\Enum;

/**
 * @property string $nom
 * @property string $prenom
 * @property \Illuminate\Http\UploadedFile $image
 * @property string $statut
 */
class StoreProfileRequest extends BaseProfileRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nom' => $this->getNameRules(true),
            'prenom' => $this->getNameRules(true),
            'image' => $this->getImageRules(true),
            'statut' => [
                'required',
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
            'statut.required' => 'Le statut est obligatoire.',
            'statut.enum' => 'Le statut doit être une valeur valide.',
        ]);
    }
}
