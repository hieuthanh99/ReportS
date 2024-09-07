<?php

namespace App\Http\Controllers;

use App\Models\IndicatorGroup;
use App\Models\TaskTarget;
use App\Models\TaskResult;
use Illuminate\Validation\Rule; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndicatorGroupController extends Controller
{
    // Hiển thị danh sách các nhóm công việc
    public function index($text=null)
    {
        $taskGroups = IndicatorGroup::where('isDelete', 0)->orderBy('created_at', 'desc');
        if($text){
            $taskGroups->where('name', 'like', '%' . $text . '%');
        }
        $taskGroups =  $taskGroups->paginate(10);
        return view('indicator_groups.index', compact('taskGroups'));
    }

    // Hiển thị form tạo nhóm công việc mới
    public function create()
    {
        return view('indicator_groups.create');
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
                'code.required' => 'Mã loại chỉ tiêu là bắt buộc.',
                'code.unique' => 'Mã loại chỉ tiêu đã tồn tại.',
                'code.max' => 'Mã loại chỉ tiêu chỉ được phép có tối đa 5 ký tự.',
                'name.required' => 'Tên loại chỉ tiêu là bắt buộc.'
            ]);
            $exitItem = IndicatorGroup::where('isDelete', 0)->where('code', $request->code)->first();
            if($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
            IndicatorGroup::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'creator_id' => auth()->id(),
            ]);
            DB::commit();
    
            return redirect()->route('indicator_groups.index')->with('success', 'Thêm mới nhóm chỉ tiêu thành công!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating document: ' . $e->getMessage());
    
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    // Hiển thị chi tiết một nhóm công việc
    public function show(IndicatorGroup $taskGroup)
    {
        return view('indicator_groups.show', compact('taskGroup'));
    }

    // Hiển thị form chỉnh sửa một nhóm công việc
    public function edit(IndicatorGroup $indicatorGroup)
    {
        return view('indicator_groups.edit', compact('indicatorGroup'));
    }

    // Cập nhật nhóm công việc trong cơ sở dữ liệu
    public function update(Request $request, IndicatorGroup $indicatorGroup)
    {
        $request->validate([
            // 'code' => 'required|unique:indicator_groups,code|max:5' . $indicatorGroup->id,
            'code' => [
                'required',
                'max:5'
            ],
            'name' => 'required'
        ], [
            'code.required' => 'Mã nhóm chỉ tiêu là bắt buộc.',
            'code.unique' => 'Mã nhóm chỉ tiêu đã tồn tại.',
            'code.max' => 'Mã nhóm chỉ tiêu chỉ được phép có tối đa 5 ký tự.',
            'name.required' => 'Tên nhóm chỉ tiêu là bắt buộc.'
        ]);

        $exitItem = IndicatorGroup::where('isDelete', 0)->where('code', $request->code)->where('id','!=', $indicatorGroup->id)->first();
        if($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
        $indicatorGroup->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('indicator_groups.index')->with('success', 'Nhóm chỉ tiêu đã được cập nhật thành công.');
    }

    // Xóa nhóm công việc
    public function destroy(IndicatorGroup $indicatorGroup)
    {
        $taskTargets = TaskTarget::where('type_id', $indicatorGroup->id)->get();

        foreach ($taskTargets as $taskTarget) {
            $taskTarget->isDelete = 1;
            $taskTarget->save();
        }

        $taskReults = TaskResult::where('id_task_criteria', $indicatorGroup->id)->get();

        foreach ($taskReults as $taskReult) {
            $taskReult->isDelete = 1;
            $taskReult->save();
        }

        $indicatorGroup->isDelete = 1;
        $indicatorGroup->save();
        return redirect()->route('indicator_groups.index')->with('success', 'Nhóm chỉ tiêu đã được xóa thành công.');
    }
    }
