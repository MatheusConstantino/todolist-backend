<?php

namespace App\Services\Auth;

use App\DTOs\Auth\LoginUserDTO;
use App\DTOs\Auth\RegisterUserDTO;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\UserAlreadyExistsException;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenService $tokenService,
    ) {}

    public function register(RegisterUserDTO $dto): array
    {
        if ($this->userRepository->findByEmail($dto->email)) {
            throw new UserAlreadyExistsException('User with this email already exists');
        }

        $user = $this->userRepository->create($dto);
        $token = $this->tokenService->createToken($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(LoginUserDTO $dto): array
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw new InvalidCredentialsException('Invalid credentials provided');
        }

        // Revogar tokens anteriores por segurança
        $this->tokenService->revokeAllTokens($user);
        
        $token = $this->tokenService->createToken($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $this->tokenService->revokeCurrentToken($user);
    }
}