<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('orderDetails.product');

        if ($request->filled('order_id')) {
            $query->where('id', $request->order_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.order.index', compact('orders'));
    }

    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        return view('admin.order.edit', compact('order'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['status' => 'required']);
        $order = Order::findOrFail($id);
        
        $updateData = ['status' => $request->status];
        if ($request->status === 'Hoàn thành') {
            $updateData['payment_status'] = 'completed';
        }

        $order->update($updateData);
        return redirect('admin/order')->with('success', 'Cập nhật trạng thái đơn hàng thành công');
    }
}
