<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AccountDeactivatedException;

class AuthService
{
    public function registerUser(array $validatedData): User
    {
        return User::create($validatedData);
    }

    public function generateToken(User $user): string
    {
        return $user->createToken('Personal Access Token')->accessToken;
    }

    public function authenticateUser(array $userCredentials): ?User
    {
        $user = User::where('email', $userCredentials['email'])->first();

        if (!$user) {
            return null;
        }

        if (!$user->isActive()) {
            throw new AccountDeactivatedException();
        }

        if (!\Illuminate\Support\Facades\Hash::check($userCredentials['password'], $user->password)) {
            return null;
        }

        return $user;
    }

    public function getAuthUserInformation(): User
    {
        $user = User::with('driver')->findOrFail(Auth::user()->id);

        return $user;
    }

    public function updateAuthUserInformation(array $data): User
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->update($data);

        return $user;
    }

    public function logoutUser(): bool
    {
        try {
            $user = Auth::user();

            if ($user && $user->tokens()) {
                $user->tokens()->delete();
            }

            Auth::logout();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function userExists(string $email): bool
    {
        return User::where('email', $email)->exists();
    }

    public function findUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
