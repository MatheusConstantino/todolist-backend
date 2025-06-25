<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Todo\CreateTodoDTO;
use App\DTOs\Todo\UpdateTodoDTO;
use App\Exceptions\Todo\TodoNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Todo\CreateTodoRequest;
use App\Http\Requests\Todo\TodoFilterRequest;
use App\Http\Requests\Todo\UpdateTodoRequest;
use App\Http\Resources\TodoResource;
use App\Services\Todo\TodoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function __construct(
        private readonly TodoService $todoService
    ) {}

    public function index(TodoFilterRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $todos = $this->todoService->getUserTodos($request->user(), $filters);

        return response()->json([
            'data' => TodoResource::collection($todos->items()),
            'meta' => [
                'current_page' => $todos->currentPage(),
                'last_page' => $todos->lastPage(),
                'per_page' => $todos->perPage(),
                'total' => $todos->total(),
            ]
        ]);
    }

    public function store(CreateTodoRequest $request): JsonResponse
    {
        $dto = CreateTodoDTO::fromRequest(
            $request->validated(),
            $request->user()->id
        );

        $todo = $this->todoService->createTodo($dto);

        return response()->json([
            'message' => 'Todo created successfully',
            'data' => new TodoResource($todo)
        ], 201);
    }

    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $todo = $this->todoService->getTodoById($id, $request->user());

            return response()->json([
                'data' => new TodoResource($todo)
            ]);

        } catch (TodoNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function update(int $id, UpdateTodoRequest $request): JsonResponse
    {
        try {
            $dto = UpdateTodoDTO::fromRequest($request->validated());
            $todo = $this->todoService->updateTodo($id, $dto, $request->user());

            return response()->json([
                'message' => 'Todo updated successfully',
                'data' => new TodoResource($todo)
            ]);

        } catch (TodoNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function destroy(int $id, Request $request): JsonResponse
    {
        try {
            $this->todoService->deleteTodo($id, $request->user());

            return response()->json([
                'message' => 'Todo deleted successfully'
            ]);

        } catch (TodoNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function complete(int $id, Request $request): JsonResponse
    {
        try {
            $todo = $this->todoService->markAsCompleted($id, $request->user());

            return response()->json([
                'message' => 'Todo marked as completed',
                'data' => new TodoResource($todo)
            ]);

        } catch (TodoNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function statistics(Request $request): JsonResponse
    {
        $stats = $this->todoService->getStatistics($request->user());

        return response()->json([
            'data' => $stats
        ]);
    }
}
