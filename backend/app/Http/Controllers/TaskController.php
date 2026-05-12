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

        // Does this project_id actually belong to the user's current company?
        $projectExistsInCompany = Project::where('id', $validated['project_id'])
            ->where('company_id', $user->current_company_id)
            ->exists();

        if (!$projectExistsInCompany) {
            return response()->json(['message' => 'Invalid project for this workspace.'], 403);
        }

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'project_id' => $validated['project_id'],
            'company_id' => $user->current_company_id, // Hardcoded from Auth for safety
            'status' => $validated['status'] ?? 'todo',
            'priority' => $validated['priority'] ?? 'medium',
        ]);

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task
        ], 201);
    }
}
