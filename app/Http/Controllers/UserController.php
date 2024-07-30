<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
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
        dd($users);
        return response()->json(['users' => $users]);
    }

    // Gán người dùng cho tổ chức
    public function assignUser(Request $request)
    {
        $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'user_id' => 'required|exists:users,id',
        ]);
        $userId = Auth::id();
        $user = User::find($userId);
        
        // Kiểm tra xem người dùng đã thuộc tổ chức khác chưa
        if ($user->organization_id) {
            return response()->json(['success' => false, 'message' => 'User already assigned to another organization']);
        }

        $user->organization_id = $request->organization_id;
        $user->save();

        return response()->json(['success' => true]);
    }
}