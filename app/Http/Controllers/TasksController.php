<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Tasks;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();
        $tasks = $user->tasks;
        return response()->json($tasks);
    }


    public function store(StoreTaskRequest $request): JsonResponse
    {
        $fields = $request->validated();

        $user_id = Auth::id();

        $task = Tasks::query()->create([
            'title' => $fields['title'],
            'description' => $fields['description'],
            'user_id' => $user_id,
            'due_date' => $fields['due_date'] ?? Carbon::now()->addDays(10),
            'is_completed' => (bool)$fields['is_completed'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return response()->json($task, 201);


    }


    public function show(string $id): JsonResponse
    {
        $user = auth()->user();
        $task = $user->tasks()->find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task);
    }


    public function update(UpdateTaskRequest $request, string $id)
    {
        $fields = $request->validated();

        $user = auth()->user();
        $task = $user->tasks()->find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->update($request->all());
        return response()->json($task);
    }


    public function destroy(string $id)
    {
        $user = auth()->user();
        $task = $user->tasks()->find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
