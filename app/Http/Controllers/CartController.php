<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $id = $request->input('id');
        $num = $request->input('num', 1);
        $size = $request->input('size', 'No Size');
        $price = $request->input('price', 0);
        $sugar_level = $request->input('sugar_level', '');
        $ice_level = $request->input('ice_level', '');

        $product = Product::findOrFail($id);
        
        $cart = session()->get('cart', []);
        $cartKey = $id . '_' . md5($size . $sugar_level . $ice_level);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['num'] += $num;
        } else {
            $cart[$cartKey] = [
                'id' => $id,
                'title' => $product->title,
                'thumbnail' => $product->thumbnail,
                'num' => $num,
                'size' => $size,
                'price' => $price,
                'sugar_level' => $sugar_level,
                'ice_level' => $ice_level
            ];
        }

        session()->put('cart', $cart);

        return response()->json(['success' => true]);
    }

    public function update(Request $request)
    {
        $action = $request->input('action');
        $id = $request->input('id'); // this might need to be cartKey in the new logic

        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            if ($action == 'update') {
                $cart[$id]['num'] = $request->input('num');
                if ($cart[$id]['num'] <= 0) {
                    unset($cart[$id]);
                }
            } elseif ($action == 'delete') {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true]);
    }
}
