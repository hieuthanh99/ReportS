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
use App\Models\Criteria;
use App\Models\Organization;
use App\Models\CriteriasTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\OrganizationTask;
use App\Models\TaskResult;
use App\Models\HistoryChangeDocument;
use App\Models\DocumentCategory;
use App\Helpers\TimeHelper;

class DocumentController extends Controller
{
    public function updateTaskCycle(Request $request, $documentId)
    {
       // dd($request);
        DB::beginTransaction();
        try {
            $cycleType = $request->input('cycle_type', []);
            $numberCycle = $request->input('number_cycle', []);
            $documentId = $request->input('document_id', []);
            $taskTargetId = $request->input('task_target_id', []);
            $cycleResult = $request->input('cycle_result', []);
            $taskStatus = $request->input('task_status', []);
            \Log::error('cycleType: ' . json_encode($cycleType));
            \Log::error('numberCycle: ' . json_encode($numberCycle));
            \Log::error('documentId: ' . json_encode($documentId));
            \Log::error('taskTargetId: ' . json_encode($taskTargetId));
            \Log::error('cycleResult: ' . json_encode($cycleResult));

            \Log::error('taskStatus: ' . json_encode($taskStatus));
           
            if (!empty($documentId) && is_array($documentId)) {
                $firstElement = reset($documentId);
                $document = Document::findOrFail($firstElement);
                if ($document) {
                    if (!empty($taskTargetId) && is_array($taskTargetId)) {
                        \Log::error('INSITE SAVE');
                        // Cập nhật các tác vụ
                       foreach ($taskTargetId as $index => $taskTarget) {
                           $task = TaskTarget::find($taskTarget);
                        //    dd($task);
                           if ($task) {
                                $checkbox = isset($taskStatus[$task->id]) ? $taskStatus[$task->id] : '';
                           
                                $task->results =  $checkbox == 1?"Hoàn Thành": $task->results;
                                $task->description = $task->getStatus();
                                $task->is_completed = $checkbox;
                                $task->status = $checkbox == 1?"complete": "assign";
                                $task->save();
                                $record = TaskResult::where('id_task_criteria' , $task->id)->where("document_id", $document->id)->where("type_save", $task->getType())->first();
                                if($record){
                                    $record->result =  isset($cycleResult[$task->id]) ? $cycleResult[$task->id] : '';
                                    $record->description =  'ok';
                                    $record->number_type =  isset($numberCycle[$task->id]) ? $numberCycle[$task->id] : '';
                                    $record->type =  isset($cycleType[$task->id]) ? $cycleType[$task->id] : '';
                                    $record->save();
                                }else{
                                    $record = TaskResult::create([
                                        'id_task_criteria' => $task->id,
                                        'document_id' => $document->id,
                                        'result' => isset($cycleResult[$task->id]) ? $cycleResult[$task->id] : '',
                                        'description' => 'ok',
                                        'number_type' => isset($numberCycle[$task->id]) ? $numberCycle[$task->id] : '',
                                        'type' => isset($cycleType[$task->id]) ? $cycleType[$task->id] : '',
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
                                    'update_user'=> Auth::id()
                                ]);
                            }
                        
                       }
                       DB::commit();
                       return redirect()->route('documents.report')->with('success', 'Cập nhật báo cáo thành công!');
                    }
                }
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
                        $taskTarget = TaskTarget::find($taskId);
                        $type = $taskTarget->type;
                        if ($first) {
                            $taskTarget->organization_id = $organization->id;
                            $taskTarget->status = "assign";
                            $taskTarget->results = "Đang thực hiện";
                            $taskTarget->save();
                            $first = false;
                            $typeRecord = $taskTarget->type === 'target' ? "Chỉ tiêu" : "Nhiệm vụ";
                            \Log::error('biến check giao việc: ' . $taskTarget->name);
                        } else {
                            $newTaskTarget = $taskTarget->replicate();
                            $newTaskTarget->organization_id = $organization->id;
                            $newTaskTarget->save();
                        }
    
                        // Thêm mã organization vào tập hợp đã xử lý
                        $processedOrganizations[] = $organizationCode;
                    }
                }
            }
    
            DB::commit();
            session(['success' =>  $typeRecord.' '. 'đã giao cho các tổ chức']);
            return response()->json([
                'success' => true,
                'type'=> $type,
                'message' => $typeRecord.' '. 'đã giao cho các tổ chức'
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

    public function reportView(Request $request)
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $query = Document::query();

        $taskTargetQuery = TaskTarget::query();
        if ($request->filled('document_name')) {
            $query->where('document_name', 'like', '%' . $request->document_name . '%');
        }
    
        if ($request->filled('organization_id')) {
            $query->where('issuing_department', $request->organization_id);
        }
    
        if ($request->filled('execution_time')) {
            $query->whereDate('release_date', $request->execution_time);
        }
        if($user->role=='staff' || $user->role=='sub_admin'){
            $documents = Document::whereHas('taskTarget', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })->with('issuingDepartment')->orderBy('created_at', 'desc')->paginate(10);

            $taskDocuments = TaskTarget::whereIn('document_id', $documents->pluck('id'))
            ->where('organization_id', $user->organization->id)
            ->get();
        }
       
        else if($user->role=='admin' || $user->role=='supper_admin'){
            $documents = $query->with('issuingDepartment')->orderBy('created_at', 'desc')->paginate(10);

            $taskDocuments = TaskTarget::whereIn('document_id', $documents->pluck('id'))->get();

        }
       
        $organizations = Organization::all();
        return view('documents.report', compact('documents', 'organizations', 'taskDocuments'));
    }
    public function index(Request $request)
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $query = Document::query();
        $taskDocumentsQuery = TaskDocument::query();
        
        if ($request->filled('document_code')) {
            $query->where('document_code', 'like', '%' . $request->document_code . '%');
        }
    
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
        $documentCategory = DocumentCategory::all();
        // Truyền dữ liệu tổ chức vào view
        return view('documents.create', compact('organizations', 'documentCategory'));
    }

    public function checkDocumentCode($documentCode)
    {
        // Kiểm tra xem mã công việc có tồn tại trong cơ sở dữ liệu không
        $exists = Document::where('document_code',$documentCode)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function getHistory($code)
    {
        
        $taskTargetIds = TaskTarget::where('code', $code)
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
                'document_code' => 'required|unique:documents,document_code',
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

            // Lưu tài liệu
            $document = Document::create([
                'document_code' => $request->input('document_code'),
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
    
            return redirect()->route('documents.index')->with('success', 'Danh mục tạo thành công!');
    
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
                                                        ->get();
        }
    
    
        $weekTask = $taskDocuments->where('reporting_cycle' , 1);
        $monthTask = $taskDocuments->where('reporting_cycle' , 2);
        $quarterTask = $taskDocuments->where('reporting_cycle' , 3);
        $yearTask = $taskDocuments->where('reporting_cycle' , 4);
        
        $timeParamsWeek = TimeHelper::getTimeParameters(1);
        $timeParamsMonth = TimeHelper::getTimeParameters(2);
        $timeParamsQuarter = TimeHelper::getTimeParameters(3);
        $timeParamsYear = TimeHelper::getTimeParameters(4);

        $organizations = Organization::all();
        return view('documents.show', compact('document', 'taskDocuments', 'criterias', 'organizations', 'weekTask', 'monthTask', 'quarterTask', 'yearTask'
        , 'timeParamsWeek', 'timeParamsMonth', 'timeParamsQuarter', 'timeParamsYear'));
    }
    
    
    public function reportViewUpdate(string $id)
    {
        
        $document = Document::findOrFail($id);
        $userId = Auth::id();
        $user = User::find($userId);
        
        if ($user->role=='admin' || $user->role=='supper_staff'){
            $taskDocuments = $document->taskTarget;
        } else {
            $taskDocuments = $document->taskTarget->filter(function ($task) use ($user) {
                return $task->organization_id === $user->organization_id;
            });
        }
        $weekTask = $taskDocuments->where('cycle_type', 1)->values();
        $monthTask = $taskDocuments->where('cycle_type', 2)->values();
        $quarterTask = $taskDocuments->where('cycle_type', 3)->values();
        $yearTask = $taskDocuments->where('cycle_type', 4)->values();
        $timeParamsWeek = TimeHelper::getTimeParameters(1);
        $timeParamsMonth = TimeHelper::getTimeParameters(2);
        $timeParamsQuarter = TimeHelper::getTimeParameters(3);
        $timeParamsYear = TimeHelper::getTimeParameters(4);


        $hasCompletedWeekTask = $weekTask->contains('is_completed', true);
        $hasCompletedMonthTask = $monthTask->contains('is_completed', true);
        $hasCompletedQuarterTask = $quarterTask->contains('is_completed', true);
        $hasCompletedYearTask = $yearTask->contains('is_completed', true);

       // dd($weekTask);
        $organizations = Organization::all();
        return view('documents.reportUpdate', compact('document', 'taskDocuments', 'organizations', 'weekTask', 'monthTask', 'quarterTask', 'yearTask'
        , 'timeParamsWeek', 'timeParamsMonth', 'timeParamsQuarter', 'timeParamsYear', 'hasCompletedWeekTask', 'hasCompletedMonthTask', 'hasCompletedQuarterTask', 'hasCompletedYearTask'));
    }

    public function detailsReport(string $id)
    {
        
        $document = Document::findOrFail($id);
        $userId = Auth::id();
        $user = User::find($userId);
        
        if ($user->role=='admin' || $user->role=='supper_staff'){
            $taskDocuments = $document->taskTarget;
        } else {
            $taskDocuments = $document->taskTarget->filter(function ($task) use ($user) {
                return $task->organization_id === $user->organization_id;
            });
        }
        //dd( $taskDocuments);
        $weekTask = $taskDocuments->where('cycle_type', 1)->values();
        $monthTask = $taskDocuments->where('cycle_type', 2)->values();
        $quarterTask = $taskDocuments->where('cycle_type', 3)->values();
        $yearTask = $taskDocuments->where('cycle_type', 4)->values();
        $timeParamsWeek = TimeHelper::getTimeParameters(1);
        $timeParamsMonth = TimeHelper::getTimeParameters(2);
        $timeParamsQuarter = TimeHelper::getTimeParameters(3);
        $timeParamsYear = TimeHelper::getTimeParameters(4);


        $hasCompletedWeekTask = $weekTask->contains('is_completed', true);
        $hasCompletedMonthTask = $monthTask->contains('is_completed', true);
        $hasCompletedQuarterTask = $quarterTask->contains('is_completed', true);
        $hasCompletedYearTask = $yearTask->contains('is_completed', true);

       // dd($weekTask);
        $organizations = Organization::all();
        return view('documents.viewDetailsReport', compact('document', 'taskDocuments', 'organizations', 'weekTask', 'monthTask', 'quarterTask', 'yearTask'
        , 'timeParamsWeek', 'timeParamsMonth', 'timeParamsQuarter', 'timeParamsYear', 'hasCompletedWeekTask', 'hasCompletedMonthTask', 'hasCompletedQuarterTask', 'hasCompletedYearTask'));
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
        
        $timeParamsWeek = TimeHelper::getTimeParameters(1);
        $timeParamsMonth = TimeHelper::getTimeParameters(2);
        $timeParamsQuarter = TimeHelper::getTimeParameters(3);
        $timeParamsYear = TimeHelper::getTimeParameters(4);

        $organizations = Organization::all();
        $documentCategory = DocumentCategory::all();
        return view('documents.edit', compact('document', 'taskDocuments', 'criterias', 'organizations', 'weekTask', 'monthTask', 'quarterTask', 'yearTask'
        , 'timeParamsWeek', 'timeParamsMonth', 'timeParamsQuarter', 'timeParamsYear', 'documentCategory'));
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
                'required',
                    Rule::unique('documents', 'document_code')->ignore($request->input('document_id'))
                ],
                'document_name' => 'required', // Validation rule for textarea
                'issuing_department' => 'required',
                'release_date' => 'required|date',
            ], [
                'document_code.required' => 'Mã văn bản là bắt buộc.',
                'document_code.unique' => 'Mã văn bản đã tồn tại.',
                'document_name.required' => 'Tên văn bản là bắt buộc.',
                'issuing_department.required' => 'Đơn vị phát hành là bắt buộc.',
                'release_date.required' => 'Ngày phát hành là bắt buộc.',
                'release_date.date' => 'Ngày phát hành không hợp lệ.',
            ]);

    
            // Lấy dữ liệu từ request
            $documentId = $request->input('document_id');

            $documentName = $request->input('document_name');
            $issuingDepartment = $request->input('issuing_department');
            $releaseDate = $request->input('release_date');         
            // Kiểm tra nếu $documentIds không rỗng và lấy phần tử đầu tiên
            if (!empty($documentId)) {
                $document = Document::findOrFail($documentId);
                
                if ($document) {
                    if($document->creator == $userId){
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
                        $document->save();
                        DB::commit();
    
                        return redirect()->route('documents.index')->with('success', 'Cập nhật danh mục thành công!');
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
    function containsNull($array) {
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
        CriteriasTask::where('DocumentID', $id)->delete();
        TaskTarget::where('document_id', $id)->delete();
        TaskResult::where('document_id', $id)->delete();
        File::where('document_id', $id)->delete();

        // Tìm và xóa tài liệu trong bảng Document
        $document = Document::findOrFail($id);
        $document->delete();
    
        // Chuyển hướng về trang danh sách tài liệu
        return redirect()->route('documents.index')->with('success', 'Xóa thành công văn bản.');
    }
}
