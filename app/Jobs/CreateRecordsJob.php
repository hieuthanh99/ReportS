<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\TaskResult;
use Carbon\Carbon;
use App\Models\TaskTarget;

class CreateRecordsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::info('Start CreateRecordsJob executed at ' . now());
    
        try {
            // Lấy các TaskResult có TaskTarget isDelete = 0 và status = 'admin_approves'
            $taskResults = TaskResult::whereHas('taskTargetRelation', function($query) {
                $query->where('isDelete', 0);
            })->where('status', 'admin_approves')->get();
    
            $currentMonth = Carbon::now()->month;
            \Log::info('Month Current job: ' . $currentMonth);
            foreach ($taskResults as $taskResult) {
                if ($taskResult->taskTarget) {
                    if ($currentMonth != $taskResult->number_type) {
                        TaskResult::create([
                            'id_task_criteria' => $taskResult->id_task_criteria,
                            'document_id' => $taskResult->document_id,
                            'organization_id' => $taskResult->organization_id,
                            'number_type' => $currentMonth,
                            'type' => $taskResult->type,
                            'type_save' => $taskResult->type_save,
                            'status' => 'assign',
                            'process_code' => $taskResult->taskTarget->getProcessCode(),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            }
    
            \Log::info('END CreateRecordsJob executed at ' . now());
        } catch (\Exception $e) {
            \Log::error('Error in CreateRecordsJob: ' . $e->getMessage());
        }
    }
    
}
