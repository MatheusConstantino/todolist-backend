<?php

namespace App\DTOs\Todo;

use Carbon\Carbon;

readonly class CreateTodoDTO
{
    public function __construct(
        public int $userId,
        public string $title,
        public ?string $description,
        public string $priority,
        public ?Carbon $dueDate,
    ) {}

    public static function fromRequest(array $data, int $userId): self
    {
        return new self(
            userId: $userId,
            title: $data['title'],
            description: $data['description'] ?? null,
            priority: $data['priority'] ?? 'medium',
            dueDate: isset($data['due_date']) ? Carbon::parse($data['due_date']) : null,
        );
    }
}