<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function checkTaskCode($taskCode)
    {
        // Kiểm tra xem mã công việc có tồn tại trong cơ sở dữ liệu không
        $exists = Task::where('task_code',$taskCode)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function searchTasks(Request $request)
    {
        $query = $request->input('query');
        
        // Nếu query không rỗng, lọc theo query
        if (!empty($query)) {
            $tasks = Task::where('task_code', 'like', "%{$query}%")
                        ->orWhere('task_name', 'like', "%{$query}%")
                        ->get();
        } else {
            // Nếu query rỗng, lấy tất cả các nhiệm vụ
            $tasks = Task::all();
        }
        
        return response()->json([
            'tasks' => $tasks
        ]);
    }
    
    
    public function getAllByAjax()
    {
        // Lấy tất cả các đầu việc từ cơ sở dữ liệu
        $tasks = Task::all();

        // Trả về dữ liệu dưới dạng JSON
        return response()->json($tasks);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
