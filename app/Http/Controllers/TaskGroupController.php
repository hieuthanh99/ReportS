<?php

namespace App\Http\Controllers;

use App\Models\TaskGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskGroupController extends Controller
{
    // Hiển thị danh sách các nhóm công việc
    public function index()
    {
        $taskGroups = TaskGroup::orderBy('created_at', 'desc')->paginate(10);
        return view('task_groups.index', compact('taskGroups'));
    }

    // Hiển thị form tạo nhóm công việc mới
    public function create()
    {
        return view('task_groups.create');
    }

    // Lưu nhóm công việc mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        DB::beginTransaction();
      
        try {
                 // Validate input
            $request->validate([
                'code' => 'required|unique:task_groups,code|max:5',
                'name' => 'required'
            ], [
                'code.required' => 'Mã loại nhiệm vụ là bắt buộc.',
                'code.unique' => 'Mã loại nhiệm vụ đã tồn tại.',
                'code.max' => 'Mã loại nhiệm vụ chỉ được phép có tối đa 5 ký tự.',
                'name.required' => 'Tên loại nhiệm vụ là bắt buộc.'
            ]);

            TaskGroup::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'creator_id' => auth()->id(),
            ]);
            DB::commit();
    
            return redirect()->route('task_groups.index')->with('success', 'Thêm mới loại nhiệm vụ thành công!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating document: ' . $e->getMessage());
    
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    // Hiển thị chi tiết một nhóm công việc
    public function show(TaskGroup $taskGroup)
    {
        return view('task_groups.show', compact('taskGroup'));
    }

    // Hiển thị form chỉnh sửa một nhóm công việc
    public function edit(TaskGroup $taskGroup)
    {
        return view('task_groups.edit', compact('taskGroup'));
    }

    // Cập nhật nhóm công việc trong cơ sở dữ liệu
    public function update(Request $request, TaskGroup $taskGroup)
    {
        $request->validate([
            'code' => 'required|unique:task_groups,code|max:5'. $taskGroup->id,
            'name' => 'required'
        ], [
            'code.required' => 'Mã loại nhiệm vụ là bắt buộc.',
            'code.unique' => 'Mã loại nhiệm vụ đã tồn tại.',
            'code.max' => 'Mã loại nhiệm vụ chỉ được phép có tối đa 5 ký tự.',
            'name.required' => 'Tên loại nhiệm vụ là bắt buộc.'
        ]);


        $taskGroup->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('task_groups.index')->with('success', 'Nhóm công việc đã được cập nhật thành công.');
    }

    // Xóa nhóm công việc
    public function destroy(TaskGroup $taskGroup)
    {
        $taskGroup->delete();
        return redirect()->route('task_groups.index')->with('success', 'Nhóm công việc đã được xóa thành công.');
    }
}
