<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::select('products.*')
            ->selectRaw('(SELECT MIN(price) FROM product_sizes WHERE product_sizes.product_id = products.id) as price');

        $search = $request->input('search');
        $categoryId = $request->input('id_category');
        $currentCategoryName = '';

        if (!empty($search)) {
            $query->where('title', 'LIKE', '%' . $search . '%');
        } elseif (!empty($categoryId)) {
            $query->where('id_category', $categoryId);
            $cat = Category::find($categoryId);
            if ($cat) {
                $currentCategoryName = $cat->name;
            }
        }

        $products = $query->get();

        return view('menu', compact('products', 'search', 'categoryId', 'currentCategoryName'));
    }
}
