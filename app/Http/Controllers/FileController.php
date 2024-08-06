<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    public function download($id, $type)
    {
        $file = File::where('id', $id)->first();
        //dd($file);
        // Lấy đường dẫn file
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

        $filePath = null; // Khởi tạo biến filePath với giá trị mặc định
        $fileName = null; // Khởi tạo biến fileName với giá trị mặc định

        if ($request->hasFile('files')) {
            $file = $request->file('files');
            $fileName = time() . '_' . $file->getClientOriginalName();
            //dd($fileName);
            if ($type == '1') {
                $filePath = $file->storeAs('tasks', $fileName, 'public');
            } else if ($type == '2') {
                $filePath = $file->storeAs('criterias', $fileName, 'public');
            } else {
                // Xử lý trường hợp type không phải 1 hoặc 2
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file type'
                ], 400);
            }

            // Tạo mới bản ghi file
            File::create([
                'document_id' => $id, // Cập nhật lại document_id cho đúng
                'file_name' => $fileName,
                'file_path' => $filePath,
                'type' => $type
            ]);
            return response()->json([
                'success' => true,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'type' => $type
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
