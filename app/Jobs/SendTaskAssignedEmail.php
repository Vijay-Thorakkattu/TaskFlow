<?php

namespace App\Jobs;
use App\Mail\TaskAssignedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
class SendTaskAssignedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task;
    protected $user;
    /**
     * Create a new job instance.
     */
    public function __construct($task, $user)
    {
        //
        $this->task = $task;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        
        try {
            Mail::to($this->user['email'])->send(new TaskAssignedMail($this->task, $this->user));
        } catch (\Exception $e) {
            Log::error('Failed to send email in SendTaskAssignedEmail job', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
        }
    }
}
