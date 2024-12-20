<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Organization;
use App\Models\Position;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // Import Validator
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
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
                        ->where('isDelete', 0)
                        ->get();
        } else {
            // Nếu query rỗng, lấy tất cả các nhiệm vụ
            $tasks = User::where('isDelete', 0)->get();;
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
        $users = User::where('isDelete', 0)->get();;
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

    public function index($text=null)
    {

        $users = User::with('organization')->orderBy('created_at', 'desc')->where('isDelete', 0);
        if($text){
            $users->where('name', 'like', '%' . $text . '%');
        }
        $users =  $users->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $positions = Position::where('isDelete', 0)->get();
        $organizations = Organization::where('isDelete', 0)->whereNotNull('organization_type_id')->orderBy('name', 'asc')->get();
        return view('users.create', compact('organizations', 'positions'));
       // return view('users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed', // Xác thực mật khẩu
           
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $exitItem = User::where('isDelete', 0)->where('code', $request->code)->first();
        if($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
        $exitItemEmail = User::where('isDelete', 0)->where('code', $request->email)->first();
        if($exitItemEmail)  return redirect()->back()->with('error', 'Email đã tồn tại!');
        // Lưu người dùng mới
        $user = new User();
        $user->code = $request->input('code');
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->organization_id = $request->input('organization_id');
        $user->phone = $request->input('phone');
        $user->position_id = $request->input('position_id');
        $user->address = $request->input('address');
        $user->role = $request->input('role');
        $user->save();
    
        return redirect()->route('users.index')->with('success', 'Người dùng đã được tạo thành công!');
    }
    public function edit($id)
    {
        $positions = Position::where('isDelete', 0)->get();

        $user = User::findOrFail($id);
        $organizations = Organization::where('isDelete', 0)->whereNotNull('organization_type_id')->orderBy('name', 'asc')->get();
        return view('users.edit', compact('user', 'organizations', 'positions'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required',
            'password' => 'nullable|string|min:8|confirmed',
   
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // Tìm người dùng và cập nhật dữ liệu
        $user = User::findOrFail($id);
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->role = $request->input('role');
        $user->position_id = $request->input('position_id');
        $user->organization_id = $request->input('organization_id');
        // Cập nhật mật khẩu nếu có thay đổi
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        // $user->organization_id = $validatedData['organization_id'];
        $user->phone = $validatedData['phone'];
        $user->address = $validatedData['address'];
        $user->save();

        // Chuyển hướng về danh sách người dùng với thông báo thành công
        return redirect()->route('users.index')->with('success', 'Người dùng đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->isDelete = 1;
        $user->save();
        return redirect()->route('users.index')->with('success', 'Xóa người dùng thành công.');
    }

    public function exportUser(Request $request, $text=null){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'DANH SÁCH NGƯỜI DÙNG');
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
        $sheet->setCellValue('B2', 'Mã danh mục');
        $sheet->setCellValue('C2', 'Tên danh mục');
        $sheet->setCellValue('D2', 'Tổ chức');
        $sheet->setCellValue('E2', 'Chức vụ');
        $sheet->setCellValue('F2', 'Email');
        $sheet->setCellValue('G2', 'Số điện thoại');
        $sheet->setCellValue('H2', 'Role');

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
        $users = User::with('organization')->orderBy('created_at', 'desc')->where('isDelete', 0)->get();
        $row = 3;
        foreach ($users as $index => $data) {
            $roleValue = '';
            if ($data->role === 'supper_admin') {
                $roleValue = 'Supper Admin';
            } elseif ($data->role === 'admin') {
                $roleValue = 'Admin';
            } elseif ($data->role === 'sub_admin') {
                $roleValue = 'Sub-Admin';
            } elseif ($data->role === 'staff') {
                $roleValue = 'Nhân viên';
            } else {
                $roleValue = 'Không xác định';
            }
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->code);
            $sheet->setCellValue('C' . $row, $data->name);
            $sheet->setCellValue('D' . $row, $data->organization->name ?? 'N/A');
            $sheet->setCellValue('E' . $row, $data->position->name ?? 'N/A');
            $sheet->setCellValue('F' . $row, $data->email);
            $sheet->setCellValue('G' . $row, $data->phone);
            $sheet->setCellValue('H' . $row, $roleValue);

            $row++;
        }
        $sheet->getStyle('A3:H' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Danh sách người dùng.xlsx';

        // Gửi file Excel cho người dùng
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}