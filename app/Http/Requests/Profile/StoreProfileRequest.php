<?php

namespace App\Http\Requests\Profile;

use App\Enums\ProfileStatut;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @property string $nom
 * @property string $prenom
 * @property \Illuminate\Http\UploadedFile|null $image
 * @property ProfileStatut $statut
 */
class StoreProfileRequest extends FormRequest
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
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'nom' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s\'-]+$/u', // Lettres, espaces, apostrophes et tirets uniquement
            ],
            'prenom' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s\'-]+$/u',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
            ],
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
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, des espaces, des apostrophes et des tirets.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.regex' => 'Le prénom ne peut contenir que des lettres, des espaces, des apostrophes et des tirets.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format JPEG, PNG ou JPG.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
            'statut.required' => 'Le statut est obligatoire.',
            'statut.enum' => 'Le statut doit être une valeur valide.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('nom')) {
            $this->merge([
                'nom' => trim($this->nom),
            ]);
        }

        if ($this->has('prenom')) {
            $this->merge([
                'prenom' => trim($this->prenom),
            ]);
        }
    }
}
