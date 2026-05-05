<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendAssignmentNotification implements ShouldQueue
{
use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     */
    public function __construct(
        public Task $task
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $user = User::find($this->task->assigned_to);

        if ($user) {
            Log::info("Notification: Task '{$this->task->title}' assigned to {$user->name}");

            // This makes the background worker wait, but NOT the user!
            sleep(3);

            Log::info("Notification sent for Task ID: {$this->task->id}");
        }
    }
}
