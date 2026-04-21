<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessTaskNotification implements ShouldQueue
{
    use Queueable;

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
        // This is where the "Heavy Work" happens
        // We will simulate a 5-second delay (like sending an email)
        sleep(5);

        Log::info(
            "Background Job Finished: Notification sent for Task:",
            ["task_title" => $this->task->title]
            );
    }
}
