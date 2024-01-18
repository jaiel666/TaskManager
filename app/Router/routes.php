<?php
return [
    ['GET', '/tasks', ['App\Controllers\TaskController', 'index']],
    ['GET', '/tasks/create', ['App\Controllers\TaskController', 'create']],
    ['POST', '/tasks', ['App\Controllers\TaskController', 'store']],
    ['GET', '/tasks/{id:\d+}', ['App\Controllers\TaskController', 'show']],
    ['GET', '/tasks/{id}/edit', ['App\Controllers\TaskController', 'edit']],
    ['POST', '/tasks/{id}/update', ['App\Controllers\TaskController', 'update']],
    ['POST', '/tasks/{id}/delete', ['App\Controllers\TaskController', 'delete']],
];