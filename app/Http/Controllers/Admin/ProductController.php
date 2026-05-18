<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSize;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'sizes')->orderBy('id', 'desc');
        
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(10);
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.product.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'id_category' => 'required',
            'sizes' => 'required|array',
            'prices' => 'required|array'
        ]);

        $thumbnail = '';
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('admin_assets/product/uploads'), $filename);
            $thumbnail = 'uploads/' . $filename;
        }

        $product = Product::create([
            'title' => $request->title,
            'id_category' => $request->id_category,
            'content' => $request->content,
            'number' => 0,
            'thumbnail' => $thumbnail
        ]);

        foreach ($request->sizes as $index => $size) {
            if (!empty($size) && !empty($request->prices[$index])) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'price' => $request->prices[$index]
                ]);
            }
        }

        return redirect('admin/product')->with('success', 'Thêm sản phẩm thành công');
    }

    public function edit(string $id)
    {
        $product = Product::with('sizes')->findOrFail($id);
        $categories = Category::all();
        return view('admin.product.form', compact('product', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required',
            'id_category' => 'required',
            'sizes' => 'required|array',
            'prices' => 'required|array'
        ]);

        $product = Product::findOrFail($id);

        $thumbnail = $product->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('admin_assets/product/uploads'), $filename);
            $thumbnail = 'uploads/' . $filename;
        }

        $product->update([
            'title' => $request->title,
            'id_category' => $request->id_category,
            'content' => $request->content,
            'thumbnail' => $thumbnail
        ]);

        ProductSize::where('product_id', $product->id)->delete();

        foreach ($request->sizes as $index => $size) {
            if (!empty($size) && !empty($request->prices[$index])) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'price' => $request->prices[$index]
                ]);
            }
        }

        return redirect('admin/product')->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function destroy(string $id)
    {
        Product::destroy($id);
        return redirect('admin/product')->with('success', 'Xóa sản phẩm thành công');
    }
}
