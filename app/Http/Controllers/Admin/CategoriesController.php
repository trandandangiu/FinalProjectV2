<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;

class CategoriesController extends Controller
{
    public function showFormAddCategories()
    {
        return view('admin.pages.categories-add');
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $imagePath = null;
        if ($request->hasFile("image")) {
            $imagePath = $request->file("image");
            $fileName = now()->timestamp . '_' . uniqid() . '.' . $imagePath->getClientOriginalExtension();
            $imagePath = $imagePath->storeAs('uploads/categories', $fileName, 'public');
        }
        Category::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'description' => $request->input('description'),
            'image' => $imagePath,
        ]);
        return redirect()->route('admin.categories.add')->with('success', 'Danh mục đã được thêm thành công');
    }


    public function index()
    {
        $categories = Category::all();
        return view('admin.pages.categories', compact('categories'));
    }

    public function updateCategory(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $category = Category::findOrFail($request->input('id'));

            $category->name = $request->input('name');
            $category->slug = Str::slug($request->input('name'));
            $category->description = $request->input('description');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/categories', $fileName, 'public');
                $category->image = $path;
            }

            $category->save();

            return response()->json([
                'status' => true,
                'message' => 'Danh mục đã được cập nhật thành công!',
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'image' => $category->image ? asset('storage/' . $category->image) : null,
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Đã có lỗi xảy ra, vui lòng thử lại sau'
            ], 500);
        }
    }

public function deleteCategory(Request $request) {
    try {
        $category = Category::find($request->category_id);
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Danh mục không tồn tại!'
            ], 404);
        }

        // Xóa ảnh nếu có
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa danh mục thành công!'
        ]);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => 'Đã có lỗi xảy ra, vui lòng thử lại sau!'
        ], 500);
    }
}
}
