<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // app/Http/Controllers/TaskController.php

    public function index(Request $request)
{
    $query = Task::where('user_id', Auth::id()); // Only fetch tasks owned by the authenticated user

    // Filter by priority
    if ($request->has('priority')) {
        $query->where('priority', $request->input('priority'));
    }

    // Filter by due date range
    if ($request->has('start_date') && $request->has('end_date')) {
        $query->whereBetween('due_date', [$request->input('start_date'), $request->input('end_date')]);
    }

    // Sort tasks
    if ($request->has('sort_by')) {
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($request->input('sort_by'), $sortOrder);
    }

    // Paginate the results
    $tasks = $query->paginate(10);

    return TaskResource::collection($tasks);
}

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'due_date' => 'required|date',
        'priority' => 'required|in:high,medium,low',
    ]);

    $task = new Task($validatedData);
    $task->user_id = Auth::id(); // Assign the task to the currently authenticated user
    $task->save();

    return response()->json($task, 201);
}

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function update(Request $request, Task $task)
    {
        $user = $request->user();

        // Ensure that the user can only update their own tasks
        if ($user->id !== $task->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validatedData = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'date',
            'priority' => 'in:high,medium,low',
        ]);

        $task->update($validatedData);

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $user = request()->user();

        // Ensure that the user can only delete their own tasks
        if ($user->id !== $task->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted']);
    }

}
