<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    
   
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
