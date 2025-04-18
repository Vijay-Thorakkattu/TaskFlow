<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogTaskCompleted
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskCompleted $event): void
    {
        //
        $task = $event->task;
        Log::info('Task completed Event', [
            'task_id'      => $task->id,
            'title'        => $task->title,
            'completed_at' => now()->toDateTimeString(),
        ]);
    }
}
