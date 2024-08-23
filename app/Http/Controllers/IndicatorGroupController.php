<?php

namespace App\Http\Controllers;

use App\Models\IndicatorGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndicatorGroupController extends Controller
{
    // Hiển thị danh sách các nhóm công việc
    public function index()
    {
        $taskGroups = IndicatorGroup::orderBy('created_at', 'desc')->paginate(10);
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
                'code' => 'required|unique:indicator_groups,code|max:5',
                'name' => 'required'
            ], [
                'code.required' => 'Mã loại chỉ tiêu là bắt buộc.',
                'code.unique' => 'Mã loại chỉ tiêu đã tồn tại.',
                'code.max' => 'Mã loại nhiệm vụ chỉ được phép có tối đa 5 ký tự.',
                'name.required' => 'Tên loại chỉ tiêu là bắt buộc.'
            ]);

            IndicatorGroup::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'creator_id' => auth()->id(),
            ]);
            DB::commit();
    
            return redirect()->route('indicator_groups.index')->with('success', 'Thêm mới loại chỉ tiêu thành công!');
    
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
            'code' => 'required|unique:indicator_groups,code|max:5' . $indicatorGroup->id,
            'name' => 'required'
        ], [
            'code.required' => 'Mã loại chỉ tiêu là bắt buộc.',
            'code.unique' => 'Mã loại chỉ tiêu đã tồn tại.',
            'code.max' => 'Mã loại nhiệm vụ chỉ được phép có tối đa 5 ký tự.',
            'name.required' => 'Tên loại chỉ tiêu là bắt buộc.'
        ]);


        $indicatorGroup->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('indicator_groups.index')->with('success', 'Nhóm công việc đã được cập nhật thành công.');
    }

    // Xóa nhóm công việc
    public function destroy(IndicatorGroup $indicatorGroup)
    {
        $indicatorGroup->delete();
        return redirect()->route('indicator_groups.index')->with('success', 'Nhóm công việc đã được xóa thành công.');
    }
    }
