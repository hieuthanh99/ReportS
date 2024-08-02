<?php
// app/Models/TaskDocument.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    ];
    public function taskResults()
    {
        return $this->hasMany(TaskResult::class, 'tasks_document_id');
    }
    // Định nghĩa mối quan hệ với Document
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    public function criteriasTask()
    {
        return $this->hasMany(CriteriasTask::class);
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
