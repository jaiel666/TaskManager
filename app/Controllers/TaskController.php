<?php

namespace App\Controllers;

use App\Collections\TaskCollection;
use App\Models\Task;
use App\RedirectResponse;
use App\ViewResponse;
use Carbon\Carbon;
use Doctrine\DBAL\Connection;

class TaskController
{
    protected Connection $database;

    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    public function index(): ViewResponse
    {
        $tasks = $this->database->createQueryBuilder()
            ->select('*')
            ->from('tasks')
            ->fetchAllAssociative();

        $tasksCollection = new TaskCollection();

        foreach ($tasks as $task) {
            $tasksCollection->add(new Task(
                (int)$task['id'],
                (string)$task['task_name'],
                (string)$task['task_description'],
                $task['created_at']
            ));
        }

        return new ViewResponse('tasks/index', [
            'tasks' => $tasksCollection->getAll()
        ]);
    }

    public function show(int $id): ViewResponse
    {
        $data = $this->database->createQueryBuilder()
            ->select('*')
            ->from('tasks')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->fetchAssociative();

        if (!$data) {
            return new ViewResponse('errors/not_found', [
                'message' => 'Task not found with ID ' . $id,
            ]);
        }

        $task = new Task(
            (int)$data['id'],
            (string)$data['task_name'],
            (string)$data['task_description'],
            $data['created_at']
        );

        return new ViewResponse('tasks/show', [
            'task' => $task
        ]);
    }


    public function create(): ViewResponse
    {
        return new ViewResponse('tasks/create');
    }

    public function store(): RedirectResponse
    {
        $this->database->createQueryBuilder()
            ->insert('tasks')
            ->values(
                [
                    'task_name' => ':task_name',
                    'task_description' => ':task_description',
                    'created_at' => ':created_at',
                ]
            )->setParameters([
                'task_name' => $_POST['task_name'],
                'task_description' => $_POST['task_description'],
                'created_at' => Carbon::now()
            ])->executeQuery();

        return new RedirectResponse('/tasks');
    }

    public function edit(int $id): ViewResponse
    {
        $data = $this->database->createQueryBuilder()
            ->select('*')
            ->from('tasks')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->fetchAssociative();

        if (!$data) {
            return new ViewResponse('errors/not_found', [
                'message' => 'Task not found with ID ' . $id,
            ]);
        }


        $task = new Task(
            (int)$data['id'],
            (string)$data['task_name'],
            (string)$data['task_description'],
            $data['created_at']
        );

        return new ViewResponse('tasks/edit', [
            'task' => $task
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $this->database->createQueryBuilder()
            ->update('tasks')
            ->set('task_name', ':task_name')
            ->set('task_description', ':task_description')
            ->where('id = :id')
            ->setParameters([
                'id' => $id,
                'task_name' => $_POST['task_name'],
                'task_description' => $_POST['task_description'],
            ])->executeQuery();

        return new RedirectResponse('/tasks/' . $id);
    }

    public function delete(int $id): RedirectResponse
    {
        $this->database->createQueryBuilder()
            ->delete('tasks')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();

        return new RedirectResponse('/tasks');
    }
}
