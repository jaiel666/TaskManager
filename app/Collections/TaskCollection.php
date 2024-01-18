<?php

namespace App\Collections;

use App\Models\Task;

class TaskCollection
{
    private array $tasks = [];

    public function add(Task $task): void
    {
        $this->tasks[] = $task;
    }

    public function getAll(): array
    {
        return $this->tasks;
    }
}
