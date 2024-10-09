<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Document;
use App\Models\File;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskDocument;
use App\Models\TaskTarget;
use App\Models\Unit;
use App\Models\Organization;
use App\Models\CriteriasTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\OrganizationTask;
use App\Models\TaskResult;
use App\Models\HistoryChangeDocument;
use App\Models\DocumentCategory;
use App\Helpers\TimeHelper;
use App\Models\OrganizationType;
use App\Enums\TaskStatus;
use App\Models\IndicatorGroup;
use App\Models\TaskGroup;

use App\Services\MasterWorkResultTypeService;

class DocumentController extends Controller
{
    public function searchDocuments(Request $request)
    {
        $query = $request->input('document_code');
        $documents = Document::where('document_code', 'LIKE', '%'. $query . '%')
        ->where('isDelete', 0)
        ->take(10)
        ->get();
        return response()->json($documents);
    }
    public function searchDocumentsName(Request $request)
    {
        $query = $request->input('document_name');
        $documents = Document::where('document_name', 'LIKE', '%' . $query . '%')->where('isDelete', 0)->take(10)->get();
        return response()->json($documents);
    }


    public function changeComplete(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::find(Auth::id());

            $task = TaskResult::find($id);
            if (($task->status == 'sub_admin_complete' || $task->status == 'admin_approves' ) && ($user->role == 'admin' || $user->role == 'supper_admin')) {
                $task->status = 'complete';
                $task->save();
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Thành công.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Task not found.'], 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating task/target: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }
    
    public function changeApprovedAll(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::find(Auth::id());
            $task = TaskTarget::find($id);
            if ($task->status == 'processing' && ($user->role == 'admin' || $user->role == 'supper_admin')) {
                $task->status = 'complete';
                $task->save();
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Thành công.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Task not found.'], 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating task/target: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }
    public function changeApproved(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::find(Auth::id());

            $task = TaskResult::find($id);
            if ($task->status == 'sub_admin_complete' && ($user->role == 'admin' || $user->role == 'supper_admin')) {
                $task->status = 'admin_approves';
                $task->save();
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Thành công.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Task not found.'], 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating task/target: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }
    public function approvedTaskTarget($id, $type)
    {
        try {
            $taskTarget = TaskTarget::where('id', $id)->where('isDelete', 0)->first();
            $document = Document::findOrFail($taskTarget->document_id);
            $userId = Auth::id();
            $user = User::find($userId);

            if ($type == 'target') {
                if ($user->role == 'admin' || $user->role == 'supper_admin') {
                    $taskDocuments = $document->taskTarget->where('id', $id)->where('type', 'target')->where('isDelete', 0);
                } else {
                    $taskDocuments = $document->taskTarget->where('id', $id)->filter(function ($task) use ($user) {
                        return $task->organization_id == $user->organization_id;
                    })->where('isDelete', 0);
                }
                $groupTarget =  IndicatorGroup::where('isDelete', 0)->get();
                $organizations = Organization::where('isDelete', 0)->orderBy('name', 'asc')->get();
                $workResultTypes = MasterWorkResultTypeService::index();
                $lstResult = $this->getFullDataTaskResult($taskTarget->id, $taskTarget->cycle_type, $taskTarget->getCurrentCycle());
                $taskDocuments = $taskDocuments->whereNotNull('organization_id');

                $taskTarget = TaskTarget::where('id', $id)->where('isDelete', 0)->first();
                $hasComplete = TaskResult::where('id_task_criteria', $taskTarget->id)->where('status', '!=', 'complete')->count() === 0;
                
                $units = Unit::all();
                return view('documents.viewApprovedReportTarget', compact('hasComplete','units', 'document', 'taskDocuments', 'organizations', 'taskTarget', 'groupTarget', 'workResultTypes', 'lstResult'));
            } else {


                if ($user->role == 'admin' || $user->role == 'supper_admin') {
                    $taskDocuments = $document->taskTarget->where('id', $id)->where('type', 'task')->where('isDelete', 0);
                } else {
                    $taskDocuments = $document->taskTarget->where('id', $id)->filter(function ($task) use ($user) {
                        return $task->organization_id == $user->organization_id;
                    })->where('isDelete', 0);
                }
                $groupTask =  TaskGroup::where('isDelete', 0)->get();
                $organizations = Organization::where('isDelete', 0)->orderBy('name', 'asc')->get();
                $workResultTypes = MasterWorkResultTypeService::index();
                $hasComplete = TaskResult::where('id_task_criteria', $taskTarget->id)->where('status', '!=', 'complete')->count() === 0;
                $lstResult = $this->getFullDataTaskResult($taskTarget->id, $taskTarget->cycle_type, $taskTarget->getCurrentCycle());
                $taskDocuments = $taskDocuments->whereNotNull('organization_id');
                //dd($hasComplete);
                return view('documents.viewApprovedReportTask', compact('hasComplete','document', 'taskDocuments', 'organizations', 'taskTarget', 'groupTask', 'workResultTypes', 'lstResult'));
            }
        } catch (\Exception $e) {
            \Log::error('Error reportViewUpdate: ' . $e->getMessage());
        }
    }
    public function updateTaskCycle(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $taskResult = TaskResult::find($id);
            //dd($taskResult);
            $task = TaskTarget::where('isDelete', 0)->find($taskResult->id_task_criteria);

            // Xử lý file upload
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('tasks', $fileName, 'public');
                    File::create([
                        'document_id' => $taskResult->id,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'number_type' => $taskResult->number_type,
                        'cycle_type' => $taskResult->type,
                        'type' => $taskResult->type_save
                    ]);
                }
            }
            $result = isset($request->request_results) ? $request->request_results : '';
            if ($taskResult) {
                HistoryChangeDocument::create([
                    'mapping_id' => $taskResult->id,
                    'type_save' => $taskResult->type_save == 'task'?1:2,
                    'result' => $result,
                    'description' => $result,
                    'number_cycle' => $taskResult->number_type,
                    'type_cycle' => $taskResult->type,
                    'update_date' => Carbon::now(),
                    'update_user' => Auth::id()
                ]);
                $taskResult->result = $result;
                $taskResult->description =  $result;
                $taskResult->process_code = $task->getProcessCode();
                $taskResult->status =  "staff_complete";
                $taskResult->save();
            }
            DB::commit();

            if ($taskResult->type_save == 'target') return redirect()->route('documents.report.target')->with('success', 'Cập nhật báo cáo thành công!');
            else return redirect()->route('documents.report')->with('success', 'Cập nhật báo cáo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating task/target: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    ///Save giao việc
    public function assignOrganizations(Request $request)
    {
        DB::beginTransaction();
        $type = '';
        try {
            $organizations = $request->input('organizations');
            $userId = Auth::id();
            $processedOrganizations = [];
            $user = User::find($userId);
            if (!empty($organizations)) {
                foreach ($organizations as $data) {
                    $organizationCode = $data['code'];
                    $organizationName = $data['name'];
                    $id = $data['id'];
                    $taskId = $data['task_id'];
                    if (in_array($organizationCode, $processedOrganizations)) {
                        continue;
                    }
       
                    $taskTarget = TaskTarget::where('isDelete', 0)->find($taskId);
                    $taskTarget->status = 'processing';
                    $taskTarget->save();
                    $organization = Organization::where('id', $id)->where('isDelete', 0)->first();
                    if ($organization) {
                        $typeRecord = $taskTarget->type === 'target' ? "Chỉ tiêu" : "Nhiệm vụ";
                        $type = $taskTarget->type;
                        $hasOrganization = TaskResult::where('organization_id', $organization->id)->where('isDelete', 0)->where('id_task_criteria', $taskTarget->id)->first();
                        \Log::error('Error taskTarget: ' . $taskTarget->getCurrentCycle());

                        if (!$hasOrganization) {
                            TaskResult::create([
                                'id_task_criteria' => $taskTarget->id,
                                'document_id' => $taskTarget->document_id,
                                'organization_id' => $organization->id,
                                'number_type' => $taskTarget->getCurrentCycle(),
                                'type' => $taskTarget->cycle_type,
                                'type_save' => $taskTarget->type,
                                'status' => 'assign',
                                'process_code' => $taskTarget->getProcessCode()
                            ]);
                        }
                        $processedOrganizations[] = $organizationCode;
                    }
                }
            }

            DB::commit();
            session(['success' =>  $typeRecord . ' ' . 'đã giao cho các tổ chức']);
            return response()->json([
                'success' => true,
                'type' => $type,
                'message' => $typeRecord . ' ' . 'đã giao cho các tổ chức'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating document: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Đã xảy ra lỗi, vui lòng thử lại.'
            ]);
        }
    }
    /// reportTargetView
    public function reportTargetView(Request $request, $text = null)
    {
     
        $userId = Auth::id();
        $user = User::find($userId);
        $query = Document::query();
        //dd($request->document_code);
        $taskTargetQuery = TaskResult::query();
        if ($request->filled('document_code')) {
            $query->where('document_code', 'like', '%'.$request->document_code.'%')->where('isDelete', 0);
        }
        //dd($query->get());
        if ($request->filled('organization_id')) {
            $query->where('issuing_department', $request->organization_id)->where('isDelete', 0);
        }
        if ($text) {
            $query->where('document_name', 'like', '%' . $text . '%');
        }
        if ($user->role == 'staff' || $user->role == 'sub_admin') {
            $documents = $query->whereHas('taskResult', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })->with('issuingDepartment')->orderBy('created_at', 'desc')->get();

            if ($documents && method_exists($documents, 'pluck')) {
                $taskDocuments = TaskResult::whereIn('document_id', $documents->pluck('id'))
                    ->where('organization_id', $user->organization->id)
                    ->where('type_save', 'target');
            } else {
                $taskDocuments = collect();
            }
        } else if ($user->role == 'admin' || $user->role == 'supper_admin') {
            $documents = $query->with('issuingDepartment')->where('isDelete', 0)->orderBy('created_at', 'desc')->get();

            $taskDocuments = TaskResult::whereIn('document_id', $documents->pluck('id'))->where('type_save', 'target');
        }
        if ($request->filled('status')) {
            $taskDocuments =  $taskDocuments->where('status', $request->status);
        }
        $taskDocuments = $taskDocuments->orderBy('created_at', 'desc')->paginate(10);
        $organizations = Organization::where('isDelete', 0)->orderBy('name', 'asc')->get();
        $organizationsType = OrganizationType::where('isDelete', 0)->orderBy('type_name', 'asc')->get();

        // dd($taskDocuments);
        return view('documents.reportTaskget', compact('documents', 'organizations', 'taskDocuments', 'organizationsType'));
    }
    /**
     * Display a listing of the resource.
     */

    public function reportView(Request $request, $text = null)
    {
        //dd($request);
        $userId = Auth::id();
        $user = User::find($userId);
        $query = Document::query();

        $taskTargetQuery = TaskResult::query();
        if ($request->filled('document_code')) {
            $query->where('document_code', $request->document_code)->where('isDelete', 0);
        }

        if ($request->filled('organization_id')) {
            $query->where('issuing_department', $request->organization_id)->where('isDelete', 0);
        }

        if ($text) {
            $query->where('document_name', 'like', '%' . $text . '%');
        }
        if ($user->role == 'staff' || $user->role == 'sub_admin') {
            $documents = $query->whereHas('taskResult', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })->with('issuingDepartment')->orderBy('created_at', 'desc')->get();

            if ($documents && method_exists($documents, 'pluck')) {
                $taskDocuments = TaskResult::whereIn('document_id', $documents->pluck('id'))
                    ->where('organization_id', $user->organization->id)
                    ->where('type_save', 'task');
   
            } else {
                $taskDocuments = collect();
            }
        } else if ($user->role == 'admin' || $user->role == 'supper_admin') {
            $documents = $query->with('issuingDepartment')->where('isDelete', 0)->orderBy('created_at', 'desc')->get();

            $taskDocuments = TaskResult::whereIn('document_id', $documents->pluck('id'))->where('type_save', 'task');
        }
        if ($request->filled('status')) {
            $taskDocuments =  $taskDocuments->where('status', $request->status);
        }
        $taskDocuments = $taskDocuments->orderBy('created_at', 'desc')->paginate(10);
        $organizations = Organization::where('isDelete', 0)->orderBy('name', 'asc')->get();
        $organizationsType = OrganizationType::where('isDelete', 0)->orderBy('type_name', 'asc')->get();

        // dd($taskDocuments);
        return view('documents.report', compact('documents', 'organizations', 'taskDocuments', 'organizationsType'));
    }

    public function index(Request $request, $text = null)
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $query = Document::query();
        $taskDocumentsQuery = TaskDocument::query();
        $executionTimeFrom = $request->input('execution_time_from');
        $executionTimeTo = $request->input('execution_time_to');

        // Kiểm tra nếu cả hai thời gian đều có giá trị
        if ($executionTimeFrom && $executionTimeTo) {
            try {
                // Chuyển đổi thời gian thành đối tượng Carbon để dễ so sánh
                $executionTimeFrom = \Carbon\Carbon::createFromFormat('Y-m-d', $executionTimeFrom);
                $executionTimeTo = \Carbon\Carbon::createFromFormat('Y-m-d', $executionTimeTo);

                // Kiểm tra nếu thời gian từ lớn hơn thời gian đến
                if ($executionTimeFrom->gt($executionTimeTo)) {
                    return redirect()->back()->withErrors([
                        'error' => "Thời gian từ ({$executionTimeFrom->format('d-m-Y')}) không được lớn hơn thời gian đến ({$executionTimeTo->format('d-m-Y')})."
                    ]);
                }
            } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                return redirect()->back()->withErrors(['error' => 'Định dạng thời gian không hợp lệ. Vui lòng nhập đúng định dạng ngày: dd-mm-yyyy.']);
            }
        }

        if ($request->filled('document_code')) {
            $query->where('document_code', 'like', '%' . $request->document_code . '%');
        }

        if ($request->filled('document_name')) {
            $query->where('document_name', 'like', '%' . $request->document_name . '%');
        }

        if ($request->filled('organization_id')) {
            $query->where('issuing_department', $request->organization_id);
        }
        if ($executionTimeFrom) {
            $query->whereDate('release_date', '>=', $executionTimeFrom);
        }

        if ($executionTimeTo) {
            $query->whereDate('release_date', '<=', $executionTimeTo);
        }
        if ($text) {
            $query->where('document_name', 'like', '%' . $text . '%');
        }
        $documents = $query->where('isDelete', 0)->with('issuingDepartment')->orderBy('created_at', 'desc')->paginate(10);
        $organizations = Organization::where('isDelete', 0)->whereNotNull('organization_type_id')->orderBy('name', 'asc')->get();

       // dd($organizations);
        $organizationsType = OrganizationType::where('isDelete', 0)->orderBy('type_name', 'asc')->get();

        return view('documents.index', compact('documents', 'organizations', 'organizationsType'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizations = Organization::where('isDelete', 0)->orderBy('name', 'asc')->get();
        $documentCategory = DocumentCategory::where('isDelete', 0)->get();
        $organizationsType = OrganizationType::where('isDelete', 0)->orderBy('type_name', 'asc')->get();

        // Truyền dữ liệu tổ chức vào view
        return view('documents.create', compact('organizations', 'documentCategory', 'organizationsType'));
    }

    public function checkDocumentCode($documentCode)
    {
        // Kiểm tra xem mã công việc có tồn tại trong cơ sở dữ liệu không
        $exists = Document::where('document_code', $documentCode)->where('isDelete', 0)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function getHistory($id)
    {
        // \DB::enableQueryLog();
        $taskTargetIds = TaskResult::where('id_task_criteria', $id)->pluck('id');
             // Lấy danh sách các ID dưới dạng mảng

        // Kiểm tra nếu không có ID nào được tìm thấy
        if ($taskTargetIds->isEmpty()) {
            return collect(); // Trả về một Collection rỗng
        }

        // Lấy danh sách các bản ghi từ bảng HistoryChangeDocument dựa trên danh sách ID
        $lstHistory = HistoryChangeDocument::whereIn('mapping_id', $taskTargetIds)->get();
        //dd($taskTargetIds);
        // return $lstHistory; // Trả về danh sách các bản ghi
        // dd(\DB::getQueryLog());

        return response()->json(['histories' => $lstHistory]);
    }

    public function getHistoryById($id)
    {

        $taskTargetId = TaskResult::where('id', $id)->first();

        // Lấy danh sách các bản ghi từ bảng HistoryChangeDocument dựa trên danh sách ID
        $lstHistory = HistoryChangeDocument::where('mapping_id', $taskTargetId->id)
            ->get();
        // return $lstHistory; // Trả về danh sách các bản ghi

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
            // Validate input
            $request->validate([
                'document_code' => 'required',
                'document_name' => 'required', // Validation rule for textarea
                'issuing_department' => 'required',
                'release_date' => 'required|date',
                'category_id' => 'required'
            ], [
                'document_code.required' => 'Mã văn bản là bắt buộc.',
                'document_code.unique' => 'Mã văn bản đã tồn tại.',
                'document_name.required' => 'Tên văn bản là bắt buộc.',
                'issuing_department.required' => 'Cơ quan ban hành là bắt buộc.',
                'category_id.required' => 'Loại văn bản là bắt buộc.',
                'release_date.required' => 'Ngày phát hành là bắt buộc.',
                'release_date.date' => 'Ngày phát hành không hợp lệ.',
            ]);


            $documentCode = str_replace(['/', ' '], '-', $request->input('document_code'));
            $exitItem = Document::where('isDelete', 0)->where('document_code', $documentCode)->where('isDelete', 0)->first();
            if ($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
            // Lưu tài liệu
            $document = Document::create([
                'document_code' => $documentCode,
                'document_name' => $request->input('document_name'), // Lưu giá trị textarea
                'issuing_department' => $request->input('issuing_department'),
                'release_date' => $request->input('release_date'),
                'category_id' => $request->input('category_id'),
                'creator' => Auth::id()
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
            // Commit transaction nếu tất cả các bước trên thành công
            DB::commit();

            return redirect()->route('documents.index')->with('success', 'Văn bản tạo thành công!');
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi xảy ra
            DB::rollBack();

            // Ghi lỗi vào log (tùy chọn)
            \Log::error('Error creating document: ' . $e->getMessage());

            return redirect()->back()->withErrors($e->getMessage())->withInput();
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
                ->where('isDelete', 0)
                ->get();
        }


        $weekTask = $taskDocuments->where('reporting_cycle', 1);
        $monthTask = $taskDocuments->where('reporting_cycle', 2);
        $quarterTask = $taskDocuments->where('reporting_cycle', 3);
        $yearTask = $taskDocuments->where('reporting_cycle', 4);

        $timeParamsWeek = TimeHelper::getTimeParameters(1);
        $timeParamsMonth = TimeHelper::getTimeParameters(2);
        $timeParamsQuarter = TimeHelper::getTimeParameters(3);
        $timeParamsYear = TimeHelper::getTimeParameters(4);

        $organizations = Organization::where('isDelete', 0)->orderBy('name', 'asc')->get();
        return view('documents.show', compact(
            'document',
            'taskDocuments',
            'criterias',
            'organizations',
            'weekTask',
            'monthTask',
            'quarterTask',
            'yearTask',
            'timeParamsWeek',
            'timeParamsMonth',
            'timeParamsQuarter',
            'timeParamsYear'
        ));
    }
    public function reportViewUpdateTarget(string $id)
    {
        try {
            $taskResult = TaskResult::where('id', $id)->where('isDelete', 0)->first();
            $taskTarget = TaskTarget::where('id', $taskResult->id_task_criteria)->where('isDelete', 0)->first();            
            $document = Document::findOrFail($taskTarget->document_id);
            $userId = Auth::id();
            $user = User::find($userId);

            if ($user->role == 'admin' || $user->role == 'supper_admin') {
                $taskDocuments = $document->taskResult;
            } else {
                $taskDocuments = $document->taskResult->filter(function ($task) use ($user) {
                    return $task->organization_id == $user->organization_id;
                });
            }
            $groupTarget =  IndicatorGroup::where('isDelete', 0)->get();
            $organizations = Organization::where('isDelete', 0)->orderBy('name', 'asc')->get();
            $workResultTypes = MasterWorkResultTypeService::index();
            $lstResult = $this->getFullDataTaskResult($taskTarget->id, $taskTarget->cycle_type, $taskTarget->getCurrentCycle());
            $units = Unit::all();
            return view('documents.reportUpdateTaskget', compact('taskResult','units', 'document', 'taskDocuments', 'organizations', 'taskTarget', 'groupTarget', 'workResultTypes', 'lstResult'));
        } catch (\Exception $e) {
            \Log::error('Error reportViewUpdate: ' . $e->getMessage());
        }
    }
    public function reportViewUpdateRole(string $id, string $type)
    {
        $user = User::find(Auth::id());
        $taskResult = TaskResult::where('id_task_criteria', $id)->where('organization_id', $user->organization_id)->first();
        if($type == 'task'){
            return $this->reportViewUpdate($taskResult->id);
        }else{
            return $this->reportViewUpdateTarget($taskResult->id); 
        }
    }
    public function reportViewUpdate(string $id)
    {
        try {
            $taskResult = TaskResult::where('id', $id)->where('isDelete', 0)->first();
            $taskTarget = TaskTarget::where('id', $taskResult->id_task_criteria)->where('isDelete', 0)->first();
            $document = Document::findOrFail($taskTarget->document_id);
            $userId = Auth::id();
            $user = User::find($userId);

            if ($user->role == 'admin' || $user->role == 'supper_admin') {
                $taskDocuments = $document->taskResult;
            } else {
                $taskDocuments = $document->taskResult->filter(function ($task) use ($user) {
                    return $task->organization_id == $user->organization_id;
                });
            }
            $groupTask =  TaskGroup::where('isDelete', 0)->get();
            $organizations = Organization::where('isDelete', 0)->orderBy('name', 'asc')->get();
            $workResultTypes = MasterWorkResultTypeService::index();
            $lstResult = $this->getFullDataTaskResult($taskTarget->id, $taskTarget->cycle_type, $taskTarget->getCurrentCycle());

            return view('documents.reportUpdate', compact('taskResult', 'document', 'taskDocuments', 'organizations', 'taskTarget', 'groupTask', 'workResultTypes', 'lstResult'));
        } catch (\Exception $e) {
            \Log::error('Error reportViewUpdate: ' . $e->getMessage());
        }
    }

    public static function getFullDataTaskResult($taskTargetId, $type, $currentNumberType)
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentWeek = Carbon::now()->weekOfYear;

        $weeksToFetch = [];
        if ($currentMonth === 1) {
            $weeksToFetch = [11, 12];
            $yearToFetch = $currentYear - 1;
        } else {
            $weeksToFetch[] = $currentWeek;
            $yearToFetch = $currentYear;
        }
        return TaskResult::where('type', $type)
            ->where('id_task_criteria', $taskTargetId)
            ->whereYear('created_at', $yearToFetch)
            ->whereIn('number_type', $weeksToFetch)
            ->where('number_type', '!=', $currentNumberType)
            ->orderBy('number_type')
            ->get();
    }

    public function detailsReport(string $id)
    {
        try {
            $taskResult = TaskResult::where('id', $id)->where('isDelete', 0)->first();
            $taskTarget = TaskTarget::where('id', $taskResult->id_task_criteria)->where('isDelete', 0)->first();
            $document = Document::findOrFail($taskResult->document_id);
            $userId = Auth::id();
            $user = User::find($userId);

            if ($user->role == 'admin' || $user->role == 'supper_admin') {
                $taskDocuments = $document->taskResult;
            } else {
                $taskDocuments = $document->taskResult->filter(function ($task) use ($user) {
                    return $task->organization_id == $user->organization_id;
                });
            }
            $groupTask =  TaskGroup::where('isDelete', 0)->get();
            $organizations = Organization::where('isDelete', 0)->orderBy('name', 'asc')->get();
            $workResultTypes = MasterWorkResultTypeService::index();
            $lstResult = $this->getFullDataTaskResult($taskTarget->id, $taskTarget->cycle_type, $taskTarget->getCurrentCycle());

            return view('documents.viewDetailsReport', compact('taskResult', 'document', 'taskDocuments', 'organizations', 'taskTarget', 'groupTask', 'workResultTypes', 'lstResult'));
        } catch (\Exception $e) {
            \Log::error('Error reportViewUpdate: ' . $e->getMessage());
        }
    }


    public function detailsReportTarget(string $id)
    {

        try {
            $taskResult = TaskResult::where('id', $id)->where('isDelete', 0)->first();
            $taskTarget = TaskTarget::where('id', $taskResult->id_task_criteria)->where('isDelete', 0)->first();
            $document = Document::findOrFail($taskResult->document_id);
            $userId = Auth::id();
            $user = User::find($userId);

            if ($user->role == 'admin' || $user->role == 'supper_admin') {
                $taskDocuments = $document->taskResult;
            } else {
                $taskDocuments = $document->taskResult->filter(function ($task) use ($user) {
                    return $task->organization_id == $user->organization_id;
                });
            }
            $groupTarget =  IndicatorGroup::where('isDelete', 0)->get();
            $organizations = Organization::where('isDelete', 0)->orderBy('name', 'asc')->get();
            $workResultTypes = MasterWorkResultTypeService::index();
            $lstResult = $this->getFullDataTaskResult($taskTarget->id, $taskTarget->cycle_type, $taskTarget->getCurrentCycle());

            $units = Unit::all();
            return view('documents.viewDetailsReportTarget', compact('taskResult','units', 'document', 'taskDocuments', 'organizations', 'taskTarget', 'groupTarget', 'workResultTypes', 'lstResult'));
        } catch (\Exception $e) {
            \Log::error('Error reportViewUpdate: ' . $e->getMessage());
        }
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
            $taskDocuments = $document->taskDocuments->where('isDelete', 0);
        } else {
            $taskDocuments = $document->taskDocuments->filter(function ($task) use ($user) {
                return $task->organization_id === $user->organization_id;
            })->where('isDelete', 0);
        }

        $criterias = [];
        foreach ($taskDocuments as $task) {
            $criterias[$task->task_code] = CriteriasTask::where('DocumentID', $document->id)
                ->where('TaskCode', $task->task_code)
                ->where('isDelete', 0)
                ->get();
        }


        $weekTask = $taskDocuments->where('reporting_cycle', 1);
        $monthTask = $taskDocuments->where('reporting_cycle', 2);
        $quarterTask = $taskDocuments->where('reporting_cycle', 3);
        $yearTask = $taskDocuments->where('reporting_cycle', 4);

        $timeParamsWeek = TimeHelper::getTimeParameters(1);
        $timeParamsMonth = TimeHelper::getTimeParameters(2);
        $timeParamsQuarter = TimeHelper::getTimeParameters(3);
        $timeParamsYear = TimeHelper::getTimeParameters(4);

        $organizations = Organization::where('isDelete', 0)->orderBy('name', 'asc')->get();
        $documentCategory = DocumentCategory::where('isDelete', 0)->get();
        $organizationsType = OrganizationType::where('isDelete', 0)->orderBy('type_name', 'asc')->get();

        return view('documents.edit', compact(
            'document',
            'taskDocuments',
            'criterias',
            'organizations',
            'weekTask',
            'monthTask',
            'quarterTask',
            'yearTask',
            'timeParamsWeek',
            'timeParamsMonth',
            'timeParamsQuarter',
            'timeParamsYear',
            'documentCategory',
            'organizationsType'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $userId = Auth::id();
        $user = User::find($userId);
        DB::beginTransaction();
        //dd($request);
        try {
            // Validate request data
            $request->validate([
                'document_code' => [
                    'required'
                ],
                'document_name' => 'required', // Validation rule for textarea
                'issuing_department' => 'required',
                'release_date' => 'required|date',
            ], [
                'document_code.required' => 'Mã văn bản là bắt buộc.',
                'document_code.unique' => 'Mã văn bản đã tồn tại.',
                'document_name.required' => 'Tên văn bản là bắt buộc.',
                'issuing_department.required' => 'Cơ quan, tổ chức là bắt buộc.',
                'release_date.required' => 'Ngày phát hành là bắt buộc.',
                'release_date.date' => 'Ngày phát hành không hợp lệ.',
            ]);

            $documentCode = str_replace(['/', ' '], '-', $request->input('document_code'));
            $documentId = $request->input('document_id');
            $exitItem = Document::where('isDelete', 0)->where('document_code', $documentCode)->where('id', '!=', $documentId)->first();
            if ($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
            // Lấy dữ liệu từ request



            $documentName = $request->input('document_name');
            $issuingDepartment = $request->input('issuing_department');
            $releaseDate = $request->input('release_date');
            // Kiểm tra nếu $documentIds không rỗng và lấy phần tử đầu tiên
            if (!empty($documentId)) {
                $document = Document::findOrFail($documentId);

                if ($document) {
                    if ($document->creator == $userId) {
                        if ($request->hasFile('files')) {
                            //dd($request->file('files'));
                            foreach ($request->file('files') as $file) {
                                $fileName = time() . '_' . $file->getClientOriginalName();
                                $filePath = $file->storeAs('documents', $fileName, 'public');

                                // Lưu thông tin file vào cơ sở dữ liệu
                                File::create([
                                    'document_id' => $document->id,
                                    'file_name' => $fileName,
                                    'file_path' => $filePath,
                                    'type' => 3
                                ]);
                            }
                        }
                        // dd($document);
                        // Cập nhật thông tin tài liệu
                        $document->document_name = $documentName;
                        $document->issuing_department = $issuingDepartment;
                        $document->release_date = $releaseDate;
                        $document->document_code = $documentCode;
                        $document->save();
                        DB::commit();

                        return redirect()->route('documents.index')->with('success', 'Cập nhật văn bản thành công!');
                    }
                }
            } else {
                return redirect()->back()->with('error', 'No document IDs found.');
            }
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi xảy ra
            DB::rollBack();

            // Ghi lỗi vào log
            \Log::error('Error creating document: ' . $e->getMessage());

            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }
    function containsNull($array)
    {
        foreach ($array as $value) {
            if ($value === null) {
                return true;
            }
        }
        return false;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $item = Document::findOrFail($id);
        $item->isDelete = 1;
        $item->save();

        $taskTargets = TaskTarget::where('document_id', $id)->get();

        foreach ($taskTargets as $taskTarget) {
            $taskTarget->isDelete = 1;
            $taskTarget->save();
        }

        $taskReults = TaskResult::where('document_id', $id)->get();

        foreach ($taskReults as $taskReult) {
            $taskReult->isDelete = 1;
            $taskReult->save();
        }

        // Chuyển hướng về trang danh sách tài liệu
        return redirect()->route('documents.index')->with('success', 'Xóa thành công văn bản.');
    }
}
