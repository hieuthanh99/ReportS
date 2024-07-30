<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\File;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskDocument;
use App\Models\Criteria;
use App\Models\Organization;
use App\Models\CriteriasTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\OrganizationTask;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::with('issuingDepartment')->orderBy('created_at', 'desc')->get();
        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizations = Organization::all();
        // Truyền dữ liệu tổ chức vào view
        return view('documents.create', compact('organizations'));
    }
    public function checkDocumentCode($documentCode)
    {
        // Kiểm tra xem mã công việc có tồn tại trong cơ sở dữ liệu không
        $exists = Document::where('document_code',$documentCode)->exists();
        return response()->json(['exists' => $exists]);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Bắt đầu transaction
        DB::beginTransaction();
    
        try {
            $userId = Auth::id();
            $user = User::find($userId);
    
            // Xác thực dữ liệu (nếu cần)
            $request->validate([
                'document_code' => 'required|string|max:255',
                'document_name' => 'required|string|max:255',
                'issuing_department' => 'required|string|max:255',
                'release_date' => 'nullable|date',
                'files.*' => 'nullable|file|max:2048',
                'tasks.*' => 'required|string',
                'criterias.*' => 'required|string',
            ]);
    
            // Lưu tài liệu
            $document = Document::create([
                'document_code' => $request->input('document_code'),
                'document_name' => $request->input('document_name'),
                'issuing_department' => $request->input('issuing_department'),
                'release_date' => $request->input('release_date'),
                'creator' => $user->name
            ]);
    
            // Xử lý file upload
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('documents', $fileName, 'public');
                    
                    // Lưu thông tin file vào cơ sở dữ liệu
                    File::create([
                        'document_id' => $document->id,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                    ]);
                }
            }
    
            // Lưu các đầu việc
            foreach ($request->input('tasks') as $taskData) {
                // Phân tách dữ liệu đầu việc
                list($taskCode, $taskName, $reportingCycle, $category, $requiredResult, $startDate, $endDate) = explode('|', $taskData);
                $existingTask = Task::where('task_code', $taskCode)->first();
                if (!$existingTask) {
                    Task::create([
                        'task_code' => $taskCode,
                        'task_name' => $taskName,
                        'reporting_cycle' => $reportingCycle,
                        'category' => $category,
                        'required_result' => $requiredResult,
                        'start_date' => $startDate,
                        'end_date' => $endDate
                    ]);
                }
                TaskDocument::create([
                    'document_id' => $document->id,
                    'task_code' => $taskCode,
                    'task_name' => $taskName,
                    'reporting_cycle' => $reportingCycle,
                    'category' => $category,
                    'required_result' => $requiredResult,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'draft',
                    'creator' => $user->name
                ]);
            }
    
            // Lưu các tiêu chí
            foreach ($request->input('criterias') as $criteriaData) {
                // Phân tách dữ liệu tiêu chí
                list($taskCode, $criteriaCode, $criteriaName, $result) = explode('|', $criteriaData);
                $existingCriteria = Criteria::where('code', $criteriaCode)->first();
                $existingTask = Task::where('task_code', $taskCode)->first();
                if (!$existingCriteria) {
                    Criteria::create([
                        'code' => $criteriaCode,
                        'name' => $criteriaName,
                    ]);
                }
                if ($existingTask) {
                    CriteriasTask::create([
                        'TaskID' => $existingTask->id,
                        'CriteriaID' => $existingCriteria->id,
                        'CriteriaCode' => $criteriaCode,
                        'CriteriaName' => $criteriaName,
                        'DocumentID' => $document->id,
                        'TaskCode' => $taskCode,
                        'RequestResult' => $result,
                    ]);
                }
            }
            if ($request->has('organizations')) {
                foreach ($request->input('organizations') as $data) {
                    // Phân tách dữ liệu tiêu chí
                    list($taskCode, $organizationCode, $organizationName, $organizationEmail, $organizationPhone) = explode('|', $data);

                    $dataTaskDocument = TaskDocument::where('Task_code', $taskCode)
                    ->where('document_id', $document->id)
                    ->first();

                    $dataOrganization = Organization::where('code', $organizationCode)
                    ->where('name', $organizationName)
                    ->first();
                    if ($dataOrganization != null && $dataTaskDocument != null) {
                        $hasRecord = OrganizationTask::create([
                            'tasks_document_id' => $dataTaskDocument->id,
                            'document_id' => $document->id,
                            'organization_id' => $dataOrganization->id,
                            'creator' => $user->name,
                            'users_id' => $user->id
                        ]);
                        if ($hasRecord != null) {
                            $dataTaskDocument->status = "assign";
                            $dataTaskDocument->save();
                        }
      
                    }
                }
            }
            
            // Commit transaction nếu tất cả các bước trên thành công
            DB::commit();
    
            return redirect()->route('documents.index')->with('success', 'Danh mục tạo thành công!');
            
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi xảy ra
            DB::rollBack();
            
            // Ghi lỗi vào log (tùy chọn)
            \Log::error('Error creating document: ' . $e->getMessage());
    
            return redirect()->back()->with('error', 'Đã xảy ra lỗi, vui lòng thử lại.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $document = Document::find($id);
        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('documents.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'document_code' => 'required|string|max:255',
            'document_name' => 'required|string|max:255',
            'issuing_department' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'release_date' => 'nullable|date', 
        ]);
        $document->update($request->all());
        return redirect()->route('documents.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $document->delete();
        return redirect()->route('documents.index');
    }
}
