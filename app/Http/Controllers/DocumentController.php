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
    public function updateTaskCycle(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::find(Auth::id());

            $task = TaskTarget::where('isDelete', 0)->find($id);
            $document = Document::findOrFail($task->document_id);
            $currentNumber = $task->getCurrentCycle();
            $currentType = $task->cycle_type;
            $typeFile = '1';
            if($task->type == 'target') $typeFile = '2';
            if ($task->status == 'sub_admin_complete' && ($user->role == 'admin' || $user->role == 'supper_admin')) {
                $checkbox = isset($request->is_completed) ? $request->is_completed : '';
                $task->is_completed = $request->is_completed;
                $task->status = $checkbox ? 'complete' : $task->status;
                $task->save();
                DB::commit();
                return redirect()->route('documents.report')->with('success', 'Cập nhật báo cáo thành công!');
            } else {
                // Xử lý file upload
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $filePath = $file->storeAs('tasks', $fileName, 'public');
                        File::create([
                            'document_id' => $task->id,
                            'file_name' => $fileName,
                            'file_path' => $filePath,
                            'number_type' => $currentNumber,
                            'cycle_type' => $currentType,
                            'type' => $typeFile
                        ]);
                    }
                }
                $hasComplete = ($task->status == 'sub_admin_complete' || $task->status == 'complete') ? true : false;

                if ($task && !$hasComplete) {
                    $task->description = $task->getStatus();
                    $task->is_completed = 0;
                    $task->status = "staff_complete";

                    $record = TaskResult::where('id_task_criteria', $task->id)->where('isDelete', 0)->where("document_id", $document->id)->where("type_save", $task->getType())->first();
                    if ($record) {
                        $record->result =  isset($request->request_results) ? $request->request_results : '';
                        $record->description =  'ok';
                        $record->number_type =  $currentNumber;
                        $record->type =  $currentType ;
                        $record->save();
                    } else {
                        $record = TaskResult::create([
                            'id_task_criteria' => $task->id,
                            'document_id' => $document->id,
                            'result' => isset($request->request_results) ? $request->request_results : '',
                            'description' => 'ok',
                            'number_type' => $currentNumber,
                            'type' => $currentType,
                            'type_save' => $task->getType()
                        ]);
                    }

                    HistoryChangeDocument::create([
                        'mapping_id' => $task->id,
                        'type_save' => 1,
                        'result' => $record->result,
                        'description' => $record->description,
                        'number_cycle' => $record->number_type,
                        'type_cycle' => $record->type,
                        'update_date' => Carbon::now(),
                        'update_user' => Auth::id()
                    ]);
                    $now = now();
                    if ($task->is_completed) {
                        if ($task->end_date >= $now) {
                            $task->status_code = TaskStatus::COMPLETED_IN_TIME->value;
                        } else {
                            $task->status_code = TaskStatus::COMPLETED_OVERDUE->value;
                        }
                    } else {
                        if ($task->end_date >= $now) {
                            $task->status_code = TaskStatus::IN_PROGRESS_IN_TIME->value;
                        } else {
                            $task->status_code = TaskStatus::IN_PROGRESS_OVERDUE->value;
                        }
                    }
                    $task->results =  "Đang thực hiện";
                    $task->save();
                }
                DB::commit();

                 if($task->type == 'target') return redirect()->route('documents.report.target')->with('success', 'Cập nhật báo cáo thành công!');
                 else return redirect()->route('documents.report')->with('success', 'Cập nhật báo cáo thành công!');
            }
         
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating task/target: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    public function assignOrganizations(Request $request)
    {
        DB::beginTransaction();
        $type = '';
        try {
            $organizations = $request->input('organizations');
            // \Log::error('organizations giao việc: ' . $organizations);
            $userId = Auth::id();
            $processedOrganizations = []; // Tạo tập hợp để lưu mã organization đã xử lý

            if (!empty($organizations)) {
                $first = true; // Đánh dấu tổ chức đầu tiên
                foreach ($organizations as $data) {
                    $organizationCode = $data['code'];
                    $organizationName = $data['name'];
                    $taskId = $data['task_id'];

                    // Kiểm tra nếu mã organization đã tồn tại trong tập hợp, bỏ qua vòng lặp
                    if (in_array($organizationCode, $processedOrganizations)) {
                        continue;
                    }

                    \Log::error('organizations giao việc: ' . $organizationName);
                    \Log::error('biến check giao việc: ' . $first);

                    $organization = Organization::where('code', $organizationCode)->where('name', $organizationName)->first();
                    if ($organization) {
                        $taskTarget = TaskTarget::where('isDelete', 0)->find($taskId);
                        $typeRecord = $taskTarget->type === 'target' ? "Chỉ tiêu" : "Nhiệm vụ";
                        if ($taskTarget->organization_id == null) $first = true;
                        $type = $taskTarget->type;
                        $hasOrganization = TaskTarget::where('organization_id', $organization->id)->where('isDelete', 0)->where('code', $taskTarget->code)->first();
                        if (!$hasOrganization) {

                            if ($first) {
                                $taskTarget->organization_id = $organization->id;
                                $taskTarget->status = "assign";
                                $taskTarget->results = "Đang thực hiện";
                                $taskTarget->save();
                                $first = false;

                                \Log::error('Giao việc cơ quan đầu tiên ' . $taskTarget->status);
                            } else {
                                $newTaskTarget = $taskTarget->replicate();
                                $newTaskTarget->status = "assign";
                                $newTaskTarget->organization_id = $organization->id;
                                $newTaskTarget->save();
                                \Log::error('Giao việc cơ quan thứ: ' . $newTaskTarget->status);
                            }
                        }
                        // Thêm mã organization vào tập hợp đã xử lý
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
   /// reportTargetView
   public function reportTargetView(Request $request, $text = null)
   {
       $userId = Auth::id();
       $user = User::find($userId);
       $query = Document::query();

       $taskTargetQuery = TaskTarget::query();
       if ($request->filled('document_name')) {
           $query->where('document_name', 'like', '%' . $request->document_name . '%')->where('isDelete', 0);
       }

       if ($request->filled('organization_id')) {
           $query->where('issuing_department', $request->organization_id)->where('isDelete', 0);
       }

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
       if ($executionTimeFrom) {
           $query->whereDate('release_date', '>=', $executionTimeFrom);
       }

       if ($executionTimeTo) {
           $query->whereDate('release_date', '<=', $executionTimeTo);
       }
       if ($text) {
           $query->where('document_name', 'like', '%' . $text . '%');
       }
       if ($user->role == 'staff' || $user->role == 'sub_admin') {
           $documents = Document::whereHas('taskTarget', function ($query) use ($user) {
               $query->where('organization_id', $user->organization_id)->where('isDelete', 0);
           })->with('issuingDepartment')->orderBy('created_at', 'desc')->get();

           if ($documents && method_exists($documents, 'pluck')) {
               $taskDocuments = TaskTarget::whereIn('document_id', $documents->pluck('id'))
                   ->where('organization_id', $user->organization->id)
                   ->where('isDelete', 0)
                   ->where('type', 'target')
                   ->paginate(10);
           } else {
               $taskDocuments = collect();
           }
       } else if ($user->role == 'admin' || $user->role == 'supper_admin') {
           $documents = $query->with('issuingDepartment')->where('isDelete', 0)->orderBy('created_at', 'desc')->get();

           $taskDocuments = TaskTarget::whereIn('document_id', $documents->pluck('id'))->where('isDelete', 0)->where('type', 'target')->paginate(10);
       }

       $organizations = Organization::where('isDelete', 0)->get();
       $organizationsType = OrganizationType::where('isDelete', 0)->get();

       // dd($taskDocuments);
       return view('documents.reportTaskget', compact('documents', 'organizations', 'taskDocuments', 'organizationsType'));
   }
    /**
     * Display a listing of the resource.
     */

    public function reportView(Request $request, $text = null)
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $query = Document::query();

        $taskTargetQuery = TaskTarget::query();
        if ($request->filled('document_name')) {
            $query->where('document_name', 'like', '%' . $request->document_name . '%')->where('isDelete', 0);
        }

        if ($request->filled('organization_id')) {
            $query->where('issuing_department', $request->organization_id)->where('isDelete', 0);
        }

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
        if ($executionTimeFrom) {
            $query->whereDate('release_date', '>=', $executionTimeFrom);
        }

        if ($executionTimeTo) {
            $query->whereDate('release_date', '<=', $executionTimeTo);
        }
        if ($text) {
            $query->where('document_name', 'like', '%' . $text . '%');
        }
        if ($user->role == 'staff' || $user->role == 'sub_admin') {
            $documents = Document::whereHas('taskTarget', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id)->where('isDelete', 0);
            })->with('issuingDepartment')->orderBy('created_at', 'desc')->get();

            if ($documents && method_exists($documents, 'pluck')) {
                $taskDocuments = TaskTarget::whereIn('document_id', $documents->pluck('id'))
                    ->where('organization_id', $user->organization->id)
                    ->where('isDelete', 0)->where('type', 'task')->paginate(10);
            } else {
                $taskDocuments = collect();
            }
        } else if ($user->role == 'admin' || $user->role == 'supper_admin') {
            $documents = $query->with('issuingDepartment')->where('isDelete', 0)->orderBy('created_at', 'desc')->get();

            $taskDocuments = TaskTarget::whereIn('document_id', $documents->pluck('id'))->where('isDelete', 0)->where('type', 'task')->paginate(10);
        }

        $organizations = Organization::where('isDelete', 0)->get();
        $organizationsType = OrganizationType::where('isDelete', 0)->get();

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
        $organizations = Organization::where('isDelete', 0)->get();
        $organizationsType = OrganizationType::where('isDelete', 0)->get();

        return view('documents.index', compact('documents', 'organizations', 'organizationsType'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizations = Organization::where('isDelete', 0)->get();
        $documentCategory = DocumentCategory::where('isDelete', 0)->get();
        $organizationsType = OrganizationType::where('isDelete', 0)->get();

        // Truyền dữ liệu tổ chức vào view
        return view('documents.create', compact('organizations', 'documentCategory', 'organizationsType'));
    }

    public function checkDocumentCode($documentCode)
    {
        // Kiểm tra xem mã công việc có tồn tại trong cơ sở dữ liệu không
        $exists = Document::where('document_code', $documentCode)->where('isDelete', 0)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function getHistory($code)
    {

        $taskTargetIds = TaskTarget::where('code', $code)->where('isDelete', 0)
            ->pluck('id'); // Lấy danh sách các ID dưới dạng mảng

        // Kiểm tra nếu không có ID nào được tìm thấy
        if ($taskTargetIds->isEmpty()) {
            return collect(); // Trả về một Collection rỗng
        }

        // Lấy danh sách các bản ghi từ bảng HistoryChangeDocument dựa trên danh sách ID
        $lstHistory = HistoryChangeDocument::whereIn('mapping_id', $taskTargetIds)
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

        $organizations = Organization::where('isDelete', 0)->get();
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
            $taskTarget = TaskTarget::where('id', $id)->where('isDelete', 0)->first();
            $document = Document::findOrFail($taskTarget->document_id);
            $userId = Auth::id();
            $user = User::find($userId);

            if ($user->role == 'admin' || $user->role == 'supper_admin') {
                $taskDocuments = $document->taskTarget->where('isDelete', 0);
            } else {
                $taskDocuments = $document->taskTarget->filter(function ($task) use ($user) {
                    return $task->organization_id == $user->organization_id;
                })->where('isDelete', 0);
            }
            $groupTarget =  IndicatorGroup::where('isDelete', 0)->get();
            $organizations = Organization::where('isDelete', 0)->get();
            $workResultTypes = MasterWorkResultTypeService::index();
            $lstResult = $this->getFullDataTaskResult($taskTarget->id, $taskTarget->cycle_type, $taskTarget->getCurrentCycle());
            $units = Unit::all();
            return view('documents.reportUpdateTaskget', compact('units', 'document', 'taskDocuments', 'organizations', 'taskTarget', 'groupTarget', 'workResultTypes', 'lstResult'));
        } catch (\Exception $e) {
            \Log::error('Error reportViewUpdate: ' . $e->getMessage());
        }
    }

    public function reportViewUpdate(string $id)
    {
        try {
            $taskTarget = TaskTarget::where('id', $id)->where('isDelete', 0)->first();
            $document = Document::findOrFail($taskTarget->document_id);
            $userId = Auth::id();
            $user = User::find($userId);

            if ($user->role == 'admin' || $user->role == 'supper_admin') {
                $taskDocuments = $document->taskTarget->where('isDelete', 0);
            } else {
                $taskDocuments = $document->taskTarget->filter(function ($task) use ($user) {
                    return $task->organization_id == $user->organization_id;
                })->where('isDelete', 0);
            }
            $groupTask =  TaskGroup::where('isDelete', 0)->get();
            $organizations = Organization::where('isDelete', 0)->get();
            $workResultTypes = MasterWorkResultTypeService::index();
            $lstResult = $this->getFullDataTaskResult($taskTarget->id, $taskTarget->cycle_type, $taskTarget->getCurrentCycle());

            return view('documents.reportUpdate', compact('document', 'taskDocuments', 'organizations', 'taskTarget', 'groupTask', 'workResultTypes', 'lstResult'));
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
            $taskTarget = TaskTarget::where('id', $id)->where('isDelete', 0)->first();
            $document = Document::findOrFail($taskTarget->document_id);
            $userId = Auth::id();
            $user = User::find($userId);

            if ($user->role == 'admin' || $user->role == 'supper_admin') {
                $taskDocuments = $document->taskTarget->where('isDelete', 0);
            } else {
                $taskDocuments = $document->taskTarget->filter(function ($task) use ($user) {
                    return $task->organization_id == $user->organization_id;
                })->where('isDelete', 0);
            }
            $groupTask =  TaskGroup::where('isDelete', 0)->get();
            $organizations = Organization::where('isDelete', 0)->get();
            $workResultTypes = MasterWorkResultTypeService::index();
            $lstResult = $this->getFullDataTaskResult($taskTarget->id, $taskTarget->cycle_type, $taskTarget->getCurrentCycle());

            return view('documents.viewDetailsReport', compact('document', 'taskDocuments', 'organizations', 'taskTarget', 'groupTask', 'workResultTypes', 'lstResult'));
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

        $organizations = Organization::where('isDelete', 0)->get();
        $documentCategory = DocumentCategory::where('isDelete', 0)->get();
        $organizationsType = OrganizationType::where('isDelete', 0)->get();

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
