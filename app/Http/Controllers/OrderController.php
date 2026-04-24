<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function history()
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để xem lịch sử mua hàng.');
        }

        $orders = Order::where('user_id', Auth::id())
            ->with(['orderDetails.product']) // Need to define relationships in Models
            ->orderBy('id', 'desc')
            ->get();

        return view('history', compact('orders'));
    }
}
