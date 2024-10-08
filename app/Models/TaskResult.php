<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Enums\TaskStatus;
use App\Enums\TaskTargetStatus;
use App\Helpers\TimeHelper;
use Illuminate\Support\Facades\Auth;

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
        'type_save', 'isDelete', 'organization_id', 'status', 'process_code'
    ];

    public function getTaskApprovalHistory()
    {
        $timeParams = TimeHelper::getTimeParameters($this->cycle_type);
        $data = TaskApprovalHistory::where('task_target_id', $this->id_task_criteria)->where('task_result_id', $this->id)->orderBy('created_at', 'desc')->first();
        return $data ?? null;
    }

    public function hasOrganizationAppro()
    {
        $user = Auth::user();
        $organizationId = $user->organization_id;
        if ($organizationId == null) return false;

        $organizationTaskId = $this->organization_id;

        if ($organizationTaskId === $organizationId) {

            if ($user->role == 'sub_admin') return true;
        }
        return false;
    }

    public function getFilePath()
    {
    
        return File::where('document_id', $this->id)->where('type', $this->type_save)->orderBy('created_at', 'desc')->first();
    }

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
    public function getStatusLabelAttributeTaskTarget()
    {

        return TaskTargetStatus::tryFrom($this->status)?->label() ?? '';
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

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

}
