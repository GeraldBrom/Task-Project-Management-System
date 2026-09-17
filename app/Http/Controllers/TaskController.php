<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Получение всех задач

    public function index() {
        $tasks = Task::where('user_id', auth()->id())
            ->with('user')
            ->get();

        return response()->json($tasks);
    }

    // Создание задач
    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|max:255',
        ]);

        $validated['user_id'] = auth()->id();

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    //Обновление задачи
    public function update(Request $request, Task $task) {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['message' => 'Доступ запрещён'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|max:255',
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    // Удаление задачи
    public function destroy($id) {
        $task = Task::where('user_id', auth()->id())->find($id);

        if (!$task) {
            return response()->json(['message' => 'Задача не найдена'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Задача удалена'], 200);
    }
}
