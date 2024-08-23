<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrganizationType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; 

class OrganizationTypeController extends Controller
{
    public function index()
    {
        $types = OrganizationType::orderBy('created_at', 'desc')->paginate(10);
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
                'code' => 'required|unique:organization_types,code',
                'type_name' => 'required'
            ], [
                'code.required' => 'Mã loại cơ quan, tổ chức là bắt buộc.',
                'code.unique' => 'Mã loại cơ quan, tổ chức đã tồn tại.',
                'name.required' => 'Tên loại cơ quan, tổ chức là bắt buộc.'
            ]);

            OrganizationType::create($request->all());
            DB::commit();
    
            return redirect()->route('organization_types.index')->with('success', 'Tạo loại cơ quan, tổ chức thành công!');
    
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
                 'required',
                     Rule::unique('organization_types', 'code')->ignore($id)
                 ],
                 'type_name' => 'required',
             ], [
                 'code.required' => 'Mã văn bản là bắt buộc.',
                 'code.unique' => 'Mã văn bản đã tồn tại.',
                 'type_name.required' => 'Tên văn bản là bắt buộc.'
             ]);
 
    
            $documentCategory = OrganizationType::findOrFail($id);
            $documentCategory->update($request->all());
            DB::commit();
    
            return redirect()->route('organization_types.index')->with('success', 'Cập nhật loại cơ quan, tổ chức thành công');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating document category: ' . $e->getMessage());
    
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

    }

    // Xử lý xóa loại cơ quan
    public function destroy(OrganizationType $organizationType)
    {
        $organizationType->delete();
        return redirect()->route('organization_types.index')->with('success', 'Loại cơ quan đã được xóa thành công!');
    }
}
