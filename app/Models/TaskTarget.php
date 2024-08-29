<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\TimeHelper;
use App\Enums\TaskStatus;



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
        'isDelete'
    ];
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
       return File::where('document_id', $this->id)->where('type', 1)->first();
    }
    public function taskResultsById($numberType)
    {

        $currentYear = Carbon::now()->year;
        // dd($id);
        \Log::error('Target Model: ' . $this->id);
        \Log::error('Target Model: ' . $this->type);
        \Log::error('Target Model: ' . $numberType);
        \Log::error('Target Model: ' . $this->cycle_type);
        return TaskResult::where('id_task_criteria', $this->id)->where('type_save', self::getType())->where('number_type', $numberType)->where('type', $this->cycle_type)->first();
    }

//TaskApprovalHistory

    public function getTaskApprovalHistory()
    {
        $timeParams = TimeHelper::getTimeParameters($this->cycle_type);
        $taskResult = TaskResult::where('id_task_criteria', $this->id)->where('type', $this->cycle_type)->where('number_type', $timeParams['current'])->first();
        if($taskResult){

            $data = TaskApprovalHistory::where('task_target_id', $this->id)->where('task_result_id', $taskResult->id)->orderBy('created_at', 'desc')->first();
            // dd($data);
        }
        return $data ?? null;
    }

    

    public function hasOrganizationAppro()
    {
        $user = Auth::user();
        $organizationId = $user->organization_id;
        if($organizationId == null) return false;

        $organizationTaskId = $this->organization_id;

        if ($organizationTaskId === $organizationId) {
        
            if($user->role == 'sub_admin') return true;
        }
        //$organizationChild = Organization::where('id', $organizationTaskId)->first();

        // $organizationParent = Organization::where('id', $organizationId)->first();
        // $currentOrganization = $organizationChild->parent;

        // while ($currentOrganization) {
        //     if ($currentOrganization->id === $organizationParent->id) {
        //         return true;
        //     }
        //     $currentOrganization = $currentOrganization->parent;
        // }

        return false;
    }

    public function getFilePathByType($numberType)
    {
        // dd($id);
        $currentYear = Carbon::now()->year;
        $taskResult =  TaskResult::where('type_save', self::getType())->where('number_type', $numberType)->where('type', $this->cycle_type)->whereYear('created_at', $currentYear)->first();
        if($taskResult){
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

    public function document()
    {
        return $this->belongsTo(Document::class);
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

    public static function getCycleTypes()
    {
        return [
            '1' => 'Tuần',
            '2' => 'Tháng',
            '3' => 'Quý',
            '4' => 'Năm'
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
        return $this->start_date ? Carbon::parse($this->start_date)->format('d-m-Y') : '';
    }

    public function getEndDate()
    {
        return $this->end_date ? Carbon::parse($this->end_date)->format('d-m-Y') : '';
    }

    public function getDateFromToTextAttribute()
    {
        return $this->getStartDate() . ' - ' . $this->getEndDate() ?? '';
    }
}
