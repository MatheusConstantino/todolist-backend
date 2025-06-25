<?php

namespace App\Repositories;

use App\DTOs\Todo\CreateTodoDTO;
use App\DTOs\Todo\UpdateTodoDTO;
use App\Models\Todo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TodoRepositoryInterface
{
    public function create(CreateTodoDTO $dto): Todo;
    public function findById(int $id): ?Todo;
    public function findByIdAndUserId(int $id, int $userId): ?Todo;
    public function update(Todo $todo, UpdateTodoDTO $dto): Todo;
    public function delete(Todo $todo): bool;
    public function getUserTodos(int $userId, array $filters = []): LengthAwarePaginator;
}