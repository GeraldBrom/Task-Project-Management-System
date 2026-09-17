<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
            'reminder_at' => 'nullable|date',
        ]);

        if (isset($validated['reminder_at'])) {
            $reminderAt = Carbon::parse($validated['reminder_at']);

            if ($reminderAt <= now()->addMinutes(15)) {
                return response()->json([
                    'message' => 'Напоминание должно быть установлено минимум на 15 минут вперёд'
                ], 422);
            }
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    //Обновление задачи
    public function update(Request $request, $id) {
        $task = Task::where('user_id', auth()->id())->find($id);

        if (!$task) {
            return response()->json(['message' => 'Доступ запрещён'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|max:255',
            'description' => 'sometimes|nullable|max:255',
            'status' => 'sometimes|in:pending,completed',
            'reminder_at' => 'sometimes|nullable|date_format:Y-m-d\TH:i',
        ]);

        // Обработка reminder_at
        if ($request->has('reminder_at')) {
            if ($validated['reminder_at'] === null) {
                $task->reminder_at = null;
            } else {
                if ($task->status === 'completed') {
                    return response()->json([
                        'message' => 'Нельзя установить напоминание для завершённой задачи'
                    ], 422);
                }

                $reminderAt = Carbon::createFromFormat('Y-m-d\TH:i', $validated['reminder_at']);
                if ($reminderAt <= now()) {
                    return response()->json([
                        'message' => 'Напоминание должно быть установлено на будущее время'
                    ], 422);
                }

                $task->reminder_at = $reminderAt;
            }
        }

        unset($validated['reminder_at']);
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
