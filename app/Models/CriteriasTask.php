<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CriteriasTask extends Model
{
    use HasFactory;

    protected $table = 'criterias_task';

    protected $fillable = [
        'TaskID',
        'CriteriaID',
        'CriteriaCode',
        'CriteriaName',
        'CreatedBy',
        'UpdatedBy',
        'DocumentID',
        'TaskCode',
        'RequestResult',
        'progress',               // Added field
        'progress_evaluation',    // Added field
    ];

    public function getFilePathByType($numberType, $type)
    {
        // dd($id);
        $currentYear = Carbon::now()->year;
        $taskResult =  TaskResult::where('type_save', 2)->where('number_type', $numberType)->where('type', $type)->whereYear('created_at', $currentYear)->first();

        if($taskResult){
           return  File::where('document_id', $taskResult->id_task_criteria)->where('type', 2)->first();
           // dd($file);
        }
        return null;
    }
    public function getFilePath($id)
    {
        return File::where('document_id', $id)->where('type', 2)->first();
    }
    
    public function taskResultsById($id, $numberType, $type)
    {
        $currentYear = Carbon::now()->year;
        // dd($id);
        return TaskResult::where('id_task_criteria', $id)->where('type_save', 2)->where('number_type', $numberType)->where('type', $type)->first();
    }

    public function taskResultsByNumber($numberType, $type)
    {
        $currentYear = Carbon::now()->year;
        // dd($id);
        return TaskResult::where('type_save', 2)->where('number_type', $numberType)->where('type', $type)->whereYear('created_at', $currentYear)->first();
    }

    public function getStatus()
    {
        $taskDocument = TaskDocument::Where('id', $this->TaskID)->first();
        if ($taskDocument) {
            $today = Carbon::now();
            $startDate = Carbon::parse($taskDocument->start_date);
            $endDate = Carbon::parse($taskDocument->end_date);

            if ($today->gt($endDate)) {
                return 'Quá hạn';
            } elseif ($today->gte($startDate) && $today->lte($endDate)) {
                return 'Trong hạn';
            } else {
                return 'Chưa bắt đầu';
            }
        }
        return 'Không xác định';
    }

    public function taskResults()
    {
        return $this->hasMany(TaskResult::class, 'id_task_criteria')->where('type', 2);
    }

    // Trong model Criteria
    public function taskDocument()
    {
        return $this->belongsTo(TaskDocument::class);
    }
}
