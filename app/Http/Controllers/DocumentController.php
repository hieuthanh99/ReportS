<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
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
use App\Models\TaskResult;

class DocumentController extends Controller
{

    public function assignOrganizations(Request $request)
    {
        $documentId = $request->input('documentId');
        $taskCode = $request->input('taskCode');
        $organizations = $request->input('organizations');
        $userId = Auth::id();
        $user = User::find($userId);

        // Lưu các mã tổ chức vào cơ sở dữ liệu
        foreach ($organizations as $organizationCode) {

            $document = Document::where('id', $documentId)->first();
            dd($document);
            $dataTaskDocument = TaskDocument::where('Task_code', $taskCode)
            ->where('document_id', $documentId)
            ->first();

            $dataOrganization = Organization::where('code', $organizations)->first();
            if ($dataOrganization != null && $dataTaskDocument != null) {
                $hasRecord = OrganizationTask::create([
                    'tasks_document_id' => $dataTaskDocument->id,
                    'document_id' => $document->id,
                    'organization_id' => $dataOrganization->id,
                    'creator' => $user->name,
                    'users_id' => $user->id
                ]);
                if ($hasRecord != null) {
                    $document->status = "assign";
                    $document->save();
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Organizations assigned successfully.'
        ]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $documents = Document::with('issuingDepartment')->orderBy('created_at', 'desc')->paginate(10);
        // return view('documents.index', compact('documents'));
        $query = Document::query();

        if ($request->filled('document_name')) {
            $query->where('document_name', 'like', '%' . $request->document_name . '%');
        }
    
        if ($request->filled('organization_id')) {
            $query->where('issuing_department', $request->organization_id);
        }
    
        if ($request->filled('execution_time')) {
            $query->whereDate('release_date', $request->execution_time);
        }
    
        $documents = $query->with('issuingDepartment')->orderBy('created_at', 'desc')->paginate(10);
        $organizations = Organization::all();
        return view('documents.index', compact('documents', 'organizations'));

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
            $tasks = $request->input('tasks');
            // Lưu các đầu việc
            if (is_array($tasks) && !empty($tasks)) {
                foreach ($tasks as $taskData) {
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
                            'end_date' => $endDate,
                            'creator' => $user->name
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
                $criterias = $request->input('criterias');
                // Lưu các đầu việc
                if (is_array($criterias) && !empty($criterias)) {
                    // Lưu các tiêu chí
                    foreach ($criterias as $criteriaData) {
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
                        if ($existingTask && $existingCriteria) {
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
    public function show($id)
    {
        $document = Document::findOrFail($id);
        $taskDocuments = $document->taskDocuments;

        $criterias = [];
        foreach ($taskDocuments as $task) {
            $criterias[$task->task_code] = CriteriasTask::where('DocumentID', $document->id)
                                                        ->where('TaskCode', $task->task_code)
                                                        ->get();
        }
    
        return view('documents.show', compact('document', 'taskDocuments', 'criterias'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $document = Document::findOrFail($id);
        $taskDocuments = $document->taskDocuments;
    
        $weekTask = $taskDocuments->where('reporting_cycle' , 1);
        $monthTask = $taskDocuments->where('reporting_cycle' , 2);
        $quarterTask = $taskDocuments->where('reporting_cycle' , 3);
        $yearTask = $taskDocuments->where('reporting_cycle' , 4);

        $timeParamsWeek = $this->getTimeParameters(1);
        //dd($timeParamsWeek);
        // $currentWeek = $timeParamsWeek['current_week'];
        // $previousWeek = $timeParamsWeek['previous_week'];
        // $twoWeeksAgo = $timeParamsWeek['two_weeks_ago'];

        $timeParamsMonth = $this->getTimeParameters(2);
        // $currentMonth = $timeParamsMonth['current_month'] ?? null;
        // $previousMonth = $timeParamsMonth['previous_month'] ?? null;
        // $twoMonthsAgo = $timeParamsMonth['two_months_ago'] ?? null;

        $timeParamsQuarter = $this->getTimeParameters(3);
        // $currentQuarter = $timeParamsQuarter['current_quarter'] ?? null;
        // $previousQuarter = $timeParamsQuarter['previous_quarter'] ?? null;
        // $twoQuartersAgo = $timeParamsQuarter['two_quarters_ago'] ?? null;

        $timeParamsYear = $this->getTimeParameters(4);
        // $currentYear = $timeParamsYear['current_year'] ?? null;
        // $previousYear = $timeParamsYear['previous_year'] ?? null;
        // $twoYearsAgo = $timeParamsYear['two_years_ago'] ?? null;


        $criterias = [];
        foreach ($taskDocuments as $task) {
            $criterias[$task->task_code] = CriteriasTask::where('DocumentID', $document->id)
                                                        ->where('TaskCode', $task->task_code)
                                                        ->get();
        }
        $organizations = Organization::all();
        return view('documents.edit', compact('document', 'taskDocuments', 'criterias', 'organizations', 'weekTask', 'monthTask', 'quarterTask', 'yearTask'
        , 'timeParamsWeek', 'timeParamsMonth', 'timeParamsQuarter', 'timeParamsYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
    //dd($request);
        try {
            // Validate request data
            $validatedData = $request->validate([
                'document_name' => 'required|string',
                'issuing_department' => 'required|integer',
                'release_date' => 'required|date',
                'document_id.*' => 'required|integer',
                'task_code.*' => 'required|string',
                'task_id.*' => 'required|integer',
                'required_result.*' => 'required|string',
                'task_progress.*' => 'required|string',
                'progress_evaluation.*' => 'required|string',
                'current_result.*' => 'required|string',
                'current_note.*' => 'required|string',
                'task_name.*' => 'required|string',
                'typeCurrent.*' => 'required|string',
                'numberCurrent.*' => 'required|string',
            ]);
    
            // Lấy dữ liệu từ request
            $documentName = $request->input('document_name');
            $issuingDepartment = $request->input('issuing_department');
            $releaseDate = $request->input('release_date');
            $documentIds = $request->input('document_id', []); // Default to empty array
            $taskCodes = $request->input('task_code', []);
            $taskIds = $request->input('task_id', []);
            $requiredResults = $request->input('required_result', []);
            $taskProgress = $request->input('task_progress', []);
            $progressEvaluations = $request->input('progress_evaluation', []);
            $currentResults = $request->input('current_result', []);
            $currentNotes = $request->input('current_note', []);
            $taskNames = $request->input('task_name', []);
            $typeCurrent = $request->input('typeCurrent', []);
            $numberCurrent = $request->input('numberCurrent', []);
    
            // Kiểm tra nếu $documentIds không rỗng và lấy phần tử đầu tiên
            if (!empty($documentIds) && is_array($documentIds)) {
                $document = Document::findOrFail($documentIds[0]);
    
                if ($document) {
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

                    // Cập nhật thông tin tài liệu
                    $document->document_name = $documentName;
                    $document->issuing_department = $issuingDepartment;
                    $document->release_date = $releaseDate;
                    $document->save();
                    
                    if (!empty($taskIds) && is_array($taskIds)) {
                         // Cập nhật các tác vụ
                        foreach ($taskIds as $index => $taskId) {
                            $task = TaskDocument::find($taskId);
        
                            if ($task) {
                              
                                $task->task_name = $taskNames[$index] ?? '';
                                $task->required_result = $requiredResults[$index] ?? '';
                                $task->progress = $taskProgress[$index] ?? '';
                                $task->progress_evaluation = $progressEvaluations[$index] ?? '';
                                $task->save();
                                
                                $record = TaskResult::where('tasks_document_id' , $task->id)->where("document_id", $document->id)->first();
                                
                                if($record){
                                    $record->result =  $currentResults[$index] ?? '';
                                    $record->description =  $currentNotes[$index] ?? '';
                                    $record->number_type =  $numberCurrent[$index] ?? '';
                                    $record->type =  $typeCurrent[$index] ?? '';

                                }else{
                                    $hasRecord = TaskResult::create([
                                        'tasks_document_id' => $task->id,
                                        'document_id' => $document->id,
                                        'result' => $currentResults[$index] ?? '',
                                        'description' => $currentNotes[$index] ?? '',
                                        'number_type' => $numberCurrent[$index] ?? '',
                                        'type' => $typeCurrent[$index] ?? ''
                                    ]);
                                }
                            }
                        }
                    }
                }
    
                DB::commit();
                return redirect()->route('documents.index')->with('success', 'Cập nhật thành công!');
            } else {
                return redirect()->back()->with('error', 'No document IDs found.');
            }
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi xảy ra
            DB::rollBack();
    
            // Ghi lỗi vào log
            \Log::error('Error creating document: ' . $e->getMessage());
    
            return redirect()->back()->with('error', 'Đã xảy ra lỗi, vui lòng thử lại.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Xóa các bản ghi trong bảng CriteriasTask theo điều kiện document_id
        CriteriasTask::where('DocumentID', $documentId)->delete();

        // Xóa các bản ghi trong bảng OrganizationTask theo điều kiện document_id
        OrganizationTask::where('document_id', $documentId)->delete();

        // Xóa các bản ghi trong bảng TaskDocument theo điều kiện document_id
        TaskDocument::where('document_id', $documentId)->delete();

        // Tìm và xóa tài liệu trong bảng Document
        $document = Document::findOrFail($documentId);
        $document->delete();
    
        // Chuyển hướng về trang danh sách tài liệu
        return redirect()->route('documents.index')->with('success', 'Xóa thành công văn bản.');
    }

    public function getTimeParameters($type)
    {
        $today = Carbon::now();
        $result = [];

        switch ($type) {
            case 1: // Tuần
                $result['current'] = $today->weekOfYear;
                $result['previous'] = $today->copy()->subWeek()->weekOfYear;
                $result['two_previous'] = $today->copy()->subWeeks(2)->weekOfYear;
                break;

            case 2: // Tháng
                $result['current'] = $today->month;
                $result['previous'] = $today->copy()->subMonth()->month;
                $result['two_previous'] = $today->copy()->subMonths(2)->month;
                break;

            case 3: // Quý
                $result['current'] = $today->quarter;
                $result['previous'] = $today->copy()->subQuarter()->quarter;
                $result['two_previous'] = $today->copy()->subQuarters(2)->quarter;
                break;

            case 4: // Năm
                $result['current'] = $today->year;
                $result['previous'] = $today->copy()->subYear()->year;
                $result['two_previous'] = $today->copy()->subYears(2)->year;
                break;

            default:
                $result['error'] = 'Invalid type';
                break;
        }

        return $result;
    }

}
