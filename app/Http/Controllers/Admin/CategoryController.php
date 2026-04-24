<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.form');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Category::create($request->all());
        return redirect('admin/category')->with('success', 'Thêm danh mục thành công');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.form', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['name' => 'required']);
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return redirect('admin/category')->with('success', 'Cập nhật danh mục thành công');
    }

    public function destroy(string $id)
    {
        Category::destroy($id);
        return redirect('admin/category')->with('success', 'Xóa danh mục thành công');
    }
}
