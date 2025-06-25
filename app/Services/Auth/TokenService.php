<?php

namespace App\Services\Auth;

use App\Models\User;

class TokenService
{
    private const TOKEN_NAME = 'auth-token';

    public function createToken(User $user): string
    {
        return $user->createToken(self::TOKEN_NAME)->plainTextToken;
    }

    public function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    public function revokeCurrentToken(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}