<?php

namespace App\DTOs\Todo;

use Carbon\Carbon;

readonly class UpdateTodoDTO
{
    public function __construct(
        public ?string $title,
        public ?string $description,
        public ?string $status,
        public ?string $priority,
        public ?Carbon $dueDate,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
            status: $data['status'] ?? null,
            priority: $data['priority'] ?? null,
            dueDate: isset($data['due_date']) ? Carbon::parse($data['due_date']) : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->dueDate?->format('Y-m-d'),
            'completed_at' => $this->status === 'completed' ? now() : null,
        ], fn($value) => $value !== null);
    }
}