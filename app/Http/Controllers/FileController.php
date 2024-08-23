<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FileController extends Controller
{
    public function view($id)
    {
        $file = File::findOrFail($id);

        // Đảm bảo file tồn tại
        if (!file_exists(storage_path('app/public/' . $file->file_path))) {
            abort(404, 'File not found.');
        }

        $path = storage_path('app/public/' . $file->file_path);

        // Trả về file PDF để mở trên tab mới
        return response()->file($path);
    }

    public function download($id, $type, $cycleType, $numberType)
    {
        $currentYear = Carbon::now()->year;
        $file = File::where('cycle_type', $cycleType)->where('number_type', $numberType)->whereYear('created_at', $currentYear)->first();
        $filePath = storage_path('app/public/' . $file->file_path);
        if (file_exists($filePath)) {
            return response()->download($filePath, $file->file_name);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }
    }
    public function upload(Request $request)
    {
      
        $type = $request->input('type');
        $id = $request->input('file_id');
        $numberType = $request->input('numberType');
        $cycleType = $request->input('cycleType');

        $filePath = null; // Khởi tạo biến filePath với giá trị mặc định
        $fileName = null; // Khởi tạo biến fileName với giá trị mặc định

        if ($request->hasFile('files')) {
            $file = $request->file('files');
            $fileName = time() . '_' . $file->getClientOriginalName();
           
            $filePath = $file->storeAs('tasks', $fileName, 'public');
            // Tạo mới bản ghi file
            File::create([
                'document_id' => $id, // Cập nhật lại document_id cho đúng
                'file_name' => $fileName,
                'file_path' => $filePath,
                'type' => 1,
                'cycle_type' => $cycleType,
                'number_type' => $numberType
            ]);
            return response()->json([
                'success' => true,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'type' => $type,
                'id' =>$id
            ]);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'No file uploaded'
        ], 400);
    }
    
    // Xóa tệp
    public function destroy($id)
    {
        $file = File::findOrFail($id);
        $filePath = storage_path('app/public/' . $file->file_path);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $file->delete();

        return response()->json([
            'success' => true
        ]);
    }

   
}
