<?php

namespace App\Models;

class Task
{
    private int $id;
    private string $taskName;
    private string $taskDescription;
    private string $createdAt;

    public function __construct(int $id, string $taskName, string $taskDescription, string $createdAt)
    {
        $this->id = $id;
        $this->taskName = $taskName;
        $this->taskDescription = $taskDescription;
        $this->createdAt = $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTaskName(): string
    {
        return $this->taskName;
    }

    public function getTaskDescription(): string
    {
        return $this->taskDescription;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
