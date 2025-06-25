<?php

namespace App\Repositories;

use App\Models\User;
use App\DTOs\Auth\RegisterUserDTO;

interface UserRepositoryInterface
{
    public function create(RegisterUserDTO $dto): User;
    public function findByEmail(string $email): ?User;
    public function findById(int $id): ?User;
}
