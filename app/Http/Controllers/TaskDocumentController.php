<?php

// app/Http/Controllers/TaskDocumentController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskDocument;
use App\Models\Document;

class TaskDocumentController extends Controller
{
    // Hiển thị form tạo mới hoặc danh sách các task
    public function index()
    {
        dd("dsadasda");
        // Truy xuất tất cả task documents (hoặc chỉ những cái cần thiết)
        $tasks = TaskDocument::where('isDelete', 0)->get();;
        return view('tasks.index', compact('tasks'));
    }

    // Hiển thị form để tạo mới task
    public function create()
    {
        // Truy xuất tất cả các document để chọn cho task
        $documents = Document::where('isDelete', 0)->get();;
        return view('tasks.create', compact('documents'));
    }

    // Lưu task mới vào database
    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'task_code' => 'required|string|max:255',
            'task_name' => 'required|string|max:255',
            'reporting_cycle' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'required_result' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'creator' => 'required|string|max:255',
            'status' => 'required|in:draft,assign',
        ]);

        // Lưu dữ liệu vào database
        TaskDocument::create($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    // Hiển thị form chỉnh sửa task
    public function edit($id)
    {
        $task = TaskDocument::findOrFail($id);
        $documents = Document::where('isDelete', 0)->get();;
        return view('tasks.edit', compact('task', 'documents'));
    }

    // Cập nhật task trong database
    public function update(Request $request, $id)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'task_code' => 'required|string|max:255',
            'task_name' => 'required|string|max:255',
            'reporting_cycle' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'required_result' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'creator' => 'required|string|max:255',
            'status' => 'required|in:draft,assign',
        ]);

        $task = TaskDocument::findOrFail($id);
        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    // Xóa task
    public function destroy($id)
    {
        $task = TaskDocument::findOrFail($id);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
