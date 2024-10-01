<?php

namespace App\Http\Controllers;

use App\Models\TaskGroup;
use App\Models\TaskTarget;
use App\Models\TaskResult;
use Illuminate\Validation\Rule; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskGroupController extends Controller
{
    // Hiển thị danh sách các nhóm công việc
    public function index($text=null)
    {
        $taskGroups = TaskGroup::orderBy('name', 'asc')->where('isDelete', 0);
        if($text){
            $taskGroups->where('name', 'like', '%' . $text . '%');
        }
        $taskGroups =  $taskGroups->paginate(10);
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
                'code' => 'required|max:5',
                'name' => 'required'
            ], [
                'code.required' => 'Mã nhóm nhiệm vụ là bắt buộc.',
                'code.unique' => 'Mã nhóm nhiệm vụ đã tồn tại.',
                'code.max' => 'Mã nhóm nhiệm vụ chỉ được phép có tối đa 5 ký tự.',
                'name.required' => 'Tên nhóm nhiệm vụ là bắt buộc.'
            ]);
            $exitItem = TaskGroup::where('isDelete', 0)->where('code', $request->code)->first();
            if($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
            TaskGroup::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'creator_id' => auth()->id(),
            ]);
            DB::commit();
    
            return redirect()->route('task_groups.index')->with('success', 'Thêm mới nhóm nhiệm vụ thành công!');
    
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
            // 'code' => 'required|unique:task_groups,code|max:5'. $taskGroup->id,
            'code' => [
                'required',
                'max:5'
            ],
            'name' => 'required'
        ], [
            'code.required' => 'Mã nhóm nhiệm vụ là bắt buộc.',
            'code.unique' => 'Mã nhóm nhiệm vụ đã tồn tại.',
            'code.max' => 'Mã nhóm nhiệm vụ chỉ được phép có tối đa 5 ký tự.',
            'name.required' => 'Tên nhóm nhiệm vụ là bắt buộc.'
        ]);

        $exitItem = TaskGroup::where('isDelete', 0)->where('code', $request->code)->where('id', '!=',$taskGroup->id)->first();
        if($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
        $taskGroup->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('task_groups.index')->with('success', 'Nhóm nhiệm vụ đã được cập nhật thành công.');
    }

    // Xóa nhóm công việc
    public function destroy(TaskGroup $taskGroup)
    {
        $taskTargets = TaskTarget::where('type_id', $taskGroup->id)->get();

        foreach ($taskTargets as $taskTarget) {
            $taskTarget->isDelete = 1;
            $taskTarget->save();
        }

        $taskReults = TaskResult::where('id_task_criteria', $taskGroup->id)->get();

        foreach ($taskReults as $taskReult) {
            $taskReult->isDelete = 1;
            $taskReult->save();
        }

        $taskGroup->isDelete = 1;
        $taskGroup->save();
        return redirect()->route('task_groups.index')->with('success', 'Nhóm nhiệm vụ đã được xóa thành công.');
    }
}
