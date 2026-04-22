<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ProjectStatsController extends Controller
{

    public function sync($projectId)
    {
        $key = "project:{$projectId}:stats";

        // 1. Get the "Absolute Truth" from MySQL
        $total = Task::where('project_id', $projectId)->count();
        $completed = Task::where('project_id', $projectId)
            ->where('status', 'done')
            ->count();

        // 2. Force Redis to match MySQL
        // We use HMSET to overwrite the fields 'total' and 'completed'
        Redis::hmset($key, [
            'total' => $total,
            'completed' => $completed
        ]);

        return response()->json([
            'message' => 'Project stats synchronized with database',
            'data' => [
                'total' => $total,
                'completed' => $completed
            ]
        ]);
    }

    public function showStats($projectId)
    {
        $key = "project:{$projectId}:stats";
        $stats = Redis::hgetall($key);

        $total = $stats['total'] ?? 0;
        $completed = $stats['completed'] ?? 0;

        $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

        return response()->json([
            'total_tasks' => (int) $total,
            'completed_tasks' => (int) $completed,
            'progress_percentage' => $percentage . '%'
        ]);
    }
}
