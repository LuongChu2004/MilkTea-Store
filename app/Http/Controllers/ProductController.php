<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $sizes = ProductSize::where('product_id', $id)->get();

        $suggestedProducts = Product::where('id', '!=', $id)
            ->select('products.*')
            ->selectRaw('(SELECT MIN(price) FROM product_sizes WHERE product_sizes.product_id = products.id) as price')
            ->limit(8)
            ->get();

        return view('details', compact('product', 'sizes', 'suggestedProducts'));
    }
}
