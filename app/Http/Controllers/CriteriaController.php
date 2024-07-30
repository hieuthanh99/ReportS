<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{

    public function searchCriteria(Request $request)
    {
        $query = $request->input('query');
        
        // Nếu query không rỗng, lọc theo query
        if (!empty($query)) {
            $tasks = Criteria::where('code', 'like', "%{$query}%")
                        ->orWhere('name', 'like', "%{$query}%")
                        ->get();
        } else {
            // Nếu query rỗng, lấy tất cả các nhiệm vụ
            $tasks = Criteria::all();
        }
        
        return response()->json([
            'criteria' => $tasks
        ]);
    }
    
    public function checkCriteriaCode(Request $request)
    {
        $code = $request->input('criteriaCode');
        // Check if the code exists in the database
        $exists = Criteria::where('code', $code)->exists();

        return response()->json(['exists' => $exists]);
    }


    public function index()
    {
        // Lấy tất cả các tiêu chí
        $criteria = Criteria::all();
        return view('criteria.index', compact('criteria'));
    }

    public function create()
    {
        // Hiển thị form tạo tiêu chí mới
        return view('criteria.create');
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'code' => 'required|string|max:255|unique:criteria',
            'name' => 'required|string|max:255',
        ]);

        // Lưu tiêu chí mới vào cơ sở dữ liệu
        Criteria::create($request->all());

        // Điều hướng trở lại danh sách tiêu chí với thông báo thành công
        return redirect()->route('criteria.index')->with('success', 'Tiêu chí đã được tạo thành công.');
    }

    public function edit($id)
    {
        // Lấy tiêu chí để chỉnh sửa
        $criteria = Criteria::findOrFail($id);
        return view('criteria.edit', compact('criteria'));
    }

    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu
        $request->validate([
            'code' => 'required|string|max:255|unique:criteria,code,' . $id,
            'name' => 'required|string|max:255',
        ]);

        // Cập nhật tiêu chí trong cơ sở dữ liệu
        $criteria = Criteria::findOrFail($id);
        $criteria->update($request->all());

        // Điều hướng trở lại danh sách tiêu chí với thông báo thành công
        return redirect()->route('criteria.index')->with('success', 'Tiêu chí đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        // Xóa tiêu chí
        $criteria = Criteria::findOrFail($id);
        $criteria->delete();

        // Điều hướng trở lại danh sách tiêu chí với thông báo thành công
        return redirect()->route('criteria.index')->with('success', 'Tiêu chí đã được xóa thành công.');
    }
}
