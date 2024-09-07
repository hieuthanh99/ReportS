<?php
namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index($text = null)
    {
        $types = Position::orderBy('created_at', 'desc')->where('isDelete', 0);
        if($types){
            $types->where('name', 'like', '%' . $text . '%');
        }
        $types =  $types->paginate(10);
        return view('positions.index', compact('types'));
    }

    public function create()
    {
        return view('positions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'description' => 'nullable',
        ], [
            'code.required' => 'Mã chức danh là bắt buộc.',
            'code.unique' => 'Mã chức danh đã tồn tại.',
            'name.required' => 'Tên chức danh là bắt buộc.',
        ]);
        $exitItem = Position::where('isDelete', 0)->where('code', $request->code)->first();
        if($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
        Position::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('positions.index')->with('success', 'Thêm mới chức danh thành công.');
    }

    public function edit(Position $position)
    {
        return view('positions.edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'description' => 'nullable',
        ], [
            'code.required' => 'Mã chức danh là bắt buộc.',
            'code.unique' => 'Mã chức danh đã tồn tại.',
            'name.required' => 'Tên chức danh là bắt buộc.',
        ]);
        $exitItem = Position::where('isDelete', 0)->where('code', $request->code)->where('id','!=', $position->id)->first();
        if($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
        $position->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('positions.index')->with('success', 'Cập nhật thành công chức danh.');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Xóa thành công chức danh');
    }
}
