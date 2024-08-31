<?php

namespace App\Http\Controllers;
use App\Models\TaskTarget;
use Illuminate\Http\Request;
use Carbon\Carbon;
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
use App\Models\Category;
use App\Models\TaskApprovalHistory;
use  App\Helpers\TimeHelper;
use App\Models\HistoryChangeDocument;
use App\Models\OrganizationType;
use Exception;
use App\Models\TaskGroup;
use App\Models\IndicatorGroup;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
class TaskTargetController extends Controller
{
    public function updateRemarks(Request $request)
    {
        // Bắt đầu transaction
        //dd($request);
        DB::beginTransaction();
        try {
            // Lấy dữ liệu từ yêu cầu
            $taskId = $request->input("taskId");
            $remarks = $request->input("remarks");
            $type = $request->input("type");
            $taskResultId = $request->input("taskResultId");
            
            // Tìm nhiệm vụ theo ID (giả sử bạn có model Task)
            // Nếu không có, bạn có thể thay thế bằng mã của bạn để lưu dữ liệu
            $task = TaskTarget::find($taskId);

            $taskResult = TaskResult::find($taskResultId);
            // $table->enum('status', ['approved', 'rejected
            $status = 'rejected';
            if($type == 'Approval') $status = 'approved';
            if ($task) {
                
                TaskApprovalHistory::create([
                    'task_target_id' => $taskId,
                    'approver_id' => Auth::id(),
                    'status' => $status,
                    'remarks' => $remarks,
                    'type'=> $taskResult->type,
                    'number_type' => $taskResult->number_type,
                    'task_result_id' => $taskResult->id,
                ]);
                if($status == 'rejected'){
                    $task->status = 'reject';
                    $task->is_completed = 0;
                    $task->results =  "Đang thực hiện";
          
                }else{
                    $task->status = "sub_admin_complete";
                    $task->results =  "Hoàn thành";
                }
                $task->save();
                DB::commit();
                // Trả về phản hồi thành công
                return response()->json(['success' => true, 'message' => 'Thành công.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Task not found.'], 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Appprovel: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while updating remarks.'], 500);
        }
    }
    public function assignOrganizations(Request $request, $taskTargetId)
    {
        // dd($request);
        $organizationsType = OrganizationType::where('isDelete', 0)->get();
        // $taskTargetId = $request->query('taskTargetId');
        $taskTarget = TaskTarget::find($taskTargetId);
        return view('tasks.assign', compact('taskTarget', 'organizationsType'));
    }
    public function destroyTaskTarget($code, $type)
    {
      //  dd($type);
        $check  = true;
        // Tìm nhiệm vụ theo mã code và type
        $taskTargets = TaskTarget::where('code', $code)->where('type', $type)->where('isDelete', 0)->get();
        try{
            foreach($taskTargets as $item){
                $item->delete();
            }
        } catch (\Exception $e) {
            $check = false;
        }

        if ($check) {
          
           return redirect()->route('tasks.byType', ['type' => $type])->with('success', 'Xóa thành công!');
        } else {
        return redirect()->route('tasks.byType', ['type' => $type])->with('error', 'Đã xảy ra lỗi!');
        }
    }
        
    public function editTaskTarget($code, $type)
    {
        $taskTarget = TaskTarget::where('code', $code)->firstOrFail();
        $tasksWithSameCode = TaskTarget::where('code', $code)->get();
        $organizationIds = $tasksWithSameCode->pluck('organization_id')->unique();
        $organizations = Organization::whereIn('id', $organizationIds)->paginate(10);;
        $organizationsType = OrganizationType::where('isDelete', 0)->get();;
        $documents = Document::where('isDelete', 0)->get();;
        $categories = Category::where('isDelete', 0)->get();;
        $typeTask = IndicatorGroup::where('isDelete', 0)->get();;
        if($type == 'task'){
           $typeTask =  TaskGroup::where('isDelete', 0)->get();;
        }
        return view('tasks.edit', compact('taskTarget', 'type', 'organizations', 'documents', 'categories', 'typeTask'));
    }
    
    public function deleteOrganization($code, $type, $id)
    {
        $check  = true;
        $taskTargetDelete = TaskTarget::where('code', $code)->where('type', $type)->where('organization_id', $id)->first();
      
        try{
            if($taskTargetDelete){
                $taskTargetDelete->delete();
            }
        } catch (\Exception $e) {
            $check = false;
        }
        $taskTarget = TaskTarget::where('code', $code)->firstOrFail();
        $tasksWithSameCode = TaskTarget::where('code', $code)->get();
        $organizationIds = $tasksWithSameCode->pluck('organization_id')->unique();
        $organizations = Organization::whereIn('id', $organizationIds)->paginate(10);;
        $organizationsType = OrganizationType::where('isDelete', 0)->get();;
        $documents = Document::where('isDelete', 0)->get();;
        $categories = Category::where('isDelete', 0)->get();;
        
        if ($check) {
            session()->flash('success', 'Xóa cơ quan, tổ chức thành công!');
        } else {
            session()->flash('error', 'Đã xảy ra lỗi!');
        }
        return view('tasks.edit', compact('taskTarget', 'type', 'organizations', 'documents', 'categories'));
    }
    
    public function updateTaskTarget(Request $request, $code, $type)
    {
        DB::beginTransaction();
        
        try {
            $type = $request->input('type');
            $code = $request->input('code');
            $documentId = $request->input('document_id');
            $cycleType = $request->input('cycle_type');
            $name = $request->input('name');
            $requestResults = $request->input('request_results');
            $endDate = $request->input('end_date');
            $categoryId = $request->input('category_id');
            $type_id = $request->input('type_id');
            // dd($request);
            $taskTargets = TaskTarget::where('code', $code)->where('type', $type)->get();
            $document = Document::findOrFail($request->input("document_id"));
            foreach($taskTargets as $item){
                    
                $item->type =  $type;
                $item->code =  $code;
                $item->document_id =  $documentId;
                $item->cycle_type =  $cycleType;
                $item->request_results =  $requestResults;
                $item->name =  $name;
                $item->end_date =  $endDate;
                $item->category_id =  $categoryId;
                $item->start_date = $document->release_date;
                $item->type_id = $type_id;
                $item->save();
                DB::commit();
            }
            
            session()->flash('success', 'Cập nhật thành công!');
            return $this->indexView($request, $type);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating task/target: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }
    public function showDetails($code, $type)
    {
        $taskTarget = TaskTarget::where('code', $code)->firstOrFail();
        $tasksWithSameCode = TaskTarget::where('code', $code)->get();
        $organizationIds = $tasksWithSameCode->pluck('organization_id')->unique();


        $organizations = Organization::whereIn('id', $organizationIds)->paginate(10);
        $taskTargetIds = $tasksWithSameCode->pluck('id');
        $latestTaskResults = TaskResult::whereIn('id_task_criteria', $taskTargetIds)
            ->select('id_task_criteria', 'created_at', 'result', 'number_type', 'type') // Chọn trường cần thiết
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('id_task_criteria')
            ->map(function ($results) {
                return $results->first();
        });

        $mappedResults = $tasksWithSameCode->map(function ($task) use ($latestTaskResults, $organizations) {
            return [
                'task' => $task,
                'latest_result' => $latestTaskResults->get($task->id),
                'organization' => $organizations->firstWhere('id', $task->organization_id) // Sử dụng Collection
            ];
        });
        // Chuyển đổi $mappedResults thành Collection
        $mappedResultsCollection = collect($mappedResults);

        // Phân trang kết quả
        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 10; // Số kết quả mỗi trang
        $currentPageResults = $mappedResultsCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedResults = new LengthAwarePaginator(
            $currentPageResults,
            $mappedResultsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
        // dd($mappedResults);

        return view('tasks.show', compact('taskTarget', 'type', 'organizations', 'paginatedResults'));
    }
    public function indexView(Request $request, $type)
    {
        $organizations = Organization::where('isDelete', 0)->get();
        $organizationsType = OrganizationType::where('isDelete', 0)->get();
        $documents = Document::where('isDelete', 0)->get();
        $categories = Category::where('isDelete', 0)->get();

        $taskDocumentsQuery = TaskDocument::query();
        
       
        if($type == 'task'){
            $taskTargets = TaskTarget::where('type', 'task')->select('name', 'code', 'document_id', 'cycle_type',
            'category_id',
            'request_results',
            'start_date',
            'end_date', 'type')
            ->where('isDelete', 0)
            ->distinct('code')
            ->orderBy('id', 'desc');
        }else{
            $taskTargets = TaskTarget::where('type', 'target')->select('name', 'code', 'document_id', 'cycle_type',
            'category_id',
            'request_results',
            'start_date',
            'end_date', 'type')
            ->where('isDelete', 0)
            ->distinct('code')
            ->orderBy('id', 'desc');
        }
    
        if ($request->filled('document_id')) {
            // Chắc chắn rằng $documents là mảng phẳng trước khi sử dụng
            $taskTargets = $taskTargets->where('document_id', $request->document_id);
        }
        if ($request->filled('organization_id')) {
            $taskTargets->where('organization_id', $request->organization_id);
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
            $taskTargets->whereDate('end_date', '>=', $executionTimeFrom);
        }

        if ($executionTimeTo) {
            $taskTargets->whereDate('end_date', '<=', $executionTimeTo);
        }
    
        $taskTargets = $taskTargets->orderBy('created_at', 'desc')->paginate(10);
        return view('tasks.index', compact('taskTargets', 'organizations', 'documents', 'categories', 'organizationsType', 'type'));
    }

    
    

    public function index($type)
    {
        $organizations = Organization::where('isDelete', 0)->get();
        $documents = Document::where('isDelete', 0)->get();
        $categories = Category::where('isDelete', 0)->get();

        if($type == 'task'){
            
            $taskTargets = TaskTarget::where('type', 'task')->select('name', 'code', 'document_id', 'cycle_type',
            'category_id',
            'request_results',
            'start_date',
            'end_date', 'type') // Chọn các trường cần thiết
            ->where('isDelete', 0)
            ->distinct('code') // Đảm bảo mỗi nhiệm vụ chỉ xuất hiện một lần
            ->orderBy('id', 'desc') // Sắp xếp theo ID hoặc bất kỳ tiêu chí nào khác
            ->paginate(10); // Phân trang kết quả
        
        }else{
            $taskTargets = TaskTarget::where('type', 'target')->select('name', 'code', 'document_id', 'cycle_type',
            'category_id',
            'request_results',
            'start_date',
            'end_date', 'type') // Chọn các trường cần thiết
            ->where('isDelete', 0)
            ->distinct('code') // Đảm bảo mỗi nhiệm vụ chỉ xuất hiện một lần
            ->orderBy('id', 'desc') // Sắp xếp theo ID hoặc bất kỳ tiêu chí nào khác
            ->paginate(10); // Phân trang kết quả
        
        }
        return view('tasks.index', compact('taskTargets', 'organizations', 'documents', 'categories', 'type'));
    }

    
    
    public function createView($type)
    {
        $organizations = Organization::where('isDelete', 0)->get();
        $documents = Document::where('isDelete', 0)->get();
        
        $categories = Category::where('isDelete', 0)->get();

        //     use App\Models\TaskGroup;
        // use App\Models\IndicatorGroup;
        $typeTask = IndicatorGroup::where('isDelete', 0)->get();
        if($type == 'task'){
           $typeTask =  TaskGroup::where('isDelete', 0)->get();
        }
        return view('tasks.create', compact('organizations', 'documents', 'categories', 'type', 'typeTask'));
    }

    public function create()
    {
        $organizations = Organization::where('isDelete', 0)->get();
        $documents = Document::where('isDelete', 0)->get();
        
        $categories = Category::where('isDelete', 0)->get();
        return view('tasks.create', compact('organizations', 'documents', 'categories'));
    }

    public function store(Request $request)
    {
        // Bắt đầu transaction
        DB::beginTransaction();
        
        try {
            $typeRecord = $request->input("type") === 'target' ? "Chỉ tiêu" : "Nhiệm vụ";

            // Xác thực dữ liệu từ request
            $request->validate([
                'document_id' => 'required',
                'code' => 'required|unique:task_target,code',
                'name' => 'required|string|max:1000',
                'cycle_type' => 'required|string|max:50',
                'category_id' => 'nullable|integer|exists:categories,CategoryID',
                'end_date' => 'nullable|date',
                'type' => 'required|in:task,target',
                'type_id' => 'required',
            ], [
                'document_id.required' => 'Văn bản là bắt buộc.',
                'code.required' => 'Mã là bắt buộc.',
                'code.unique' => 'Mã này đã tồn tại trong hệ thống.',
                'name.required' => 'Tên là bắt buộc.',
                'name.string' => 'Tên phải là một chuỗi ký tự.',
                'name.max' => 'Tên không được vượt quá 1000 ký tự.',
                'cycle_type.required' => 'Chu kỳ báo cáo là bắt buộc.',
                'cycle_type.string' => 'Chu kỳ báo cáo phải là một chuỗi ký tự.',
                'cycle_type.max' => 'Chu kỳ báo cáo không được vượt quá 50 ký tự.',
                'category_id.integer' => 'Phân loại phải là một số nguyên.',
                'end_date.date' => 'Ngày kết thúc không hợp lệ.',
                'type.required' => 'Loại mục tiêu là bắt buộc.',
                'type_id.required' => 'Loại mục tiêu là bắt buộc.',
                'type.in' => 'Loại mục tiêu phải là "task" hoặc "target".',
            ]);

            // Lấy tất cả các giá trị từ request
            $data = $request->only([
                'type_id',
                'document_id',
                'code',
                'name',
                'cycle_type',
                'category_id',
                'end_date',
                'type',
            ]);
            $user = Auth::user();
            $document = Document::findOrFail($request->input("document_id"));
            $valueArea = $request->input("request_results_area");
            $valueNumber = $request->input("request_results_number");
            $type = $request->input("type");
            if($type == 'target') $result = $valueNumber;
            else if($type == 'task') $result = $valueArea;
            $organizationId = $user->organization_id;
            
            $data['creator'] = Auth::id();
            $data['status'] = 'new';
            $data['organization_id'] = $organizationId;
            $data['request_results'] = $result;
            $data['start_date'] = $document->release_date;
            // Tạo bản ghi mới
            // dd($data);
            $taskTarget = TaskTarget::create($data);
            session(['taskTargetAssign' => $taskTarget]);
            
            DB::commit();
            return redirect()->route('tasks.assign-organizations', [
                'taskTargetId' => $taskTarget->id
            ])->with('success', 'Tạo '.$typeRecord.' thành công! Hãy giao việc cho cơ quan/tổ chức');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating task/target: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    public function show($code)
    {
        $taskTarget = TaskTarget::where('code', $code)->first();
        $organizationIds = TaskTarget::where('code', $code)
        ->pluck('organization_id'); // Lấy danh sách các organization_id dưới dạng mảng
        $organizations = Organization::whereIn('id', $organizationIds)->get();
        return view('tasks.show', compact('taskTarget', 'organizations'));
    }


    public function edit($code)
    {

        return view('tasks.edit', compact());
    }

    public function update(Request $request, $code)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:1000',
            'cycle_type' => 'required|string|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:new,assign,complete,reject',
            'type' => 'required|in:task,target',
        ]);
        $taskTarget = TaskTarget::where('code', $code)->get();
        foreach($taskTarget as $item){
            $item->update($request->all());
        }
        

        return redirect()->route('tasks.index', ['type' => $request->input('cycle_type')])->with('success', 'Task Target updated successfully.');
    }

    public function destroy($code)
    {

        $taskTargets = TaskTarget::where('code', $code)->get();
        $type = $taskTargets->first()->cycle_type;

        foreach ($taskTargets as $taskTarget) {

            $taskReults = TaskResult::where('id_task_criteria', $taskTarget->id)->get();
            foreach ($taskReults as $taskReult) {
                $taskReult->isDelete = 1;
                $taskReult->save();
            }
            $taskTarget->isDelete = 1;
            $taskTarget->save();
        }
       

        return redirect()->route('tasks.index', ['type' => $type])->with('success', 'Task Target deleted successfully.');
    }
}
