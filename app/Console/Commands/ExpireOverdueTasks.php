<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class ExpireOverdueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-overdue-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark pending task whose due_date has passed as expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $now = Carbon::now();
            $count = Task::where('status', 'pending')
                ->whereNotNull('due_date')
                ->where('due_date', '<', $now)
                ->update(['status' => 'expired']);
    
            $this->info("Expired {$count} overdue task(s).");
        } catch (\Exception $e) {
            Log::error('ExpireOverdueTasks failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

    }
}
