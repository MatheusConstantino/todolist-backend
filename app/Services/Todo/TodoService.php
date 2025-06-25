<?php

namespace App\Services\Todo;

use App\DTOs\Todo\CreateTodoDTO;
use App\DTOs\Todo\UpdateTodoDTO;
use App\Exceptions\Todo\TodoNotFoundException;
use App\Exceptions\Todo\UnauthorizedTodoAccessException;
use App\Models\Todo;
use App\Models\User;
use App\Repositories\TodoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TodoService
{
    public function __construct(
        private readonly TodoRepositoryInterface $todoRepository
    ) {}

    public function createTodo(CreateTodoDTO $dto): Todo
    {
        return $this->todoRepository->create($dto);
    }

    public function getUserTodos(User $user, array $filters = []): LengthAwarePaginator
    {
        return $this->todoRepository->getUserTodos($user->id, $filters);
    }

    public function getTodoById(int $id, User $user): Todo
    {
        $todo = $this->todoRepository->findByIdAndUserId($id, $user->id);

        if (!$todo) {
            throw new TodoNotFoundException('Todo not found');
        }

        return $todo;
    }

    public function updateTodo(int $id, UpdateTodoDTO $dto, User $user): Todo
    {
        $todo = $this->getTodoById($id, $user);
        
        return $this->todoRepository->update($todo, $dto);
    }

    public function deleteTodo(int $id, User $user): void
    {
        $todo = $this->getTodoById($id, $user);
        
        $this->todoRepository->delete($todo);
    }

    public function markAsCompleted(int $id, User $user): Todo
    {
        $todo = $this->getTodoById($id, $user);
        
        if ($todo->isCompleted()) {
            return $todo; // Já está completo
        }

        $updateDto = new UpdateTodoDTO(
            title: null,
            description: null,
            status: 'completed',
            priority: null,
            dueDate: null
        );

        return $this->todoRepository->update($todo, $updateDto);
    }

    public function getStatistics(User $user): array
    {
        $todos = $this->todoRepository->getUserTodos($user->id, ['per_page' => 1000]);
        
        $total = $todos->total();
        $completed = $todos->where('status', 'completed')->count();
        $pending = $todos->where('status', 'pending')->count();
        $inProgress = $todos->where('status', 'in_progress')->count();
        $overdue = $todos->filter(fn($todo) => $todo->isOverdue())->count();

        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $pending,
            'in_progress' => $inProgress,
            'overdue' => $overdue,
            'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0,
        ];
    }
}