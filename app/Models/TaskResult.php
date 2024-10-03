<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TaskResult extends Model
{
    use HasFactory;
    protected $table = 'task_result';
    protected $fillable = [
        'id_task_criteria',
        'document_id',
        'result',
        'description',
        'number_type',
        'type',
        'type_save', 'isDelete'
    ];
    public function taskTarget()
    {
        return $this->belongsTo(TaskTarget::class, 'id_task_criteria');
    }
    public function taskDocument()
    {
        return $this->belongsTo(TaskDocument::class, 'id_task_criteria')->where('type', 1);
    }
    public static function getCycleTypes()
    {
        return [
            '1' => 'tuần',
            '2' => 'tháng',
            '3' => 'quý',
            '4' => 'năm'
        ];
    }

    // Phương thức để lấy giá trị cycle_type dưới dạng văn bản
    public function getCycleTypeTextAttribute()
    {
        $cycleTypes = self::getCycleTypes();
        return $cycleTypes[$this->type] ?? 'Không xác định';
    }
    /**
     * Get the criteria task that owns the task result.
     */
    public function criteriaTask()
    {
        return $this->belongsTo(CriteriasTask::class, 'id_task_criteria')->where('type', 2);
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    
}
