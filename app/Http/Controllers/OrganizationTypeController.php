<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrganizationType;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; 
use App\Models\TaskTarget;
use App\Models\TaskResult;
use App\Models\User;

class OrganizationTypeController extends Controller
{
    public function index($text=null)
    {
        $types = OrganizationType::orderBy('type_name', 'asc')->where('isDelete', 0);
        if($text){
            $types->where('type_name', 'like', '%' . $text . '%');
        }
        $types = $types->paginate(10);
        return view('organization_types.index', compact('types'));
    }

    // Hiển thị form tạo mới loại cơ quan
    public function create()
    {
        return view('organization_types.create');
    }

    // Xử lý lưu loại cơ quan mới
    public function store(Request $request)
    {
        DB::beginTransaction();
      
        try {
                 // Validate input
            $request->validate([
                'code' => 'required',
                'type_name' => 'required'
            ], [
                'code.required' => 'Mã nhóm cơ quan, tổ chức là bắt buộc.',
                'code.unique' => 'Mã nhóm cơ quan, tổ chức đã tồn tại.',
                'name.required' => 'Tên nhóm cơ quan, tổ chức là bắt buộc.'
            ]);
            $exitItem = OrganizationType::where('isDelete', 0)->where('code', $request->code)->first();
            if($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
            OrganizationType::create($request->all());
            DB::commit();
    
            return redirect()->route('organization_types.index')->with('success', 'Tạo nhóm cơ quan, tổ chức thành công!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating document: ' . $e->getMessage());
    
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    // Hiển thị form chỉnh sửa loại cơ quan
    public function edit(OrganizationType $organizationType)
    {
        return view('organization_types.edit', compact('organizationType'));
    }

    // Xử lý cập nhật loại cơ quan
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $request->validate([
                'code' => [
                 'required'
                 ],
                 'type_name' => 'required',
             ], [
                 'code.required' => 'Mã nhóm cơ quan, tổ chức là bắt buộc.',
                 'code.unique' => 'Mã nhóm cơ quan, tổ chức đã tồn tại.',
                 'type_name.required' => 'Tên nhóm cơ quan, tổ chức là bắt buộc.'
             ]);
 
             $exitItem = OrganizationType::where('isDelete', 0)->where('code', $request->code)->where('id','!=', $id)->first();
             if($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
            $documentCategory = OrganizationType::findOrFail($id);
            $documentCategory->update($request->all());
            DB::commit();
    
            return redirect()->route('organization_types.index')->with('success', 'Cập nhật nhóm cơ quan, tổ chức thành công');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating document category: ' . $e->getMessage());
    
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

    }

    // Xử lý xóa loại cơ quan
    public function destroy(OrganizationType $organizationType)
    {
        // dd($organizationType);
        $organizations = Organization::where('organization_type_id', $organizationType->id)->whereNotNull('organization_type_id')->orderBy('name', 'asc')->get();

        foreach ($organizations as $organization) {

            $taskTargets = TaskTarget::where('organization_id', $organization->id)->get();

            foreach ($taskTargets as $taskTarget) {
                $taskTarget->isDelete = 1;
                $taskTarget->save();

                $taskReults = TaskResult::where('id_task_criteria', $taskTarget->id)->get();

                foreach ($taskReults as $taskReult) {
                    $taskReult->isDelete = 1;
                    $taskReult->save();
                }

    
            }
            $users = User::where('organization_id', $organization->id)->get();
    
            foreach ($users as $user) {
                $user->isDelete = 1;
                $user->save();
            }
            $organization->isDelete = 1;
            $organization->save();
        }
        $organizationType->isDelete = 1;
        $organizationType->save();

        
        return redirect()->route('organization_types.index')->with('success', 'Nhóm cơ quan đã được xóa thành công!');
    }
}
