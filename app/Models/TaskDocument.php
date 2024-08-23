<?php
// app/Models/TaskDocument.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class TaskDocument extends Model
{
    // Bảng tương ứng với model này
    protected $table = 'tasks_document';

    // Các thuộc tính có thể được gán hàng loạt
    protected $fillable = [
        'document_id',
        'task_code',
        'task_name',
        'reporting_cycle',
        'category',
        'required_result',
        'start_date',
        'end_date',
        'creator',
        'status',
        'progress',               // Added field
        'progress_evaluation',    // Added field
        'organization_id'

    ];

    public function getStatus()
    {
        $today = Carbon::now();
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);

        if ($today->gt($endDate)) {
            return 'Quá hạn';
        } elseif ($today->gte($startDate) && $today->lte($endDate)) {
            return 'Trong hạn';
        } else {
            return 'Chưa bắt đầu';
        }
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function taskResults()
    {
        return $this->hasMany(TaskResult::class, 'id_task_criteria')->where('type', 1);
    }
    public function getFilePathByType($numberType, $type)
    {
        // dd($id);
        $currentYear = Carbon::now()->year;
        $taskResult =  TaskResult::where('type_save', 1)->where('number_type', $numberType)->where('type', $type)->whereYear('created_at', $currentYear)->first();

        if($taskResult){
           return  File::where('document_id', $taskResult->id_task_criteria)->where('type', 1)->first();
           // dd($file);
        }
        return null;
    }
    public function getFile()
    {
        return File::where('document_id', $this->id)->where('type', 3)->get();
    }
    public function taskResultsById($id, $numberType, $type)
    {
        $currentYear = Carbon::now()->year;
        // dd($id);
        return TaskResult::where('id_task_criteria', $id)->where('type_save', 1)->where('number_type', $numberType)->where('type', $type)->first();
    }

    public function taskResultsByNumber($numberType, $type)
    {
        $currentYear = Carbon::now()->year;
        // dd($id);
        return TaskResult::where('type_save', 1)->where('number_type', $numberType)->where('type', $type)->whereYear('created_at', $currentYear)->first();
    }

    // Định nghĩa mối quan hệ với Document
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    public function criteriasTask()
    {
        return $this->hasMany(CriteriasTask::class, 'TaskID');
    }

    public function getCycleAttribute()
    {
        switch ($this->reporting_cycle) {
            case 1:
                return 'Theo tuần';
            case 2:
                return 'Theo tháng';
            case 3:
                return 'Theo quý';
            case 4:
                return 'Theo năm';
            default:
                return 'không xác định';
        }
    }

    public function getStatusAttribute()
    {
        switch ($this->attributes['status']) {
            case 'draft':
                return 'Mới tạo';
            case 'assign':
                return 'Đã giao việc';
            case 'done':
                return 'Đã hoàn thành chu kỳ';
            default:
                return 'không xác định';
        }
    }
    
    public static function getCriteriasByDocumentAndTask($documentId, $taskCode)
    {
        return CriteriasTask::where('DocumentID', $documentId)
                            ->where('TaskCode', $taskCode)
                            ->get();
    }
    
}
