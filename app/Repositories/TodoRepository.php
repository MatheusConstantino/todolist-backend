<?php

namespace App\Repositories;

use App\DTOs\Todo\CreateTodoDTO;
use App\DTOs\Todo\UpdateTodoDTO;
use App\Models\Todo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TodoRepository implements TodoRepositoryInterface
{
    public function create(CreateTodoDTO $dto): Todo
    {
        return Todo::create([
            'user_id' => $dto->userId,
            'title' => $dto->title,
            'description' => $dto->description,
            'priority' => $dto->priority,
            'due_date' => $dto->dueDate,
        ]);
    }

    public function findById(int $id): ?Todo
    {
        return Todo::find($id);
    }

    public function findByIdAndUserId(int $id, int $userId): ?Todo
    {
        return Todo::where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function update(Todo $todo, UpdateTodoDTO $dto): Todo
    {
        $todo->update($dto->toArray());
        return $todo->fresh();
    }

    public function delete(Todo $todo): bool
    {
        return $todo->delete();
    }

    public function getUserTodos(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = Todo::where('user_id', $userId);

        // Aplicar filtros
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['overdue']) && $filters['overdue']) {
            $query->where('due_date', '<', now())
                  ->where('status', '!=', 'completed');
        }

        // Ordenação
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($filters['per_page'] ?? 15);
    }
}