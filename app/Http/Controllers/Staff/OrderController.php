<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')
            ->latest()
            ->get();

        return view('staff.orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::with('sizes')->get();

        $tables = Table::whereDoesntHave('orders', function ($q) {
            $q->where('status', 'pending');
        })->get();

        $categories = Category::all();

        return view('staff.orders.create', compact('products', 'tables', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.size_id' => 'required|exists:product_sizes,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        // check bàn đã có đơn pending chưa
        $exists = Order::where('table_id', $request->table_id)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bàn này đã có đơn!');
        }

        DB::beginTransaction();

        try {

            $order = Order::create([
                'user_id' => auth()->id(),
                'table_id' => $request->table_id,
                'total_price' => 0,
                'status' => 'pending'
            ]);

            $total = 0;

            foreach ($request->items as $item) {

                $product = Product::findOrFail($item['product_id']);

                $size = ProductSize::where('id', $item['size_id'])
                    ->where('product_id', $item['product_id'])
                    ->first();

                if (!$size) continue;

                $price = $product->price + $size->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['qty'],
                    'price' => $price,
                    'size' => $size->size,
                    'sugar' => $item['sugar'] ?? 'Không',
                    'ice' => $item['ice'] ?? 'Không',
                ]);

                $total += $price * $item['qty'];
            }

            // tránh đơn rỗng
            if ($total <= 0) {
                DB::rollBack();
                return back()->with('error', 'Vui lòng chọn ít nhất 1 món');
            }

            $order->update([
                'total_price' => $total
            ]);

            // cập nhật trạng thái bàn
            Table::where('id', $request->table_id)
                ->update(['status' => 'using']);

            DB::commit();

            return redirect()->route('staff.orders.index')
                ->with('success', 'Tạo đơn thành công');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Lỗi khi tạo đơn: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $order = Order::with('items')->findOrFail($id);

        $products = Product::with('sizes')->get();

        $categories = Category::all();

        return view('staff.orders.edit', compact(
            'order',
            'products',
            'categories'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.size_id' => 'required|exists:product_sizes,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $order = Order::findOrFail($id);

        DB::beginTransaction();

        try {

            // xoá item cũ
            $order->items()->delete();

            $total = 0;

            foreach ($request->items as $item) {

                $product = Product::findOrFail($item['product_id']);

                $size = ProductSize::where('id', $item['size_id'])
                    ->where('product_id', $item['product_id'])
                    ->first();

                if (!$size) continue;

                $price = $product->price + $size->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['qty'],
                    'price' => $price,
                    'size' => $size->size,
                    'sugar' => $item['sugar'] ?? 'Không',
                    'ice' => $item['ice'] ?? 'Không',
                ]);

                $total += $price * $item['qty'];
            }

            if ($total <= 0) {
                DB::rollBack();
                return back()->with('error', 'Đơn hàng không hợp lệ');
            }

            $order->update([
                'total_price' => $total
            ]);

            DB::commit();

            return redirect()->route('staff.orders.index')
                ->with('success', 'Cập nhật đơn thành công');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Lỗi cập nhật: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // 🔥 FIX Ở ĐÂY: details -> items
        $order = Order::with(['items.product', 'user'])

            ->findOrFail($id);

        return view('staff.orders.show', compact('order'));
    }
    public function destroy($id)
    {
        return back()->with('error', 'Không được phép xóa đơn!');
    }

    public function cancel(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        DB::beginTransaction();

        try {
            $order = Order::findOrFail($id);

            if ($order->status === 'paid') {
                return back()->with('error', 'Đơn đã thanh toán, không thể hủy');
            }

            if ($order->status === 'cancelled') {
                return back()->with('info', 'Đơn đã bị hủy trước đó');
            }

            $order->update([
                'status' => 'cancelled'
            ]);

            DB::table('cancelled_orders')->insert([
                'order_id' => $order->id,
                'cancelled_by' => auth()->id(),
                'reason' => $request->reason,
                'cancelled_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Table::where('id', $order->table_id)
                ->update(['status' => 'empty']);

            DB::commit();

            return back()->with('success', 'Hủy đơn thành công');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Lỗi hủy đơn: ' . $e->getMessage());
        }
    }

    public function pay($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'paid') {
            return back()->with('info', 'Đơn đã thanh toán trước đó');
        }

        if ($order->status === 'cancelled') {
            return back()->with('error', 'Đơn đã bị hủy, không thể thanh toán');
        }

        $order->update([
            'status' => 'paid'
        ]);

        Table::where('id', $order->table_id)
            ->update(['status' => 'empty']);

        return back()->with('success', 'Đã thanh toán');
    }
}
