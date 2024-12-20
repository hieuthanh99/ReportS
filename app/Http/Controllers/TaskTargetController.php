<?php

namespace App\Http\Controllers;

use App\Models\TaskTarget;
use App\Services\MasterWorkResultTypeService;
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
use App\Models\Unit;
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
use App\Enums\TaskTargetStatus;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TaskTargetController extends Controller
{
    public function updateRemarks(Request $request)
    {
        DB::beginTransaction();
        try {
            $taskId = $request->input("taskId");
            $remarks = $request->input("remarks");
            $type = $request->input("type");
            $taskResult = TaskResult::find($taskId);
            $history = HistoryChangeDocument::where('mapping_id', $taskId)->orderBy('created_at', 'desc')->first();
            $status = 'rejected';
            if ($type == 'Approval') $status = 'approved';
            if ($taskResult) {

                TaskApprovalHistory::create([
                    'task_target_id' => $taskResult->id_task_criteria,
                    'approver_id' => Auth::id(),
                    'status' => $status,
                    'remarks' => $remarks,
                    'type' => $taskResult->type,
                    'number_type' => $taskResult->number_type,
                    'task_result_id' => $taskResult->id,
                    'history_id' => $history->id,
                ]);
                if ($status == 'rejected') {
                    $taskResult->status = 'reject';
            
                } else {
                    $taskResult->status = "sub_admin_complete";
                }
                $taskResult->save();
                DB::commit();
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
        $organizationsType = OrganizationType::where('isDelete', 0)->get();
        $taskTarget = TaskTarget::find($taskTargetId);
        return view('tasks.assign', compact('taskTarget', 'organizationsType'));
    }
    public function destroyTaskTarget($id, $type)
    {
        $check = true;
        try {
            $taskTarget = TaskTarget::where('id', $id)->where('isDelete', 0)->firstOrFail();
            $taskResults = TaskResult::where('id_task_criteria', $id)->get();
            foreach ($taskResults as $item) {
                $item->delete();
            }
            $taskTarget->isDelete = 1;
            $taskTarget->save();
    
        } catch (\Exception $e) {
            $check = false;
            \Log::error('Error deleting task target: ' . $e->getMessage());
        }
        if ($check) {
            // return redirect()->route('tasks.byType', ['type' => $type])->with('success', 'Xóa thành công!');
            return redirect()->back()->with('success', 'Xóa thành công!');
        } else {
            // return redirect()->route('tasks.byType', ['type' => $type])->with('error', 'Đã xảy ra lỗi!');
            return redirect()->back()->with('error', 'Đã xảy ra lỗi!');
        }
    }

    public function getOrganizationsByTaskTargetId($idTaskTarget)
    {   
        $organizationIds = TaskResult::where('id_task_criteria', $idTaskTarget)->select('organization_id')->where('isDelete', 0)->get();

        $organizations = Organization::whereIn('id', $organizationIds)->where('isDelete', 0)->whereNotNull('organization_type_id')->orderBy('name', 'asc')->get();
        if ($organizations->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không có tổ chức nào được tìm thấy cho task này.'
            ], 404);
        }

        // Trả về dữ liệu JSON của các tổ chức
        return response()->json([
            'success' => true,
            'data' => $organizations
        ]);
    }

    public function editTaskTarget($id, $type)
    {
        $taskTarget = TaskTarget::where('id', $id)->firstOrFail();
        $taskResult = TaskResult::where('id_task_criteria', $id)->where('isDelete', 0)->paginate(10);
        $organizationsType = OrganizationType::where('isDelete', 0)->orderBy('type_name', 'asc')->get();;
        $documents = Document::where('isDelete', 0)->get();;
        $categories = Category::where('isDelete', 0)->get();;
        $typeTask = IndicatorGroup::where('isDelete', 0)->get();;
        $workResultTypes = MasterWorkResultTypeService::index();
        $keyConstants = MasterWorkResultTypeService::keyConstants();
        $units = Unit::all();
        if ($type == 'task') {
            $typeTask =  TaskGroup::where('isDelete', 0)->get();;
        }
        $isAssignOrganizations = true;
        $isAssign = $taskTarget->document->release_date;
        if (Carbon::parse($isAssign)->greaterThan(Carbon::today())) {
            $isAssignOrganizations = false;
        }
        return view('tasks.edit', compact('isAssignOrganizations', 'taskTarget', 'type', 'taskResult', 'documents', 'categories', 'typeTask', 'workResultTypes', 'keyConstants', 'units'));
    }

    public function deleteOrganization($idTaskCriteria, $type, $id)
    {
        try {
            $taskResult = TaskResult::where('id', $id)->first();
            if ($taskResult) {
                $taskResult->delete();
                session()->flash('success', 'Xóa cơ quan, tổ chức thành công!');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Đã xảy ra lỗi!');
        }
        return $this->editTaskTarget($idTaskCriteria, $type);
    }

    public function updateTaskTarget(Request $request, $code, $type)
    {
        DB::beginTransaction();

        try {
            $typeRecord = $request->input("type") === 'target' ? "Chỉ tiêu" : "Nhiệm vụ";


            if ($request->input("type") === 'target') {
                $request->validate([
                    'document_id' => 'required',
                    'code' => 'required',
                    'name' => 'required|string|max:1000',
                    'cycle_type' => 'required|string|max:50',
                    'category_id' => 'nullable|integer|exists:categories,CategoryID',
                    'end_date' => 'nullable|date',
                    'type' => 'required|in:task,target',
                    'type_id' => 'required',
                    'unit' => 'required',   // Đơn vị
                    // 'target_type' => 'required',
                    'target' => 'required',
                ], [
                    'document_id.required' => 'Văn bản là bắt buộc.',
                    'code.required' => 'Mã là bắt buộc.',
                    'code.unique' => 'Mã này đã tồn tại trong hệ thống.',
                    'name.required' => 'Tên là bắt buộc.',
                    'name.string' => 'Tên phải là một chuỗi ký tự.',
                    'name.max' => 'Tên không được vượt quá 1000 ký tự.',
                    // 'cycle_type.required' => 'Chu kỳ báo cáo là bắt buộc.',
                    'cycle_type.string' => 'Chu kỳ báo cáo phải là một chuỗi ký tự.',
                    'cycle_type.max' => 'Chu kỳ báo cáo không được vượt quá 50 ký tự.',
                    'category_id.integer' => 'Phân loại phải là một số nguyên.',
                    'end_date.date' => 'Ngày kết thúc không hợp lệ.',
                    'type.required' => 'Loại mục tiêu là bắt buộc.',
                    'type_id.required' => 'Loại mục tiêu là bắt buộc.',
                    'type.in' => 'Loại mục tiêu phải là "task" hoặc "target".',
                    'unit.required' => 'Đơn vị là bắt buộc',   // Đơn vị
                    // 'target_type.required' => 'Loại chỉ tiêu là bắt buộc',
                    'target.required' => 'Chỉ tiêu là bắt buộc',
                ]);
            } else {
                $request->validate([
                    'document_id' => 'required',
                    'code' => 'required',
                    'name' => 'required|string|max:1000',
                    'cycle_type' => 'required|string|max:50',
                    'category_id' => 'nullable|integer|exists:categories,CategoryID',
                    'end_date' => 'nullable|date',
                    'type' => 'required|in:task,target',
                    'type_id' => 'required',
                    'task_type' => 'required',   // Đơn vị
                ], [
                    'document_id.required' => 'Văn bản là bắt buộc.',
                    'code.required' => 'Mã là bắt buộc.',
                    'code.unique' => 'Mã này đã tồn tại trong hệ thống.',
                    'name.required' => 'Tên là bắt buộc.',
                    'name.string' => 'Tên phải là một chuỗi ký tự.',
                    'name.max' => 'Tên không được vượt quá 1000 ký tự.',
                    // 'cycle_type.required' => 'Chu kỳ báo cáo là bắt buộc.',
                    'cycle_type.string' => 'Chu kỳ báo cáo phải là một chuỗi ký tự.',
                    'cycle_type.max' => 'Chu kỳ báo cáo không được vượt quá 50 ký tự.',
                    'category_id.integer' => 'Phân loại phải là một số nguyên.',
                    'end_date.date' => 'Ngày kết thúc không hợp lệ.',
                    'type.required' => 'Loại mục tiêu là bắt buộc.',
                    'type_id.required' => 'Loại mục tiêu là bắt buộc.',
                    'type.in' => 'Loại mục tiêu phải là "task" hoặc "target".',
                    'task_type.required' => 'Loại nhiệm vụ là bắt buộc',   // Đơn vị


                ]);
            }
            if ($request->input("type") === 'target') {
                $unit = null;
                if ($request->unit == 0 && $request->custom_unit == null) {
                    return redirect()->back()->with('error', 'Vui lòng nhập đơn vị khác!');
                }
                if ($request->unit == 0 && $request->custom_unit != null) {
                    $unit = Unit::create(['name' => $request->custom_unit])->id;
                } else {
                    $unit = $request->unit;
                }
            }

            $type = $request->input('type');
            $code = $request->input('code');
            $documentId = $request->input('document_id');
            $cycleType = $request->input('cycle_type');
            $name = $request->input('name');
            $requestResults = $request->input('request_results');
            $endDate = $request->input('end_date');
            $categoryId = $request->input('category_id');
            $type_id = $request->input('type_id');
            $result_type = $request->input('result_type');
            // dd($request);
            $taskTargets = TaskTarget::where('code', $code)->where('type', $type)->get();
            $document = Document::findOrFail($request->input("document_id"));
            foreach ($taskTargets as $item) {

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
                $item->result_type = $result_type;

                //update
                if ($request->input("type") === 'target') {
                    $item->unit = $unit;
                    // $item->target_type = $request->target_type;
                    $item->target = $request->target;
                } else {
                    $item->task_type = $request->task_type;
                }

                $item->save();
                DB::commit();
            }

            session()->flash('success', 'Cập nhật thành công!');
            // dd($request);
            return $this->indexView($request, $type);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating task/target: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    public function showDetails($id, $type)
    {
        try{
            $taskTarget = TaskTarget::where('id', $id)->where('isDelete', 0)->firstOrFail();
            $documents = Document::where('isDelete', 0)->get();
            $taskResult = TaskResult::where('id_task_criteria', $id)->where('isDelete', 0)->paginate(10);
            $typeTask = IndicatorGroup::where('isDelete', 0)->get();
            $workResultTypes = MasterWorkResultTypeService::index();
            $keyConstants= MasterWorkResultTypeService::keyConstants();
            $units = Unit::all();

            if ($type == 'task') {
                $typeTask =  TaskGroup::where('isDelete', 0)->get();;
            }
            $organizationTypes = OrganizationType::withCount(['organizations' => function ($query) use ($taskTarget) {
                $query->whereHas('taskResults', function ($q) use ($taskTarget) {
                    $q->where('id_task_criteria', $taskTarget->id); 
                });
            }])->having('organizations_count', '>', 0) ->get();

            return view('tasks.show', compact('taskTarget', 'taskResult', 'typeTask', 'workResultTypes', 'keyConstants', 'type', 'documents', 'organizationTypes'));    
        }catch (\Exception $e) {
            \Log::error('Error creating task/target: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    public function indexViewApproved(Request $request, $type, $text = null)
    {
        $organizations = Organization::where('isDelete', 0)->whereHas('documents', function($query) {
            $query->where('isDelete', 0);
        })->whereNotNull('organization_type_id')->orderBy('name', 'asc')->get();
        $organizationsType = OrganizationType::where('isDelete', 0)->orderBy('type_name', 'asc')->get();
        $documents = Document::where('isDelete', 0)->get();
        $categories = Category::where('isDelete', 0)->get();
        $statuses = TaskTargetStatus::cases();

        if ($type == 'task') {
            $taskTargets = TaskTarget::where('type', 'task')->where('isDelete', 0)->orderBy('id', 'desc');
        } else {
            $taskTargets = TaskTarget::where('type', 'target')->where('isDelete', 0)->orderBy('id', 'desc');
        }
        if ($text) {
            $taskTargets->where('name', 'like', '%' . $text . '%');
        }
        if ($request->filled('document_code')) {
            $documentsSearch = Document::where('isDelete', 0)->where('document_code', 'like', '%' . $request->document_code . '%')->pluck('id');
            $taskTargets = $taskTargets->whereIn('document_id', $documentsSearch);
        }
        if ($request->filled('document_id')) {

            $taskTargets = $taskTargets->where('document_id', $request->document_id);
        }
        if ($request->filled('issuing_organization_id')) {
            $taskTargets->where('issuing_organization_id', $request->issuing_organization_id);
        }
        if ($request->filled('status_code')) {
            $taskTargets = $taskTargets->where('status_code', $request->status_code);
        }
        if ($request->filled('status')) {
            if($request->status === 'complete_on_time'){
                $taskTargets =  $taskTargets->where('status', 'complete')->where('end_date', '>', Carbon::now());
            }
            elseif($request->status === 'complete_late'){
                $taskTargets =  $taskTargets->where('status', 'complete')->where('end_date', '<', Carbon::now());
            }
            elseif($request->status === 'processing'){
                $taskTargets =  $taskTargets->where('status', 'processing')->where('end_date', '>', Carbon::now())->where('start_date', '<', Carbon::now());
            }
            elseif($request->status === 'overdue'){
                $taskTargets =  $taskTargets->where('status', 'processing')->whereDate('end_date', '<', Carbon::now());
            }
            elseif($request->status === 'upcoming_due'){
                $taskTargets =  $taskTargets->where('status', 'new');
            }
            // $taskTargets =  $taskTargets->where('status', $request->status);
        }
        if ($request->filled('organization_id')) {
            $taskResultSearch = TaskResult::where('isDelete', 0)->where('organization_id', $request->organization_id)->pluck('id_task_criteria');
            $taskTargets = $taskTargets->whereIn('id', $taskResultSearch);
        }
        $executionTimeFrom = $request->input('execution_time_from');
        $executionTimeTo = $request->input('execution_time_to');
        if ($executionTimeFrom && $executionTimeTo) {
            try {
                $executionTimeFrom = \Carbon\Carbon::createFromFormat('Y-m-d', $executionTimeFrom);
                $executionTimeTo = \Carbon\Carbon::createFromFormat('Y-m-d', $executionTimeTo);
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
        $typeTask = IndicatorGroup::where('isDelete', 0)->get();
        if ($type == 'task') {
            $typeTask =  TaskGroup::where('isDelete', 0)->get();
        }
        $workResultTypes = MasterWorkResultTypeService::index();
        $taskTargets = $taskTargets->whereHas('taskResultsRelation', function($query) {
            $query->whereIn('status', ['sub_admin_complete', 'admin_approves']);
        })->where('isDelete', 0)->orderBy('created_at', 'desc')->paginate(10)->appends($request->all());
        

        return view('documents.indexApprovedReport', compact('taskTargets', 'organizations', 'documents', 'categories', 'organizationsType', 'type', 'typeTask', 'workResultTypes', 'statuses'));
    }

    public function dataIndexView(Request $request, $type, $text = null){
        if ($type == 'task') {
            $taskTargets = TaskTarget::where('type', 'task')->where('isDelete', 0)->orderBy('id', 'desc');
        } else {
            $taskTargets = TaskTarget::where('type', 'target')->where('isDelete', 0)->orderBy('id', 'desc');
        }
        if ($text) {
            $taskTargets->where('name', 'like', '%' . $text . '%');
        }
        if ($request->filled('document_code')) {
            $documentsSearch = Document::where('isDelete', 0)->where('document_code', 'like', '%' . $request->document_code . '%')->pluck('id');
            $taskTargets = $taskTargets->whereIn('document_id', $documentsSearch);
        }
        // if ($request->filled('document_id')) {
        //     $taskTargets = $taskTargets->where('document_id', $request->document_id);
        // }
        if ($request->filled('organization_id')) {
            $taskTargets->where('issuing_organization_id', $request->organization_id);
        }
        if ($request->filled('status')) {
            if($request->status === 'complete_on_time'){
                $taskTargets =  $taskTargets->where('status', 'complete')->where('end_date', '>', Carbon::now());
            }
            elseif($request->status === 'complete_late'){
                $taskTargets =  $taskTargets->where('status', 'complete')->where('end_date', '<', Carbon::now());
            }
            elseif($request->status === 'processing'){
                $taskTargets =  $taskTargets->where('status', 'processing')->where('end_date', '>', Carbon::now())->where('start_date', '<', Carbon::now());
            }
            elseif($request->status === 'overdue'){
                $taskTargets =  $taskTargets->where('status', 'processing')->whereDate('end_date', '<', Carbon::now());
            }
            elseif($request->status === 'upcoming_due'){
                $taskTargets =  $taskTargets->where('status', 'new');
            }
            // $taskTargets =  $taskTargets->where('status', $request->status);
        }
        if ($request->filled('tasktype')) {
            $taskTargets =  $taskTargets->where('task_type', $request->tasktype);
        }
        if ($request->filled('typeid')) {
            $taskTargets =  $taskTargets->where('type_id', $request->typeid);
        }
        $executionTimeFrom = $request->input('completion_date');
        $executionTimeTo = $request->input('execution_time_to');
        if ($executionTimeFrom && $executionTimeTo) {
            try {
                $executionTimeFrom = \Carbon\Carbon::createFromFormat('Y-m-d', $executionTimeFrom);
                $executionTimeTo = \Carbon\Carbon::createFromFormat('Y-m-d', $executionTimeTo);
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
            // Thêm ngày đầu tiên của tháng để tạo thành một chuỗi ngày đầy đủ
            $startOfMonth = $executionTimeFrom . '-01';
            
            // Tính ngày cuối cùng của tháng
            $endOfMonth = date("Y-m-t", strtotime($startOfMonth)); // 'Y-m-t' trả về ngày cuối cùng của tháng

            // Sử dụng whereDate để lọc các bản ghi có end_date nằm trong tháng đó
            $taskTargets->whereDate('end_date', '>=', $startOfMonth)
                        ->whereDate('end_date', '<=', $endOfMonth);
        }

        if ($executionTimeTo) {
            $taskTargets->whereDate('end_date', '<=', $executionTimeTo);
        }
        return $taskTargets;
    }

    public function indexView(Request $request, $type, $text = null)
    {
        $organizations = Organization::where('isDelete', 0)->whereHas('documents', function($query) {
            $query->where('isDelete', 0);
        })->whereNotNull('organization_type_id')->orderBy('name', 'asc')->get();
        $organizationsType = OrganizationType::where('isDelete', 0)->orderBy('type_name', 'asc')->get();
        $documents = Document::where('isDelete', 0)->get();
        $categories = Category::where('isDelete', 0)->get();

        $taskTargets = $this->dataIndexView($request, $type, $text);

        $typeTask = IndicatorGroup::where('isDelete', 0)->get();
        if ($type == 'task') {
            $typeTask =  TaskGroup::where('isDelete', 0)->get();
        }
        $workResultTypes = MasterWorkResultTypeService::index();
        $taskTargets = $taskTargets->where('isDelete', 0)->orderBy('created_at', 'desc')->paginate(10)->appends($request->all());
        // dd($taskTargets);
        return view('tasks.index', compact('taskTargets', 'organizations', 'documents', 'categories', 'organizationsType', 'type', 'typeTask', 'workResultTypes'));
    }

    public function index($type)
    {
        $organizations = Organization::where('isDelete', 0)->whereNotNull('organization_type_id')->orderBy('name', 'asc')->get();
        $documents = Document::where('isDelete', 0)->get();
        $categories = Category::where('isDelete', 0)->get();

        if ($type == 'task') {

            $taskTargets = TaskTarget::where('type', 'task')->select(
                'id',
                'name',
                'code',
                'document_id',
                'cycle_type',
                'category_id',
                'request_results',
                'start_date',
                'end_date',
                'type'
            ) // Chọn các trường cần thiết
                ->where('isDelete', 0)
                ->distinct('code') // Đảm bảo mỗi nhiệm vụ chỉ xuất hiện một lần
                ->orderBy('id', 'desc') // Sắp xếp theo ID hoặc bất kỳ tiêu chí nào khác
                ->paginate(10); // Phân trang kết quả

        } else {
            $taskTargets = TaskTarget::where('type', 'target')->select(
                'id',
                'name',
                'code',
                'document_id',
                'cycle_type',
                'category_id',
                'request_results',
                'start_date',
                'end_date',
                'type'
            ) // Chọn các trường cần thiết
                ->where('isDelete', 0)
                ->distinct('code') // Đảm bảo mỗi nhiệm vụ chỉ xuất hiện một lần
                ->orderBy('id', 'desc') // Sắp xếp theo ID hoặc bất kỳ tiêu chí nào khác
                ->paginate(10); // Phân trang kết quả
        }

        return view('tasks.index', compact('taskTargets', 'organizations', 'documents', 'categories', 'type'));
    }

    public function createView($type)
    {
        $organizations = Organization::where('isDelete', 0)->whereNotNull('organization_type_id')->orderBy('name', 'asc')->get();
        $documents = Document::where('isDelete', 0)->get();

        $categories = Category::where('isDelete', 0)->get();
        $units = Unit::all();
        $workResultTypes = MasterWorkResultTypeService::index();
        $keyConstants = MasterWorkResultTypeService::keyConstants();

        //     use App\Models\TaskGroup;
        // use App\Models\IndicatorGroup;
        $typeTask = IndicatorGroup::where('isDelete', 0)->get();
        if ($type == 'task') {
            $typeTask =  TaskGroup::where('isDelete', 0)->get();
        }
        return view('tasks.create', compact('organizations', 'documents', 'categories', 'type', 'typeTask', 'workResultTypes', 'keyConstants', 'units'));
    }

    public function create()
    {
        $organizations = Organization::where('isDelete', 0)->whereNotNull('organization_type_id')->orderBy('name', 'asc')->get();
        $documents = Document::where('isDelete', 0)->get();

        $units = Unit::all();

        $categories = Category::where('isDelete', 0)->get();
        return view('tasks.create', compact('organizations', 'documents', 'categories', 'units'));
    }

    public function store(Request $request)
    {
        // Bắt đầu transaction
        DB::beginTransaction();
        //dd($request);
        try {
            $typeRecord = $request->input("type") === 'target' ? "chỉ tiêu" : "nhiệm vụ";


            if ($request->input("type") === 'target') {
                $request->validate([
                    'document_id' => 'required',
                    'code' => 'required',
                    'name' => 'required|string|max:1000',
                    'cycle_type' => 'required|string|max:50',
                    'category_id' => 'nullable|integer|exists:categories,CategoryID',
                    'end_date' => 'nullable|date',
                    'type' => 'required|in:task,target',
                    'type_id' => 'required',
                    'unit' => 'required',   // Đơn vị
                    // 'target_type' => 'required',
                    'target' => 'required',
                ], [
                    'document_id.required' => 'Văn bản là bắt buộc.',
                    'code.required' => 'Mã là bắt buộc.',
                    'code.unique' => 'Mã này đã tồn tại trong hệ thống.',
                    'name.required' => 'Tên là bắt buộc.',
                    'name.string' => 'Tên phải là một chuỗi ký tự.',
                    'name.max' => 'Tên không được vượt quá 1000 ký tự.',
                    // 'cycle_type.required' => 'Chu kỳ báo cáo là bắt buộc.',
                    'cycle_type.string' => 'Chu kỳ báo cáo phải là một chuỗi ký tự.',
                    'cycle_type.max' => 'Chu kỳ báo cáo không được vượt quá 50 ký tự.',
                    'category_id.integer' => 'Phân loại phải là một số nguyên.',
                    'end_date.date' => 'Ngày kết thúc không hợp lệ.',
                    'type.required' => 'Loại mục tiêu là bắt buộc.',
                    'type_id.required' => 'Loại mục tiêu là bắt buộc.',
                    'type.in' => 'Loại mục tiêu phải là "task" hoặc "target".',
                    'unit.required' => 'Đơn vị là bắt buộc',   // Đơn vị
                    // 'target_type.required' => 'Loại chỉ tiêu là bắt buộc',
                    'target.required' => 'Chỉ tiêu là bắt buộc',
                ]);
            } else {
                $request->validate([
                    'document_id' => 'required',
                    'code' => 'required',
                    'name' => 'required|string|max:1000',
                    'cycle_type' => 'required|string|max:50',
                    'category_id' => 'nullable|integer|exists:categories,CategoryID',
                    'end_date' => 'nullable|date',
                    'type' => 'required|in:task,target',
                    'type_id' => 'required',
                    'task_type' => 'required',
                    'request_results_task' => 'required'
                ], [
                    'document_id.required' => 'Văn bản là bắt buộc.',
                    'code.required' => 'Mã là bắt buộc.',
                    'code.unique' => 'Mã này đã tồn tại trong hệ thống.',
                    'name.required' => 'Tên là bắt buộc.',
                    'name.string' => 'Tên phải là một chuỗi ký tự.',
                    'name.max' => 'Tên không được vượt quá 1000 ký tự.',
                    // 'cycle_type.required' => 'Chu kỳ báo cáo là bắt buộc.',
                    'cycle_type.string' => 'Chu kỳ báo cáo phải là một chuỗi ký tự.',
                    'cycle_type.max' => 'Chu kỳ báo cáo không được vượt quá 50 ký tự.',
                    'category_id.integer' => 'Phân loại phải là một số nguyên.',
                    'end_date.date' => 'Ngày kết thúc không hợp lệ.',
                    'type.required' => 'Loại mục tiêu là bắt buộc.',
                    'type_id.required' => 'Loại mục tiêu là bắt buộc.',
                    'type.in' => 'Loại mục tiêu phải là "task" hoặc "target".',
                    'task_type.required' => 'Loại nhiệm vụ là bắt buộc',   // Đơn vị
                    'request_results_task.required' => 'Kết quả yêu cầu là bắt buộc',   // Đơn vị
                ]);
            }
            if ($request->input("type") === 'target') {
                $unit = null;
                if ($request->unit == 0 && $request->custom_unit == null) {
                    return redirect()->back()->with('error', 'Vui lòng nhập đơn vị khác!');
                }
                if ($request->unit == 0 && $request->custom_unit != null) {
                    $unit = Unit::create(['name' => $request->custom_unit])->id;
                } else {
                    $unit = $request->unit;
                }
            }
       
            $exitItem = TaskTarget::where('isDelete', 0)->where('code', $request->code)->first();
            if ($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
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
            $executionTimeTo = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input("end_date"));
            $executionTimeFrom = \Carbon\Carbon::createFromFormat('Y-m-d', $document->release_date);

            if ($executionTimeFrom->gt($executionTimeTo)) {
                return redirect()->back()->withErrors([
                    'error' => "Thời gian kết thúc phải lớn hơn thời gian kết thúc."
                ]);
            }
            $isAssign = $document->release_date;
            if (Carbon::parse($isAssign)->greaterThan(Carbon::today())) {
                return redirect()->back()->withErrors([
                    'error' => "Văn bản chưa phát hành. Bạn không thể tạo ". $typeRecord
                ]);
            }
            
            $result = $request->input("request_results");
            $type_id = $request->input('type_id');
            $result_type = $request->input('result_type');
            $organizationId = $user->organization_id;

            $data['creator'] = Auth::id();
            $data['status'] = "new";
            // $data['organization_id'] = $organizationId;
            $data['request_results'] = $result;
            $data['start_date'] = $document->release_date;
            $data['type_id'] = $type_id;
            $data['issuing_organization_id'] = $document->issuing_department;
            $data['slno'] = 1;

            //update
            if ($request->input("type") === 'target') {
                $data['unit'] = $unit;
                $data['target_type'] = $request->target_type;
                $data['target'] = $request->target;
            } else {
                $data['task_type'] = $request->task_type;
                $data['result_type'] = $result_type;
                $data['request_results_task'] = $request->input('request_results_task');
            }

            // Tạo bản ghi mới
            // dd($data);
            $taskTarget = TaskTarget::create($data);
            session(['taskTargetAssign' => $taskTarget]);

            DB::commit();
            return redirect()->route('tasks.assign-organizations', [
                'taskTargetId' => $taskTarget->id
            ])->with('success', 'Tạo ' . $typeRecord . ' thành công! Hãy giao việc cho cơ quan/tổ chức');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating task/target: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    public function show($code)
    {
        $documents = Document::where('isDelete', 0)->get();
        $taskTarget = TaskTarget::where('code', $code)->first();
        $organizationIds = TaskTarget::where('code', $code)->where('isDelete', 0)
            ->pluck('organization_id');
        $organizations = Organization::whereIn('id', $organizationIds)->where('isDelete', 0)->whereNotNull('organization_type_id')->orderBy('name', 'asc')->get();
        return view('tasks.show', compact('taskTarget', 'organizations', 'documents'));
    }

    public function edit($code)
    {

        return view('tasks.edit', compact());
    }

    public function update(Request $request, $code)
    {
        DB::beginTransaction();

        try {
            $typeRecord = $request->input("type") === 'target' ? "Chỉ tiêu" : "Nhiệm vụ";
            $request->validate([
                'document_id' => 'required',
                'code' => 'required',
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
                // 'cycle_type.required' => 'Chu kỳ báo cáo là bắt buộc.',
                'cycle_type.string' => 'Chu kỳ báo cáo phải là một chuỗi ký tự.',
                'cycle_type.max' => 'Chu kỳ báo cáo không được vượt quá 50 ký tự.',
                'category_id.integer' => 'Phân loại phải là một số nguyên.',
                'end_date.date' => 'Ngày kết thúc không hợp lệ.',
                'type.required' => 'Loại mục tiêu là bắt buộc.',
                'type_id.required' => 'Loại mục tiêu là bắt buộc.',
                'type.in' => 'Loại mục tiêu phải là "task" hoặc "target".',
            ]);

            $taskTarget = TaskTarget::where('code', $code)->get();
            foreach ($taskTarget as $item) {
                $exitItem = TaskGroup::where('isDelete', 0)->where('code', $request->code)->where('id', '!=', $item->id)->first();
                if ($exitItem) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Mã đã tồn tại!');
                }
                $item->update($request->all());
                DB::commit();
            }


            return redirect()->route('tasks.index', ['type' => $request->input('cycle_type')])->with('success', 'Cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating task/target: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
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


        return redirect()->route('tasks.index', ['type' => $type])->with('success', 'Xóa thành công.');
    }

    public function getOrganizationIdByCode($taskTargetCode)
    {
        $organizationId = TaskTarget::where('code', $taskTargetCode)->select('organization_id')->where('isDelete', 0)->get();
        return response()->json($organizationId);
    }

    public function exportTaskTarget(Request $request, $type, $text=null){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        if($type == "task"){
            $sheet->setCellValue('A1', 'DANH SÁCH NHIỆM VỤ');
            $sheet->mergeCells('A1:I1');
            $sheet->getStyle('A1:I1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFC2C2C2'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);

            $sheet->setCellValue('A2', 'STT');
            $sheet->setCellValue('B2', 'Tên nhiệm vụ');
            $sheet->setCellValue('C2', 'Kết quả yêu cầu');
            $sheet->setCellValue('D2', 'Nhóm nhiệm vụ');
            $sheet->setCellValue('E2', 'Loại nhiệm vụ');
            $sheet->setCellValue('F2', 'Thời hạn hoàn thành');
            $sheet->setCellValue('G2', 'Số hiệu văn bản');
            $sheet->setCellValue('H2', 'Số đơn vị được giao');
            $sheet->setCellValue('I2', 'Tiến độ');

            // Định dạng hàng tiêu đề
            $sheet->getStyle('A2:I2')->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFC2C2C2'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
            $query = $this->dataIndexView($request, $type, $text);
            $excelData = $query->where('isDelete', 0)->orderBy('created_at', 'desc')->get();
            $row = 3;
            foreach ($excelData as $index => $data) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $data->name);
                $sheet->setCellValue('C' . $row, $data->request_results_task);
                $sheet->setCellValue('D' . $row, $data->getGroupName());
                $sheet->setCellValue('E' . $row, $data->getTypeTextAttributeTime());
                $sheet->setCellValue('F' . $row, $data->getEndDate());
                $sheet->setCellValue('G' . $row, $data->document->document_code ?? ''());
                $sheet->setCellValue('H' . $row, $data->countOrganization());
                $sheet->setCellValue('I' . $row, $data->getStatusLabel());

                $row++;
            }
            $sheet->getStyle('A3:I' . ($row - 1))->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
            $sheet->getStyle('B3:B' . ($row - 1))->getAlignment()->setWrapText(true);
            $sheet->getColumnDimension('B')->setWidth(60);

            foreach (range('A1', 'I1') as $columnID) {
                if ($columnID !== 'B') {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            }
        }
        else{
            $sheet->setCellValue('A1', 'DANH SÁCH CHỈ TIÊU');
            $sheet->mergeCells('A1:H1');
            $sheet->getStyle('A1:H1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFC2C2C2'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);

            $sheet->setCellValue('A2', 'STT');
            $sheet->setCellValue('B2', 'Tên chỉ tiêu');
            $sheet->setCellValue('C2', 'Đơn vị tính');
            $sheet->setCellValue('D2', 'Chỉ tiêu');
            $sheet->setCellValue('E2', 'Thời hạn hoàn thành');
            $sheet->setCellValue('F2', 'Số hiệu văn bản');
            $sheet->setCellValue('G2', 'Số đơn vị được giao');
            $sheet->setCellValue('H2', 'Tiến độ');

            // Định dạng hàng tiêu đề
            $sheet->getStyle('A2:H2')->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFC2C2C2'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
            $query = $this->dataIndexView($request, $type, $text);
            $excelData = $query->where('isDelete', 0)->orderBy('created_at', 'desc')->get();
            $row = 3;
            foreach ($excelData as $index => $data) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $data->name);
                $sheet->setCellValue('C' . $row, $data->getUnitName());
                $sheet->setCellValue('D' . $row, $data->target);
                $sheet->setCellValue('E' . $row, $data->getEndDate());
                $sheet->setCellValue('F' . $row, $data->document->document_code ?? ''());
                $sheet->setCellValue('G' . $row, $data->countOrganization());
                $sheet->setCellValue('H' . $row, $data->getStatusLabel());

                $row++;
            }
            $sheet->getStyle('A3:H' . ($row - 1))->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
            $sheet->getStyle('B3:B' . ($row - 1))->getAlignment()->setWrapText(true);
            $sheet->getColumnDimension('B')->setWidth(60);

            foreach (range('A1', 'H1') as $columnID) {
                if ($columnID !== 'B') {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            }
        }

        $writer = new Xlsx($spreadsheet);
        if($type == "task"){
            $filename = 'Danh sách nhiệm vụ.xlsx';
        }
        else{
            $filename = 'Danh sách chỉ tiêu.xlsx';
        }

        // Gửi file Excel cho người dùng
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportDetailTaskTarget($id, $type){
        $taskTarget = TaskTarget::where('id', $id)->where('isDelete', 0)->firstOrFail();
        $taskResult = TaskResult::where('id_task_criteria', $id)->where('isDelete', 0)->get();
        $organizationTypes = OrganizationType::withCount(['organizations' => function ($query) use ($taskTarget) {
            $query->whereHas('taskResults', function ($q) use ($taskTarget) {
                $q->where('id_task_criteria', $taskTarget->id); 
            });
        }])->having('organizations_count', '>', 0)->get();
        $workResultTypes = MasterWorkResultTypeService::index();
        $valueType = '';
        foreach ($workResultTypes as $idx => $result_type) {
            if ($type != 'task' && $idx == 4) {
                continue;
            }
        
            if ($taskTarget->result_type == $result_type->key) {
                $valueType = $result_type->value;
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        if($type == "task"){
            $sheet->setCellValue('A1', 'Tên nhiệm vụ: ');
            $sheet->setCellValue('A2', 'Chu kỳ báo cáo: ');
            $sheet->setCellValue('A3', 'Kiểu dữ liệu báo cáo: ');
            $sheet->setCellValue('A4', 'Số liệu văn bản: ');
            $sheet->setCellValue('A5', 'Ngày bắt đầu: ');
            $sheet->getStyle('A1:A5')->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFC2C2C2'],
                ],
            ]);
            $sheet->setCellValue('B1', $taskTarget->name);
            $sheet->setCellValue('B2', $taskTarget->getCycleTypeTextAttribute());
            $sheet->setCellValue('B3', $valueType);
            $sheet->setCellValue('B4', $taskTarget->document->document_code);
            $sheet->setCellValue('B5', $taskTarget->getStartDate());

            $sheet->setCellValue('D1', 'Nhóm nhiệm vụ: ');
            $sheet->setCellValue('D2', 'Kết quả yêu cầu: ');
            $sheet->setCellValue('D3', 'Loại nhiệm vụ: ');
            $sheet->setCellValue('D4', 'Văn bản giao việc: ');
            $sheet->setCellValue('D5', 'Thời hạn hoàn thành: ');
            $sheet->getStyle('D1:D5')->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFC2C2C2'],
                ],
            ]);
            $sheet->setCellValue('E1', $taskTarget->getGroupTaskTarget()->name ?? '');
            $sheet->setCellValue('E2', $taskTarget->request_results_task);
            $sheet->setCellValue('E3', $taskTarget->getTypeTextAttributeTime());
            $sheet->setCellValue('E4', $taskTarget->document->document_name);
            $sheet->setCellValue('E5', $taskTarget->getEndDate());

            $row = 6;
            $totalRows = count($organizationTypes);
            $half = ceil($totalRows / 2);

            for ($i = 0; $i < $half; $i++) {
                $sheet->setCellValue('A' . $row, $organizationTypes[$i]->type_name);
                $sheet->setCellValue('B' . $row, $organizationTypes[$i]->organizations_count);
                $row++;
            }
            $row = 6;
            for ($i = $half; $i < $totalRows; $i++) {
                $sheet->setCellValue('D' . $row, $organizationTypes[$i]->type_name);
                $sheet->setCellValue('E' . $row, $organizationTypes[$i]->organizations_count);
                $row++;
            }
            
            $row = $row + 1;
            $sheet->setCellValue('A' . $row, 'Cơ quan, tổ chức đã được giao');
            $sheet->mergeCells('A' . $row . ':E' . $row);
            $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFC2C2C2'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
            $rowH = $row + 1;
            $sheet->setCellValue('A' . $rowH, 'Mã cơ quan, tổ chức');
            $sheet->setCellValue('B' . $rowH, 'Tên cơ quan, tổ chức');
            $sheet->setCellValue('C' . $rowH, 'Loại cơ quan, tổ chức');
            $sheet->setCellValue('D' . $rowH, 'Chu kỳ');
            $sheet->setCellValue('E' . $rowH, 'Kết quả');

            // Định dạng hàng tiêu đề
            $sheet->getStyle('A' . $rowH . ':E' . $rowH)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFC2C2C2'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
            
            $rowT = $rowH + 1;
            foreach ($taskResult as $index => $item) {
                $sheet->setCellValue('A' . $rowT, $item->organization->code ?? '');
                $sheet->setCellValue('B' . $rowT, $item->organization->name ?? '');
                $sheet->setCellValue('C' . $rowT, $item->organization->organizationType->type_name ?? '');
                $sheet->setCellValue('D' . $rowT, $item->getCycleTypeTextAttribute() ?? '');
                $sheet->setCellValue('E' . $rowT, $item->result ?? 'chưa có kết quả báo cáo');

                $rowT++;
            }
            $sheet->getStyle('A' . $rowH . ':E' . ($rowT - 1))->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);

            foreach (range('A'. $rowH, 'E' . $rowH) as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        }
        else{
            $sheet->setCellValue('A1', 'Tên chỉ tiêu: ');
            $sheet->setCellValue('A2', 'Chỉ tiêu: ');
            $sheet->setCellValue('A3', 'Chu kỳ báo cáo: ');
            $sheet->setCellValue('A4', 'Ngày bắt đầu: ');
            $sheet->getStyle('A1:A4')->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFC2C2C2'],
                ],
            ]);
            $sheet->setCellValue('B1', $taskTarget->name);
            $sheet->setCellValue('B2', $taskTarget->target);
            $sheet->setCellValue('B3', $taskTarget->getCycleTypeTextAttribute());
            $sheet->setCellValue('B4', $taskTarget->getStartDate());

            $sheet->setCellValue('D1', 'Đơn vị tính: ');
            $sheet->setCellValue('D2', 'Nhóm chỉ tiêu: ');
            $sheet->setCellValue('D3', 'Số liệu văn bản: ');
            $sheet->setCellValue('D4', 'Thời hạn hoàn thành: ');
            $sheet->getStyle('D1:D4')->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFC2C2C2'],
                ],
            ]);
            $sheet->setCellValue('E1', $taskTarget->unit()->name ??'');
            $sheet->setCellValue('E2', $taskTarget->getGroupTaskTarget()->name ?? '');
            $sheet->setCellValue('E3', $taskTarget->document->document_code);
            $sheet->setCellValue('E4', $taskTarget->getEndDate());

            $row = 5;
            $totalRows = count($organizationTypes);
            $half = ceil($totalRows / 2);

            for ($i = 0; $i < $half; $i++) {
                $sheet->setCellValue('A' . $row, $organizationTypes[$i]->type_name);
                $sheet->setCellValue('B' . $row, $organizationTypes[$i]->organizations_count);
                $row++;
            }
            $row = 5;
            for ($i = $half; $i < $totalRows; $i++) {
                $sheet->setCellValue('D' . $row, $organizationTypes[$i]->type_name);
                $sheet->setCellValue('E' . $row, $organizationTypes[$i]->organizations_count);
                $row++;
            }
            
            $row = $row + 1;
            $sheet->setCellValue('A' . $row, 'Cơ quan, tổ chức đã được giao');
            $sheet->mergeCells('A' . $row . ':E' . $row);
            $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFC2C2C2'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
            $rowH = $row + 1;
            $sheet->setCellValue('A' . $rowH, 'Mã cơ quan, tổ chức');
            $sheet->setCellValue('B' . $rowH, 'Tên cơ quan, tổ chức');
            $sheet->setCellValue('C' . $rowH, 'Loại cơ quan, tổ chức');
            $sheet->setCellValue('D' . $rowH, 'Chu kỳ');
            $sheet->setCellValue('E' . $rowH, 'Kết quả');

            // Định dạng hàng tiêu đề
            $sheet->getStyle('A' . $rowH . ':E' . $rowH)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFC2C2C2'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
            
            $rowT = $rowH + 1;
            foreach ($taskResult as $index => $item) {
                $sheet->setCellValue('A' . $rowT, $item->organization->code ?? '');
                $sheet->setCellValue('B' . $rowT, $item->organization->name ?? '');
                $sheet->setCellValue('C' . $rowT, $item->organization->organizationType->type_name ?? '');
                $sheet->setCellValue('D' . $rowT, $item->getCycleTypeTextAttribute() ?? '');
                $sheet->setCellValue('E' . $rowT, $item->result ?? 'chưa có kết quả báo cáo');

                $rowT++;
            }
            $sheet->getStyle('A' . $rowH . ':E' . ($rowT - 1))->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);

            foreach (range('A'. $rowH, 'E' . $rowH) as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        }

        $writer = new Xlsx($spreadsheet);
        if($type == "task"){
            $filename = 'Nhiệm vụ ' . $taskTarget->code . '.xlsx';
        }
        else{
            $filename = 'Chỉ tiêu ' . $taskTarget->code . '.xlsx';
        }

        // Gửi file Excel cho người dùng
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
