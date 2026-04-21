<?php

namespace App\Observers;

use App\Jobs\ProcessTaskNotification;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        $user = Auth::user();

        $activity = [
            'user' => $user ? $user->name : 'System',
            'action' => 'created task',
            'subject' => $task->title,
            'time' => now()->format('H:i:s'),
        ];

        $cacheKey = "company:{$task->company_id}:activity";

        // 1. Push the new activity to the front of the list
        Redis::lpush($cacheKey, json_encode($activity));

        // 2. Keep only the latest 20 activities (prevents Redis memory bloat)
        Redis::ltrim($cacheKey, 0, 19);

        // Dispatch the Background Job
        ProcessTaskNotification::dispatch($task);
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
