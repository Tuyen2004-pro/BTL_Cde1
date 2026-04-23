<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::all();
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $order = Order::create([
            'user_id' => auth()->id(),
            'table_id' => $request->table_id,
            'total_price' => 0,
            'status' => 'pending'
        ]);

        $total = 0;

        foreach ($request->products as $product_id => $qty) {
            if ($qty > 0) {
                $product = Product::find($product_id);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product_id,
                    'quantity' => $qty,
                    'price' => $product->price
                ]);

                $total += $product->price * $qty;
            }
        }

        $order->update(['total_price' => $total]);

        return redirect()->route('orders.index')->with('success', 'Tạo đơn thành công');
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return back()->with('success', 'Xoá đơn');
    }

    // Thanh toán
    public function pay($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'paid']);

        return back()->with('success', 'Đã thanh toán');
    }
}
