<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // Import Validator
class UserController extends Controller
{
    public function destroyOrganization(string $id)
    {
        // Tìm và xóa tài liệu trong bảng Document
        $user = User::findOrFail($id);
        $user->organization_id = null;
        $user->save();
        return redirect()->back()->with('success', 'Nhân viên đã không còn thuộc tổ chức!');
    }

    public function assignUsers(Request $request)
    {
        $assignedUsers = $request->all();

        // Logic để cập nhật dữ liệu vào cơ sở dữ liệu
        foreach ($assignedUsers as $user) {
            User::where('id', $user['userId'])->update([
                'organization_id' => $user['userOrganization']
            ]);
        }

        return response()->json(['organization_id' => $user['userOrganization']]);
    }
    public function searchUser(Request $request)
    {
        $query = $request->input('query');
        
        // Nếu query không rỗng, lọc theo query
        if (!empty($query)) {
            $tasks = User::where('code', 'like', "%{$query}%")
                        ->orWhere('name', 'like', "%{$query}%")
                        ->get();
        } else {
            // Nếu query rỗng, lấy tất cả các nhiệm vụ
            $tasks = User::all();
        }
        
        return response()->json([
            'user' => $tasks
        ]);
    }
    // Lấy danh sách người dùng
    public function listUsers()
    {
        $users = User::with('organization')->get();
        return response()->json(['users' => $users]);
    }

     // Lấy danh sách người dùng
    public function listUsersAll()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    // Gán người dùng cho tổ chức
    public function assignUser(Request $request)
    {
        $request->validate([
            'organization_id' => 'required',
            'user_id' => 'required',
        ]);
        $userId = Auth::id();
        $user = User::find($userId);
        
        // Kiểm tra xem người dùng đã thuộc tổ chức khác chưa
        if ($user->organization_id) {
            return response()->json(['success' => false, 'message' => 'Người dùng đã thuộc cơ quan tổ chức khác']);
        }

        $user->organization_id = $request->organization_id;
        $user->save();

        return response()->json(['success' => true]);
    }    
    
    public function saveAssignedUsers(Request $request)
    {
        $users = $request->input('user');
        $organization_id = $request->input('organization_id');
        foreach ($users as $user) {
            list($userId, $userCode) = explode('|', $user);

           $user = User::find($userId);
           $user->organization_id =  $organization_id;
           $user->save();
        }

        return redirect()->back()->with('success', 'Assigned users saved successfully');
    }

    public function index()
    {
        $users = User::with('organization')->orderBy('created_at', 'desc')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $organizations = Organization::all();
     return view('users.create', compact('organizations'));
       // return view('users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:users,code|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed', // Xác thực mật khẩu
            'organization_id' => 'nullable|exists:organizations,id',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Lưu người dùng mới
        $user = new User();
        $user->code = $request->input('code');
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->organization_id = $request->input('organization_id');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->save();
    
        return redirect()->route('users.index')->with('success', 'Người dùng đã được tạo thành công!');
    }
    public function edit($id)
    {
    
        $user = User::findOrFail($id);
        $organizations = Organization::all(); // Lấy danh sách tổ chức để hiển thị trong dropdown
        return view('users.edit', compact('user', 'organizations'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'organization_id' => 'nullable|exists:organizations,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // Tìm người dùng và cập nhật dữ liệu
        $user = User::findOrFail($id);
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        // Cập nhật mật khẩu nếu có thay đổi
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->organization_id = $validatedData['organization_id'];
        $user->phone = $validatedData['phone'];
        $user->address = $validatedData['address'];
        $user->save();

        // Chuyển hướng về danh sách người dùng với thông báo thành công
        return redirect()->route('users.index')->with('success', 'Người dùng đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}