<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->get();

        return view('staff.orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::all();

        $tables = Table::where('status', 'available')
            ->orWhereNull('status')
            ->get();

        return view('staff.orders.create', compact('products', 'tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required'
        ]);

        // Tạo order
        $order = Order::create([
            'user_id' => auth()->id(),
            'table_id' => $request->table_id,
            'status' => 'pending',
            'total_price' => 0
        ]);

        $total = 0;

        // Lưu sản phẩm
        if ($request->products) {
            foreach ($request->products as $productId => $qty) {

                if ($qty > 0) {

                    $product = Product::find($productId);

                    if ($product) {

                        $sub = $product->price * $qty;

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $productId,
                            'quantity' => $qty,
                            'price' => $product->price
                        ]);

                        $total += $sub;
                    }
                }
            }
        }

        // Update tổng tiền
        $order->update([
            'total_price' => $total
        ]);

        return redirect()
            ->route('staff.orders.index')
            ->with('success', 'Tạo đơn thành công');
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        return view('staff.orders.show', compact('order'));
    }
    public function pay($id)
    {
        $order = Order::findOrFail($id);

        // cập nhật trạng thái
        $order->update([
            'status' => 'paid'
        ]);

        // (tuỳ chọn) cập nhật bàn về trống
        if ($order->table) {
            $order->table->update([
                'status' => 'available'
            ]);
        }

        return redirect()
            ->route('staff.orders.show', $id)
            ->with('success', 'Thanh toán thành công');
    }
}
