<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaskAssignmentController extends Controller
{
    public function assign(Request $request, $taskId)
    {
        // Find the task
        $task = Task::findOrFail($taskId);

        // Create the lock (Targeting this specific task)
        // We allow the lock to exist for 10 seconds max
        $lock = Cache::lock("assign_task_{$taskId}", 10);

        // Try to get the lock.
        // ->block(3) means: "If it's locked, wait up to 3 seconds before giving up"
        if ($lock->block(3)) {
            try {
                $task->update([
                    'assigned_to' => $request->input('user_id')
                ]);

                return response()->json([
                    'message' => 'Task successfully assigned to ' . User::find($request->user_id)->name,
                    'task' => $task->title
                ]);
            } finally {
                // IMPORTANT: Always release the key so the next person can use it
                $lock->release();
            }
        }

        return response()->json([
            'error' => 'This task is currently being modified by another manager. Please try again.'
        ], 423);
    }
}
