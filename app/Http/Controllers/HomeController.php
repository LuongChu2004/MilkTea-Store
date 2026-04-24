<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Get products with their lowest price from product_sizes
        $products = Product::select('products.*')
            ->selectRaw('(SELECT MIN(price) FROM product_sizes WHERE product_sizes.product_id = products.id) as price')
            ->paginate(12);
            
        return view('home', compact('products'));
    }
}
