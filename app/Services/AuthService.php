<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * @param array<string, string> $data
     */
    public function register(array $data): string
    {
        $admin = Admin::create([
          'name' => $data['name'],
          'email' => $data['email'],
          'password' => Hash::make($data['password']),
        ]);

        return $admin->createToken('auth_token')->plainTextToken;
    }

    /**
     * @param array<string, string> $data
     */
    public function login(array $data): string
    {
        $admin = Admin::where('email', $data['email'])->first();

        if (! $admin || ! Hash::check($data['password'], $admin->password)) {
            throw ValidationException::withMessages([
              'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $admin->createToken('auth_token')->plainTextToken;
    }
}
