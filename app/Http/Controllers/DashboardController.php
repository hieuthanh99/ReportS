<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskTarget;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Enums\TaskStatus;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());
        $tasksTable = TaskTarget::where('type', 'task')->where('isDelete', 0)->paginate(10);
        $targetsTable = TaskTarget::where('type', 'target')->where('isDelete', 0)->paginate(10);

        $taskList = DB::table('task_target')
            ->selectRaw('
                MONTH(created_at) as month, 
                status_code, 
                COUNT(*) as total
            ')->where('type', 'task')
            ->where('isDelete', 0)
            ->groupBy('month', 'status_code')
            ->orderBy('month')->get();


        $tagertList = DB::table('task_target')
            ->selectRaw('
                MONTH(created_at) as month, 
                status_code, 
                COUNT(*) as total
            ')->where('type', 'target')
            ->where('isDelete', 0)
            ->groupBy('month', 'status_code')
            ->orderBy('month')->get();

        //dd($taskList);
        // Chuẩn bị dữ liệu cho biểu đồ
        $chartDataTask = $this->getJson($taskList);
        $chartDataTarget =  $this->getJson($tagertList);

        $dataStaff = DB::table('task_target')
        ->selectRaw('
            MONTH(created_at) as month, 
            status_code, 
            COUNT(*) as total
        ')
        ->where('isDelete', 0)
        ->where('organization_id', $user->organization_id)
        ->groupBy('month', 'status_code')
        ->orderBy('month')->get();

        $chartDataStaff =  $this->getJson($dataStaff);

        $chartDataJson = json_encode($chartDataTask);
        $chartDataTargetJson = json_encode($chartDataTarget);
        $chartDataStaffJson = json_encode($chartDataStaff);
        $staffTable = TaskTarget::where('organization_id', $user->organization_id)->where('isDelete', 0)->paginate(10);
       // dd($staffTable);
        return view('dashboard', compact('chartDataJson', 'chartDataTargetJson', 'tasksTable', 'targetsTable', 'chartDataStaffJson', 'staffTable'));
    }


    function getJson($data){
        $chartData = [
            'months' => [],
            'not_completed' => [],
            'in_progress_in_time' => [],
            'in_progress_overdue' => [],
            'completed_in_time' => [],
            'completed_overdue' => []
        ];

        foreach ($data as $task) {
            $month = 'Tháng ' . $task->month;
            if (!in_array($month, $chartData['months'])) {
                $chartData['months'][] = $month;
            }
            
            switch ($task->status_code) {
                case TaskStatus::NOT_COMPLETED->value:
                    $chartData['not_completed'][] = $task->total;
                    break;
                case TaskStatus::IN_PROGRESS_IN_TIME->value:
                    $chartData['in_progress_in_time'][] = $task->total;
                    break;
                case TaskStatus::IN_PROGRESS_OVERDUE->value:
                    $chartData['in_progress_overdue'][] = $task->total;
                    break;
                case TaskStatus::COMPLETED_IN_TIME->value:
                    $chartData['completed_in_time'][] = $task->total;
                    break;
                case TaskStatus::COMPLETED_OVERDUE->value:
                    $chartData['completed_overdue'][] = $task->total;
                    break;
            }
        }
        return $chartData;
    }
}
