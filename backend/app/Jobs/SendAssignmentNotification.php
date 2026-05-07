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
use Throwable;

class SendAssignmentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = 30;

    /**
     * The number of seconds the job can run before timing out.
     */
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Task $task
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $this->task->refresh();

        $user = User::find($this->task->assigned_to);

        if (!$user) {
            // If the user doesn't exist, retrying won't help.
            // We "fail" the job manually.
            $this->fail(new \Exception("Assignee not found for Task ID: {$this->task->id}"));
            return;
        }

        Log::info("Notification: Task '{$this->task->title}' assigned to {$user->name}");

        // This makes the background worker wait, but NOT the user!
        sleep(3);

        Log::info("Notification sent for Task ID: {$this->task->id}");
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error("JOB PERMANENTLY FAILED: " . $exception->getMessage());
    }
}
