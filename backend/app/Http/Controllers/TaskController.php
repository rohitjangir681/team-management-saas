<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index(Request $request)
    {

        $tasks = Task::with('project')
            ->search($request->search)
            ->priority($request->priority)
            ->status($request->status)
            ->latest()
            ->paginate(10);

        // Our Multitenantable trait automatically handles the where('company_id')
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'description' => 'nullable|string',
            'priority' => 'in:low,medium,high',
            'status' => 'in:todo,in_progress,done',
        ]);


        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'project_id' => $validated['project_id'],
            'status' => $validated['status'] ?? 'todo',
            'priority' => $validated['priority'] ?? 'medium',
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task->load('project')
        ], 201);
    }
}
