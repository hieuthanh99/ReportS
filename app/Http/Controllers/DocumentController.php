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
use App\Models\HistoryChangeDocument;

class DocumentController extends Controller
{

    public function assignOrganizations(Request $request)
    {
        DB::beginTransaction();
    
        try {
            $organizations = $request->input('organizations');
            $userId = Auth::id();
            $processedOrganizations = []; // Tạo tập hợp để lưu mã organization đã xử lý
    
            if (!empty($organizations)) {
                $first = true; // Đánh dấu tổ chức đầu tiên
                foreach ($organizations as $data) {
                    $organizationCode = $data['code'];
                    $organizationName = $data['name'];
                    $organizationEmail = $data['email'];
                    $organizationPhone = $data['phone'];
                    $taskCodeOrganization = $data['task_code'];
                    $taskId = $data['task_id'];
    
                    // Kiểm tra nếu mã organization đã tồn tại trong tập hợp, bỏ qua vòng lặp
                    if (in_array($organizationCode, $processedOrganizations)) {
                        continue;
                    }
    
                    \Log::error('organizations giao việc: ' . $organizationName);
                    \Log::error('biến check giao việc: ' . $first);
    
                    $organization = Organization::where('code', $organizationCode)->where('name', $organizationName)->first();
                    if ($organization) {
                        // Kiểm tra nếu đây là tổ chức đầu tiên, gán vào task gốc
                        $taskDocument = TaskDocument::find($taskId);
    
                        if ($first) {
                            $taskDocument->organization_id = $organization->id;
                            $taskDocument->status = "assign";
                            $taskDocument->progress = "Đang thực hiện";
                            $taskDocument->save();
                            $first = false;
                            \Log::error('biến check giao việc: ' . $taskDocument->name);
                        } else {
                            // Tạo TaskDocument mới cho tổ chức khác
                            $taskDocumentClone = TaskDocument::create([
                                'document_id' => $taskDocument->document_id,
                                'task_code' => $taskDocument->task_code,
                                'task_name' => $taskDocument->task_name,
                                'reporting_cycle' => $taskDocument->reporting_cycle,
                                'category' => $taskDocument->category,
                                'required_result' => $taskDocument->required_result,
                                'start_date' => $taskDocument->start_date,
                                'end_date' => $taskDocument->end_date,
                                'status' => 'assign',
                                'creator' => $userId,
                                'organization_id' => $organization->id,
                                'progress' => "Đang thực hiện"
                            ]);
    
                            // Lấy các tiêu chí liên quan đến task gốc
                            $criterias = CriteriasTask::where('TaskID', $taskDocument->id)->get();
                            // Lưu các tiêu chí liên quan đến tổ chức
                            foreach ($criterias as $criteria) {
                                CriteriasTask::create([
                                    'TaskID' => $taskDocumentClone->id,
                                    'CriteriaID' => $criteria->CriteriaID,
                                    'CriteriaCode' => $criteria->CriteriaCode,
                                    'CriteriaName' => $criteria->CriteriaName,
                                    'DocumentID' => $criteria->DocumentID,
                                    'TaskCode' => $criteria->TaskCode,
                                    'RequestResult' => $criteria->RequestResult,
                                    'progress' => "Đang thực hiện"
                                ]);
                            }
                        }
    
                        // Thêm mã organization vào tập hợp đã xử lý
                        $processedOrganizations[] = $organizationCode;
                    }
                }
            }
    
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Organizations assigned successfully.'
            ]);
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi xảy ra
            DB::rollBack();
    
            // Ghi lỗi vào log (tùy chọn)
            \Log::error('Error creating document: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Đã xảy ra lỗi, vui lòng thử lại.'
            ]);
        }
    }
    

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $user = User::find($userId);
        // $documents = Document::with('issuingDepartment')->orderBy('created_at', 'desc')->paginate(10);
        // return view('documents.index', compact('documents'));
        $query = Document::query();
        $taskDocumentsQuery = TaskDocument::query();
        if ($request->filled('document_name')) {
            $query->where('document_name', 'like', '%' . $request->document_name . '%');
        }
    
        if ($request->filled('organization_id')) {
            $query->where('issuing_department', $request->organization_id);
        }
    
        if ($request->filled('execution_time')) {
            $query->whereDate('release_date', $request->execution_time);
        }
    
        // $documents = $query->with('issuingDepartment')->orderBy('created_at', 'desc')->paginate(10);
        $documents = $query->where('creator', $userId)->with('issuingDepartment')->orderBy('created_at', 'desc')->paginate(10);

        if ($documents->isEmpty()) {
            $documents = Document::whereHas('taskDocuments', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })->with('issuingDepartment')->orderBy('created_at', 'desc')->paginate(10);

            $taskDocuments = TaskDocument::whereIn('document_id', $documents->pluck('id'))
            ->where('organization_id', $user->organization->id)
            ->get();
        }
        else {
            // Nếu người dùng là người tạo Document, lấy tất cả TaskDocuments liên quan đến Document
            $taskDocuments = TaskDocument::whereIn('document_id', $documents->pluck('id'))->get();
        }
        //dd($taskDocuments);
        $organizations = Organization::all();
        return view('documents.index', compact('documents', 'organizations', 'taskDocuments'));

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

    public function getHistory($id, $type, $cycle, $typeCycle)
    {

        $id = (int) $id;
        $type = (int) $type;
        $typeCycle = (int) $typeCycle;
        $cycle = (int) $cycle;
        
        // dd($id, $type, $typeCycle, $cycle);
        
        $lstHistory = HistoryChangeDocument::where('mapping_id', $id)
        ->where('type_save', $type)
        ->where('type_cycle', $typeCycle)
        ->where('number_cycle', $cycle)->get();
    
        //dd($lstHistory->toSql(), $lstHistory->getBindings());
    
        return response()->json(['histories' => $lstHistory]);
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
    
            // Xác thực dữ liệu
            $request->validate([
                'document_code' => 'required|string|max:255',
                'document_name' => 'required|string|max:255',
                'issuing_department' => 'required|string|max:255',
                'release_date' => 'nullable|date',
                'files.*' => 'nullable|file|max:2048',
                'tasks.*' => 'required|string',
                'criterias.*' => 'required|string',
                'organizations.*' => 'nullable|string',
            ]);
    
            // Lưu tài liệu
            $document = Document::create([
                'document_code' => $request->input('document_code'),
                'document_name' => $request->input('document_name'),
                'issuing_department' => $request->input('issuing_department'),
                'release_date' => $request->input('release_date'),
                'creator' => $userId
            ]);
    
            // Xử lý file upload
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('documents', $fileName, 'public');
                    File::create([
                        'document_id' => $document->id,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'type' => '3'
                    ]);
                }
            }
    
            // Lưu và gán task
            $tasks = $request->input('tasks');
            $organizations = $request->input('organizations', []);
            $createdTaskDocuments = array();
           // dd($request);
            foreach ($tasks as $taskData) {
                list($taskCode, $taskName, $reportingCycle, $category, $requiredResult, $startDate, $endDate) = explode('|', $taskData);
                $existingTask = Task::where('task_code', $taskCode)->first();
                $task = $existingTask ?: Task::create([
                    'task_code' => $taskCode,
                    'task_name' => $taskName,
                    'reporting_cycle' => $reportingCycle,
                    'category' => $category,
                    'required_result' => $requiredResult,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'creator' => $user->name,
                ]);
                \Log::error('Task: ' . $taskData);
                // Xử lý organizations
                if (!empty($organizations)) {
                    foreach ($organizations as $data) {
                        list($taskCodeOrganization, $organizationCode, $organizationName, $organizationEmail, $organizationPhone) = explode('|', $data);
                        if ($taskCodeOrganization == $taskCode) {
                            $organization = Organization::where('code', $organizationCode)->where('name', $organizationName)->first();
                            if ($organization) {
                               
                                $taskDocument = TaskDocument::create([
                                    'document_id' => $document->id,
                                    'task_code' => $taskCode,
                                    'task_name' => $taskName,
                                    'reporting_cycle' => $reportingCycle,
                                    'category' => $category,
                                    'required_result' => $requiredResult,
                                    'start_date' => $startDate,
                                    'end_date' => $endDate,
                                    'status' => 'assign',
                                    'creator' => $userId,
                                    'organization_id' => $organization->id,
                                    'progress' => "Đang thực hiện"
                                ]);
                        
                                array_push($createdTaskDocuments, $taskCode);
                                // Lưu các tiêu chí liên quan đến tổ chức
                                $criterias = $request->input('criterias');
                                foreach ($criterias as $criteria) {
                                    list($taskCodeCriteria, $criteriaCode, $criteriaName, $result) = explode('|', $criteria);
                                    if ($taskCodeCriteria == $taskCode) {
                                        $existingCriteria = Criteria::where('code', $criteriaCode)->first();
                                        $existingCriteria = $existingCriteria ?: Criteria::create([
                                            'code' => $criteriaCode,
                                            'name' => $criteriaName,
                                        ]);
    
                                        CriteriasTask::create([
                                            'TaskID' => $taskDocument->id,
                                            'CriteriaID' => $existingCriteria->id,
                                            'CriteriaCode' => $criteriaCode,
                                            'CriteriaName' => $criteriaName,
                                            'DocumentID' => $document->id,
                                            'TaskCode' => $taskCode,
                                            'RequestResult' => $result,
                                            'progress' => "Đang thực hiện"
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
  
            }
            $uniqueArray = array_unique($createdTaskDocuments);
       
            foreach ($tasks as $taskData) {
                list($taskCode, $taskName, $reportingCycle, $category, $requiredResult, $startDate, $endDate) = explode('|', $taskData);
                $index = in_array($taskCode, $uniqueArray);
                #dd($taskCode);
                if ($index) continue;
                #dd($index);
                array_push($createdTaskDocuments, $taskCode);
                $existingTask = Task::where('task_code', $taskCode)->first();
                    $task = $existingTask ?: Task::create([
                        'task_code' => $taskCode,
                        'task_name' => $taskName,
                        'reporting_cycle' => $reportingCycle,
                        'category' => $category,
                        'required_result' => $requiredResult,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'creator' => $userId,
                        
                    ]);
                    $taskDocument = TaskDocument::create([
                        'document_id' => $document->id,
                        'task_code' => $taskCode,
                        'task_name' => $taskName,
                        'reporting_cycle' => $reportingCycle,
                        'category' => $category,
                        'required_result' => $requiredResult,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'status' => 'draft',
                        'creator' => $userId,
                        'organization_id' => 0,
                        'progress' => "Chưa giao việc"
                    ]);
    
                    // Lưu các tiêu chí cho các tasks không có organizations
                    $criterias = $request->input('criterias');
                    foreach ($criterias as $criteria) {
                        list($taskCodeCriteria, $criteriaCode, $criteriaName, $result) = explode('|', $criteria);
                        if ($taskCodeCriteria == $taskCode) {
                            $existingCriteria = Criteria::where('code', $criteriaCode)->first();
                            $existingCriteria = $existingCriteria ?: Criteria::create([
                                'code' => $criteriaCode,
                                'name' => $criteriaName,
                            ]);
    
                            CriteriasTask::create([
                                'TaskID' => $taskDocument->id,
                                'CriteriaID' => $existingCriteria->id,
                                'CriteriaCode' => $criteriaCode,
                                'CriteriaName' => $criteriaName,
                                'DocumentID' => $document->id,
                                'TaskCode' => $taskCode,
                                'RequestResult' => $result,
                                'progress' => "Chưa giao việc"
                            ]);
                        }
                    }
                    $uniqueArray = array_unique($createdTaskDocuments);
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
        $userId = Auth::id();
        $user = User::find($userId);
        
        // Kiểm tra xem người dùng có phải là người tạo tài liệu không
        //dd($userId);
        if ($document->creator == $userId) {
            // Nếu người dùng là người tạo, lấy tất cả TaskDocument liên quan
            $taskDocuments = $document->taskDocuments;
        } else {
            // Nếu không phải người tạo, lấy TaskDocument theo organization_id của người dùng
            $taskDocuments = $document->taskDocuments->filter(function ($task) use ($user) {
                return $task->organization_id === $user->organization_id;
            });
        }
        
        // Lấy criterias cho mỗi task
        $criterias = [];
        foreach ($taskDocuments as $task) {
            $criterias[$task->task_code] = CriteriasTask::where('DocumentID', $document->id)
                                                        ->where('TaskCode', $task->task_code)
                                                        ->get();
        }
    
    
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
        $organizations = Organization::all();
        return view('documents.show', compact('document', 'taskDocuments', 'criterias', 'organizations', 'weekTask', 'monthTask', 'quarterTask', 'yearTask'
        , 'timeParamsWeek', 'timeParamsMonth', 'timeParamsQuarter', 'timeParamsYear'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $document = Document::findOrFail($id);
        $userId = Auth::id();
        $user = User::find($userId);
        
        if ($document->creator == $userId) {
            $taskDocuments = $document->taskDocuments;
        } else {
            $taskDocuments = $document->taskDocuments->filter(function ($task) use ($user) {
                return $task->organization_id === $user->organization_id;
            });
        }
        
        $criterias = [];
        foreach ($taskDocuments as $task) {
            $criterias[$task->task_code] = CriteriasTask::where('DocumentID', $document->id)
                                                        ->where('TaskCode', $task->task_code)
                                                        ->get();
        }
    
    
        $weekTask = $taskDocuments->where('reporting_cycle' , 1);
        $monthTask = $taskDocuments->where('reporting_cycle' , 2);
        $quarterTask = $taskDocuments->where('reporting_cycle' , 3);
        $yearTask = $taskDocuments->where('reporting_cycle' , 4);
        
        $timeParamsWeek = $this->getTimeParameters(1);
        $timeParamsMonth = $this->getTimeParameters(2);
        $timeParamsQuarter = $this->getTimeParameters(3);
        $timeParamsYear = $this->getTimeParameters(4);

        $organizations = Organization::all();
        return view('documents.edit', compact('document', 'taskDocuments', 'criterias', 'organizations', 'weekTask', 'monthTask', 'quarterTask', 'yearTask'
        , 'timeParamsWeek', 'timeParamsMonth', 'timeParamsQuarter', 'timeParamsYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $userId = Auth::id();
        $user = User::find($userId);
        DB::beginTransaction();
       // dd($request);
        try {
            // Validate request data
            $validatedData = $request->validate([

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
            $taskCurrentResults = $request->input('task_current_result', []);
            $taskCurrentNotes = $request->input('task_current_note', []);
            $taskNames = $request->input('task_name', []);
            $typeCurrent = $request->input('typeCurrent', []);
            $numberCurrent = $request->input('numberCurrent', []);

            $criteriaTypeCurrent = $request->input('criteriaTypeCurrent', []);
            $criteriaNumberCurrent = $request->input('criteriaNumberCurrent', []);
            $criteriaCode = $request->input('criteria_code', []);
            $criteriaIds = $request->input('criteria_id', []);
            $criteriaName = $request->input('criteria_name', []);
            $criteriaResult = $request->input('criteria_required_result', []);
            $criteriaProgress = $request->input('criterion_progress', []);
            $criteriaProgressEvaluation = $request->input('criteria_progress_evaluation', []);
            $criteriaCurrentResult = $request->input('criteria_current_result', []);
            $criteriaCurrentNote = $request->input('criteria_current_note', []);
            // Kiểm tra nếu $documentIds không rỗng và lấy phần tử đầu tiên
            if (!empty($documentIds) && is_array($documentIds)) {
                $document = Document::findOrFail($documentIds[0]);
                
                if ($document) {
                    if($document->creator == $userId){
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
                    }
                    

                    if (!empty($taskIds) && is_array($taskIds)) {
                         // Cập nhật các tác vụ
                        foreach ($taskIds as $index => $taskId) {
                            $task = TaskDocument::find($taskId);
                            if ($task) {
                              
                                $task->task_name = $taskNames[$index] ?? '';
                                $task->required_result = $requiredResults[$index] ?? '';
                                $task->progress = "Hoàn Thành" ?? '';
                                $task->progress_evaluation = $progressEvaluations[$index] ?? '';
                                $task->save();
                                $record = TaskResult::where('id_task_criteria' , $task->id)->where("document_id", $document->id)->where("type_save", 1)->first();
                                
                                if($record){
                                    $record->result =  $taskCurrentResults[$index] ?? '';
                                    $record->description =  $taskCurrentNotes[$index] ?? '';
                                    $record->number_type =  $numberCurrent[$index] ?? '';
                                    $record->type =  $task->reporting_cycle ?? '';
                                    $record->save();
                                    HistoryChangeDocument::create([
                                        'mapping_id' => $task->id,
                                        'type_save' => 1,
                                        'result' => $record->result,
                                        'description' => $record->description,
                                        'number_cycle' => $record->number_type,
                                        'type_cycle' => $record->type,
                                        'update_date' => Carbon::now(),
                                        'update_user'=> $userId
                                    ]);
                                }else{
                                    $hasRecord = TaskResult::create([
                                        'id_task_criteria' => $task->id,
                                        'document_id' => $document->id,
                                        'result' => $taskCurrentResults[$index] ?? '',
                                        'description' => $taskCurrentNotes[$index] ?? '',
                                        'number_type' => $numberCurrent[$index] ?? '',
                                        'type' => $task->reporting_cycle ?? '',
                                        'type_save' => 1
                                    ]);


                                }
                            }
                        }
                    }


                    if (!empty($criteriaIds) && is_array($criteriaIds)) {
                         // Cập nhật các tác vụ
                        foreach ($criteriaIds as $index => $taskId) {
                            $criteria = CriteriasTask::find($taskId);
                            if ($criteria) {
                                //dd($criteria);
                                $criteria->CriteriaName = $criteriaName[$index] ?? '';
                                $criteria->RequestResult = $criteriaResult[$index] ?? '';
                                $criteria->progress = "Hoàn Thành" ?? '';
                                $criteria->progress_evaluation = $criteriaProgressEvaluation[$index] ?? '';
                                $criteria->save();
                                
                                $record = TaskResult::where('id_task_criteria' , $criteria->id)->where("document_id", $document->id)
                                ->where("type_save", 2)->first();
                                
                                if($record){
                                    $record->result =  $criteriaCurrentResult[$index] ?? '';
                                    $record->description =  $criteriaCurrentNote[$index] ?? '';
                                    $record->number_type =  $criteriaNumberCurrent[$index] ?? '';
                                    $record->type =  $criteriaTypeCurrent[$index] ?? '';
                                    $record->save();
                                    HistoryChangeDocument::create([
                                        'mapping_id' => $criteria->id,
                                        'type_save' => 2,
                                        'result' => $record->result,
                                        'description' => $record->description,
                                        'number_cycle' => $record->number_type,
                                        'type_cycle' => $record->type,
                                        'update_date' => Carbon::now(),
                                        'update_user'=> $userId
                                    ]);
                                }else{
                                    $hasRecord = TaskResult::create([
                                        'id_task_criteria' => $criteria->id,
                                        'document_id' => $document->id,
                                        'result' => $criteriaCurrentResult[$index] ?? '',
                                        'description' => $criteriaCurrentNote[$index] ?? '',
                                        'number_type' => $criteriaNumberCurrent[$index] ?? '',
                                        'type' => $criteriaTypeCurrent[$index] ?? '',
                                        'type_save' => 2
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
