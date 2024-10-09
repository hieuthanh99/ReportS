<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\TimeHelper;
use App\Enums\TaskStatus;
use App\Enums\TaskTargetStatus;
use App\Enums\TaskTargetStatusChange;



class TaskTarget extends Model
{
    use HasFactory;

    protected $table = 'task_target';

    protected $fillable = [
        'document_id',
        'code',
        'name',
        'cycle_type',
        'category_id',
        'request_results',
        'start_date',
        'end_date',
        'creator',
        'status',
        'results',
        'description',
        'organization_id',
        'type',
        'is_completed',
        'type_id',
        'status_code',
        'isDelete',
        'result_type',
        'unit',      // Đơn vị
        'target_type', // Loại chỉ tiêu
        'task_type',   // Loại nhiệm vụ
        'target',      // Chỉ tiêu
        'request_results_task',
        'results_task',
        'issuing_organization_id',
        'slno'
    ];
    public function taskResultsRelation()
    {
        return $this->hasMany(TaskResult::class, 'id_task_criteria'); 
    }
    public function getStatusLabel()
    {
        $today = Carbon::now();
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);
        if ($this->status === 'complete') {
            if ($endDate->gt($today)) {
                return "Hoàn thành đúng hạn";
            } else {
                return "Hoàn thành quá hạn";
            }
        }
        // Nếu trạng thái là "new", "reject", "staff_complete", "sub_admin_complete", "assign"
        if ($this->status === 'processing') {
            if ($today->between($startDate, $endDate)) {
                return "Đang thực hiện";
            } elseif ($today->gt($endDate)) {
                return "Quá hạn";
            }
        }
        if ($this->status === 'new') {
           if ($endDate->diffInDays($today) <= 30) {
                return "Sắp tới hạn";
            }
        }
        return "Trạng thái không xác định";
    }
    public function getCurrentCycle()
    {
        // dd($this->cycle_type);
        $data=  TimeHelper::getTimeParameters((int)$this->cycle_type);

    }

    public function hasCompletedTask()
    {
        return $this->contains('status', 'staff_complete');
    }

    public function getUnitName()
    {
        $typeTask = Unit::where('id', $this->unit)->first();
        return $typeTask->name ?? '';
    }
    public function getGroupName()
    {


        $typeTask = IndicatorGroup::where('isDelete', 0)->where('id', $this->type_id)->first();
        if ($this->type == 'task') {
            $typeTask =  TaskGroup::where('isDelete', 0)->where('id', $this->type_id)->first();
        }
        return $typeTask->name ?? '';
    }

    public function taskResults()
    {

        if (isset($this->id)) {
            return TaskResult::where('id_task_criteria', $this->id)->count();
        }
        return 0;
    }

    public function countOrganization()
    {
        return TaskResult::where('id_task_criteria', $this->id)->count();
    }
    public function latestTaskResult()
    {
        $currentYear = now()->year;
        return TaskResult::where('id_task_criteria', $this->id)->where('type_save', self::getType())
            ->whereYear('created_at', $currentYear)
            ->orderBy('created_at', 'desc')->first();
    }
    public function getProcessCode()
    {
        $now = now();
        if ($this->is_completed) {
            if ($this->end_date >= $now) {
                return TaskStatus::COMPLETED_IN_TIME->value;
            } else {
                return TaskStatus::COMPLETED_OVERDUE->value;
            }
        } else {
            if ($this->end_date >= $now) {
                return TaskStatus::IN_PROGRESS_IN_TIME->value;
            } else {
                return TaskStatus::IN_PROGRESS_OVERDUE->value;
            }
        }
    }
    public function getTaskStatusDescription()
    {

        // Chuyển đổi giá trị status_code thành enum
        $status = TaskStatus::tryFrom($this->status_code);

        if ($status) {
            // Lấy mô tả từ phương thức label() của enum
            return $status->label();
        }

        // Nếu giá trị không hợp lệ, trả về mô tả mặc định hoặc thông báo lỗi
        return '';
    }
    public function scopeSearch($query, $searchTerm = null)
    {
        if ($searchTerm) {
            $query
                ->select('task_target.*')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('task_target.name', 'like', '%' . $searchTerm . '%')
                        ->where('isDelete', 0)
                        ->orWhere('task_target.code', 'like', '%' . $searchTerm . '%');
                    // ->orWhere('documents.name', 'like', '%' . $searchTerm . '%')
                    // ->orWhere('organizations.name', 'like', '%' . $searchTerm . '%');
                });
        }

        return $query;
    }


    public function getStatusLabelAttributeTaskTarget()
    {

        return TaskTargetStatus::tryFrom($this->status)?->label() ?? '';
    }
    public function getStatusTaskTarget()
    {

        return TaskTargetStatusChange::tryFrom($this->status)?->label() ?? '';
    }
    
    public function getStatusLabelAttribute()
    {
        return TaskStatus::tryFrom($this->status_code)?->label() ?? '';
    }
    public function getType()
    {
        if ($this->type == 'task') {
            return 1;
        }
        return 0;
    }
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
    public function getFilePath()
    {
        if ($this->type == 'target') $type = 2;
        else $type = 1;
        return File::where('document_id', $this->id)->where('type', $type)->orderBy('created_at', 'desc')->first();
    }
    public function taskResultsById($numberType)
    {

        $currentYear = Carbon::now()->year;
        return TaskResult::where('id_task_criteria', $this->id)->where('type_save', self::getType())->where('number_type', $numberType)->where('type', $this->cycle_type)->first();
    }


    public function taskResultsByIdTaskTarget()
    {
        $currentYear = Carbon::now()->year;
        return TaskResult::where('id_task_criteria', $this->id)->where('type_save', self::getType())->where('type', $this->cycle_type)->orderBy('created_at', 'desc')->first();
    }

    //TaskApprovalHistory

    public function getTaskApprovalHistory()
    {
        $timeParams = TimeHelper::getTimeParameters($this->cycle_type);
        $taskResult = TaskResult::where('id_task_criteria', $this->id)->where('type', $this->cycle_type)->where('number_type', (int)$timeParams)->first();
        if ($taskResult) {

            $data = TaskApprovalHistory::where('task_target_id', $this->id)->where('task_result_id', $taskResult->id)->orderBy('created_at', 'desc')->first();
            // dd($data);
        }
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

    public function getFilePathByType($numberType)
    {
        // dd($id);
        $currentYear = Carbon::now()->year;
        $taskResult =  TaskResult::where('type_save', self::getType())->where('number_type', $numberType)->where('type', $this->cycle_type)->whereYear('created_at', $currentYear)->first();
        if ($taskResult) {
            return  File::where('document_id', $taskResult->id_task_criteria)->where('type', 1)->first();
            // dd($file);
        }
        return null;
    }

    public function taskResultsByNumber($numberType)
    {
        $currentYear = Carbon::now()->year;
        // dd($id);
        return TaskResult::where('type_save', self::getType())->where('number_type', $numberType)->where('type', $this->cycle_type)->whereYear('created_at', $currentYear)->first();
    }
    public function unit()
    {
        return Unit::where('id', $this->unit)->first();
    }


    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    

    public function getListResults(){
        return TaskResult::where('type_save', $this->type)->where('id_task_criteria', $this->id)->paginate(10);
    }
    

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }


    public function issuingDepartment()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function getGroupTaskTarget()
    {
        if($this->type == 'task'){
            return TaskGroup::where('id', $this->type_id)->where('isDelete', 0)->first();
        } 
        return IndicatorGroup::where('id', $this->type_id)->where('isDelete', 0)->first();
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
        return $cycleTypes[$this->cycle_type] ?? 'Không xác định';
    }

    public static function getTypes()
    {
        return [
            'task' => 'Nhiệm vụ',
            'target' => 'Chỉ tiêu'
        ];
    }

    // Phương thức để lấy giá trị type dưới dạng văn bản
    public function getTypeTextAttribute()
    {
        $types = self::getTypes();
        return $types[$this->type] ?? 'Không xác định';
    }
    public function getStartDate()
    {
        return $this->start_date ? Carbon::parse($this->start_date)->format('d/m/Y') : '';
    }

    public function getEndDate()
    {
        return $this->end_date ? Carbon::parse($this->end_date)->format('d/m/Y') : '';
    }

    public function getDateFromToTextAttribute()
    {
        return $this->getStartDate() . '-' . $this->getEndDate() ?? '';
    }



    public static function getTypesSomes()
    {
        return [
            'timed' => 'Có thời hạn',
            'regular' => 'Thường xuyên'
        ];
    }

    // Phương thức để lấy giá trị type dưới dạng văn bản
    public function getTypeTextAttributeTime()
    {

        $types = self::getTypesSomes();
        return $types[$this->task_type] ?? '';
    }

    public static function getTypesTarget()
    {
        return [
            'single' => 'Đơn',
            'aggregate' => 'Tổng hợp'
        ];
    }

    // Phương thức để lấy giá trị type dưới dạng văn bản
    public function getTypeTextAttributeTarget()
    {

        $types = self::getTypesTarget();
        return $types[$this->target_type] ?? '';
    }


    
}
