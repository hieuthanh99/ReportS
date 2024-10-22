<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskTarget;
use App\Models\TaskResult;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Enums\TaskStatus;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index($text = null)
    {
        $user = User::find(Auth::id());
        $tasks = TaskTarget::where('isDelete', 0)->get();
        $today = Carbon::now();

       
        if($user->role === 'supper_admin' || $user->role === 'admin'){
            $tableTask = TaskTarget::where('isDelete', 0)
            ->where('type', 'task')
            ->whereHas('taskResultsRelation', function($query) {
                $query->whereIn('status', ['admin_approves', 'sub_admin_complete']);
            })
            ->orWhere(function ($query) {
                $query->where('status','processing')
                      ->whereDoesntHave('taskResultsRelation', function($query) {
                          $query->where('status', '!=', 'complete');
                      });
            })
            ->paginate(10, ['*'], 'task_pagging');
            $tableTaskCount = TaskTarget::where('isDelete', 0)
            ->where('type', 'task')
            ->whereHas('taskResultsRelation', function($query) {
                $query->whereIn('status', ['admin_approves', 'sub_admin_complete']);
            })
            ->orWhere(function ($query) {
                $query->where('status','processing')
                      ->whereDoesntHave('taskResultsRelation', function($query) {
                          $query->where('status', '!=', 'complete');
                      });
            })->get();
            // $tableTarget = TaskTarget::where('isDelete', 0)->where('type', 'target')->whereHas('taskResultsRelation', function($query) {
            //     $query->whereIn('status', ['admin_approves', 'sub_admin_complete']);
            // })->where('status', 'complete')->paginate(10);

            $tableTarget = TaskTarget::where('isDelete', 0)
            ->where('type', 'target')
            ->whereHas('taskResultsRelation', function($query) {
                $query->whereIn('status', ['admin_approves', 'sub_admin_complete']);
            })
            ->orWhere(function ($query) {
                $query->where('status','processing')
                      ->whereDoesntHave('taskResultsRelation', function($query) {
                          $query->where('status', '!=', 'complete');
                      });
            })
            ->paginate(10, ['*'], 'target_pagging');
            $tableTargetCount = TaskTarget::where('isDelete', 0)
            ->where('type', 'target')
            ->whereHas('taskResultsRelation', function($query) {
                $query->whereIn('status', ['admin_approves', 'sub_admin_complete']);
            })
            ->orWhere(function ($query) {
                $query->where('status','processing')
                      ->whereDoesntHave('taskResultsRelation', function($query) {
                          $query->where('status', '!=', 'complete');
                      });
            })->get();
        }
        elseif($user->role === 'sub_admin'){
            $tasks = TaskTarget::where('isDelete', 0)->whereHas('taskResultsRelation', function($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })->get();
            $tableTask = TaskTarget::where('isDelete', 0)->where('type', 'task')->whereHas('taskResultsRelation', function($query) use ($user) {
                $query->whereIn('status', ['staff_complete'])->where('organization_id', $user->organization_id);
            })->with(['taskResultsRelation' => function($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            }])->paginate(10, ['*'], 'task_pagging');
            $tableTaskCount = TaskTarget::where('isDelete', 0)->where('type', 'task')->whereHas('taskResultsRelation', function($query) use ($user) {
                $query->whereIn('status', ['staff_complete'])->where('organization_id', $user->organization_id);
            })->get();
            $tableTarget = TaskTarget::where('isDelete', 0)->where('type', 'target')->whereHas('taskResultsRelation', function($query) use ($user) {
                $query->whereIn('status', ['staff_complete'])->where('organization_id', $user->organization_id);
            })->with(['taskResultsRelation' => function($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            }])->paginate(10, ['*'], 'target_pagging');
            $tableTargetCount = TaskTarget::where('isDelete', 0)->where('type', 'target')->whereHas('taskResultsRelation', function($query) use ($user) {
                $query->whereIn('status', ['staff_complete'])->where('organization_id', $user->organization_id);
            })->get();
        }
        elseif($user->role === 'staff'){
            $tasks = TaskTarget::where('isDelete', 0)->whereHas('taskResultsRelation', function($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })->get();
            $tableTask = TaskTarget::where('isDelete', 0)->where('type', 'task')->whereHas('taskResultsRelation', function($query) use ($user) {
                $query->whereIn('status', ['assign', 'reject'])->where('organization_id', $user->organization_id);
            })->with(['taskResultsRelation' => function($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            }])->paginate(10, ['*'], 'task_pagging');
            $tableTaskCount = TaskTarget::where('isDelete', 0)->where('type', 'task')->whereHas('taskResultsRelation', function($query) use ($user) {
                $query->whereIn('status', ['assign', 'reject'])->where('organization_id', $user->organization_id);
            })->get();
            $tableTarget = TaskTarget::where('isDelete', 0)->where('type', 'target')->whereHas('taskResultsRelation', function($query) use ($user) {
                $query->whereIn('status', ['assign', 'reject'])->where('organization_id', $user->organization_id);
            })->with(['taskResultsRelation' => function($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            }])->paginate(10, ['*'], 'target_pagging');
            $tableTargetCount = TaskTarget::where('isDelete', 0)->where('type', 'target')->whereHas('taskResultsRelation', function($query) use ($user) {
                $query->whereIn('status', ['assign', 'reject'])->where('organization_id', $user->organization_id);
            })->get();
        }
        // Khởi tạo mảng để chứa số liệu cho từng role và type
        $stats = [
            'task' => [
                'overdue' => 0,
                'upcoming' => 0,
                'inProgress' => 0,
                'completedOnTime' => 0,
                'completedLate' => 0
            ],
            'target' => [
                'overdue' => 0,
                'upcoming' => 0,
                'inProgress' => 0,
                'completedOnTime' => 0,
                'completedLate' => 0
            ]
        ];

        foreach ($tasks as $task) {
            $startDate = Carbon::parse($task->start_date);
            $endDate = Carbon::parse($task->end_date);
    
            // Xác định loại (task hoặc target)
            $type = $task->type;
    
            // Sử dụng logic chung cho cả task và target
            if ($task->status === 'complete') {
                if ($endDate->gt($today)) {
                    $stats[$type]['completedOnTime']++;
                } else {
                    $stats[$type]['completedLate']++;
                }
            } elseif ($task->status === 'processing') {
                if ($today->between($startDate, $endDate)) {
                    $stats[$type]['inProgress']++;
                } elseif ($today->gt($endDate)) {
                    $stats[$type]['overdue']++;
                }
            } elseif ($task->status === 'new' && $endDate->diffInDays($today) <= 30) {
                $stats[$type]['upcoming']++;
            }
        }
        return view('dashboard', [
            'taskStatus' => $stats['task'],
            'targetStatus' => $stats['target'],
            'tableTask' => $tableTask,
            'tableTarget' => $tableTarget,
            'tableTaskCount' => $tableTaskCount,
            'tableTargetCount' => $tableTargetCount,

        ]);
    }
    
    
}
