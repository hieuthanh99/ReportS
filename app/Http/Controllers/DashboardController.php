<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskTarget;
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
        $tasks = TaskTarget::where('isDelete', 0);
        $today = Carbon::now();
    
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
        $tableTask = $tasks->where('type', 'task')->paginate(10);
        $tableTarget = $tasks->where('type', 'task')->paginate(10);
        // Lặp qua các task/target và phân loại theo role và type
        foreach ($tasks->get() as $task) {
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
            'tableTarget' => $tableTarget
        ]);
    }
    
    
}
