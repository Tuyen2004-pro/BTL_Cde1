<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'sizes'])->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required'
        ]);

        $data = $request->all();

        // upload ảnh
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // tạo product
        $product = Product::create($data);

        // 🔥 FIX: LUÔN LƯU SIZE (KỂ CẢ = 0)
        if ($request->sizes) {
            foreach ($request->sizes as $size => $price) {

                ProductSize::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'price' => ($price === null || $price === '') ? 0 : $price,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công');
    }

    public function edit($id)
    {
        $product = Product::with('sizes')->findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required'
        ]);

        $product = Product::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        // 🔥 XÓA SIZE CŨ
        ProductSize::where('product_id', $product->id)->delete();

        // 🔥 FIX: LƯU LẠI SIZE (KỂ CẢ = 0)
        if ($request->sizes) {
            foreach ($request->sizes as $size => $price) {

                ProductSize::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'price' => ($price === null || $price === '') ? 0 : $price,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return back()->with('success', 'Xóa thành công');
    }
}
