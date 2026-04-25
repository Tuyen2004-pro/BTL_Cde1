<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // load user + items để hiển thị nhanh hơn
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        // 🔥 FIX Ở ĐÂY: details -> items
        $order = Order::with(['items.product', 'user'])

            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order = Order::findOrFail($id);

        // Luồng trạng thái hợp lệ
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['shipping', 'cancelled'],
            'shipping' => ['completed'],
        ];

        if (
            !isset($validTransitions[$order->status]) ||
            !in_array($request->status, $validTransitions[$order->status])
        ) {
            return back()->with('error', 'Trạng thái không hợp lệ');
        }

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Cập nhật trạng thái thành công');
    }
}
