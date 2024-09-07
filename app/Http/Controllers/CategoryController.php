<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Phương thức để lấy danh sách danh mục
    public function listCategories()
    {
        $categories = Category::where('isDelete', 0)->get();
        
        return response()->json(['categories' => $categories]);
    }
    public function index($text = null)
    {
        $categories = Category::where('isDelete', 0)->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'CategoryName' => 'required|string|max:500',
            'CreatedBy' => 'nullable|string|max:256',
            // Add validation for other fields as needed
        ]);

        // Create a new category
        $category = new Category();
        $category->CategoryName = $request->input('CategoryName');
        $category->CreatedBy = $request->input('CreatedBy');
        $category->CreatedDTG = now(); // Use the current date and time
        $category->save();

        // Flash a success message to the session
        return redirect()->route('categories.index')->with('success', 'Danh mục tạo thành công!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'CategoryName' => 'required|string|max:500',
            'UpdatedBy' => 'nullable|string|max:256',
            // Add validation for other fields as needed
        ]);

        // Find and update the category
        $category = Category::findOrFail($id);
        $category->CategoryName = $request->input('CategoryName');
        $category->UpdatedBy = $request->input('UpdatedBy');
        $category->UpdatedDTG = now(); // Use the current date and time
        $category->save();

        // Flash a success message to the session
        return redirect()->route('categories.index')->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        // Find and delete the category
        $category = Category::findOrFail($id);
        $category->isDelete = 1;
        $category->save();

        // Flash a success message to the session
        return redirect()->route('categories.index')->with('success', 'Danh mục đã được xóa thành công!');
    }
}
