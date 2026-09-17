<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function store(Request $request, $id) {
        $task = Task::where('user_id', auth()->id())->find($id);

        if (!$task) {
            return response()->json(['message' => 'Доступ запрещён'], 403);
        }

        if ($task->status === 'completed') {
            return response()->json([
                'message' => 'Нельзя установить напоминание для завершённой задачи'
            ], 422);
        }

        $validated = $request->validate([
            'reminder_at' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $reminderAt = Carbon::createFromFormat('Y-m-d\TH:i', $validated['reminder_at']);

        if ($reminderAt <= now()) {
            return response()->json([
                'message' => 'Напоминание должно быть установлено на будущее время'
            ], 422);
        }

        DB::transaction(function () use ($task, $reminderAt) {
            $activeRemindersCount = Task::where('user_id', auth()->id())
                ->whereNotNull('reminder_at')
                ->where('reminder_at', '>', now())
                ->where('status', '!=', 'completed')
                ->where('id', '!=', $task->id)
                ->lockForUpdate()
                ->count();

            if ($activeRemindersCount >= 3) {
                throw new QueryException('Превышен лимит напоминаний', 409);
            }

            $task->reminder_at = $reminderAt;
            $task->save();
        });

        return response()->json($task);
    }

    public function destroy(Request $request, $id) {
        $task = Task::where('user_id', auth()->id())->find($id);

        if (!$task) {
            return response()->json(['message' => 'Доступ запрещён'], 403);
        }

        $task->reminder_at = null;
        $task->save();

        return response()->json(['message' => 'Напоминание удалено']);
    }
}
