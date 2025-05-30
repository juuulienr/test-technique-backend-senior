<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles communes pour nom et prénom
     * 
     * @return array<int, string>
     */
    protected function getNameRules(bool $required = true): array
    {
        $rules = [
            'string',
            'max:255',
            'regex:/^[\p{L}\s\'-]+$/u'
        ];

        if ($required) {
            array_unshift($rules, 'required');
        } else {
            array_unshift($rules, 'sometimes');
        }

        return $rules;
    }

    /**
     * Règles communes pour les images
     * 
     * @return array<int, string>
     */
    protected function getImageRules(bool $required = true): array
    {
        $rules = [
            'image',
            'mimes:jpeg,png,jpg',
            'max:2048'
        ];

        if ($required) {
            array_unshift($rules, 'required');
        } else {
            array_unshift($rules, 'sometimes', 'file');
        }

        return $rules;
    }

    /**
     * Messages d'erreur communs
     * 
     * @return array<string, string>
     */
    protected function getCommonMessages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, des espaces, des apostrophes et des tirets.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.regex' => 'Le prénom ne peut contenir que des lettres, des espaces, des apostrophes et des tirets.',
            'image.required' => 'L\'image est obligatoire.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format JPEG, PNG ou JPG.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ];
    }

    /**
     * Préparation commune des données
     */
    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('nom')) {
            $merge['nom'] = trim($this->nom);
        }

        if ($this->has('prenom')) {
            $merge['prenom'] = trim($this->prenom);
        }

        if (!empty($merge)) {
            $this->merge($merge);
        }
    }
} 