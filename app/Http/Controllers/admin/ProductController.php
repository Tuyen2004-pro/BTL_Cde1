<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSize;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DANH SÁCH
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $products = Product::with([
            'category',
            'sizes',
            'ingredients'
        ])->latest()->get();

        return view(
            'admin.products.index',
            compact('products')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM THÊM
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $categories = Category::all();

        $ingredients = Ingredient::all();

        return view(
            'admin.products.create',
            compact(
                'categories',
                'ingredients'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LƯU
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPLOAD ẢNH
        |--------------------------------------------------------------------------
        */

        $imagePath = null;

        if ($request->hasFile('image')) {

            $imagePath = $request
                ->file('image')
                ->store('products', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | TẠO PRODUCT
        |--------------------------------------------------------------------------
        */

        $product = Product::create([

            'name' => $request->name,

            'price' => $request->price,

            'category_id' => $request->category_id,

            'description' => $request->description,

            'image' => $imagePath,

            'status' => 1,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIZE
        |--------------------------------------------------------------------------
        */

        if ($request->sizes) {

            foreach ($request->sizes as $size => $price) {

                if ($price !== null && $price !== '') {

                    ProductSize::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'price' => $price
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CÔNG THỨC NGUYÊN LIỆU
        |--------------------------------------------------------------------------
        */

        if ($request->ingredient_ids) {

            foreach ($request->ingredient_ids as $index => $ingredientId) {

                $quantity =
                    $request->ingredient_quantities[$index] ?? 0;

                if (
                    $ingredientId &&
                    $quantity &&
                    $quantity > 0
                ) {

                    $product->ingredients()->attach(
                        $ingredientId,
                        [
                            'quantity' => $quantity
                        ]
                    );
                }
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Thêm sản phẩm thành công'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM SỬA
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $product = Product::with([
            'sizes',
            'ingredients'
        ])->findOrFail($id);

        $categories = Category::all();

        $ingredients = Ingredient::all();

        return view(
            'admin.products.edit',
            compact(
                'product',
                'categories',
                'ingredients'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $product = Product::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | DATA UPDATE
        |--------------------------------------------------------------------------
        */

        $data = [

            'name' => $request->name,

            'price' => $request->price,

            'category_id' => $request->category_id,

            'description' => $request->description,
        ];

        /*
        |--------------------------------------------------------------------------
        | UPLOAD ẢNH MỚI
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            // xóa ảnh cũ
            if (
                $product->image &&
                Storage::disk('public')->exists($product->image)
            ) {

                Storage::disk('public')
                    ->delete($product->image);
            }

            // lưu ảnh mới
            $data['image'] = $request
                ->file('image')
                ->store('products', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE PRODUCT
        |--------------------------------------------------------------------------
        */

        $product->update($data);

        /*
        |--------------------------------------------------------------------------
        | UPDATE SIZE
        |--------------------------------------------------------------------------
        */

        // xóa size cũ
        ProductSize::where(
            'product_id',
            $product->id
        )->delete();

        // thêm size mới
        if ($request->sizes) {

            foreach ($request->sizes as $size => $price) {

                if ($price !== null && $price !== '') {

                    ProductSize::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'price' => $price
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE CÔNG THỨC
        |--------------------------------------------------------------------------
        */

        // xóa công thức cũ
        $product->ingredients()->detach();

        // thêm công thức mới
        if ($request->ingredient_ids) {

            foreach ($request->ingredient_ids as $index => $ingredientId) {

                $quantity =
                    $request->ingredient_quantities[$index] ?? 0;

                if (
                    $ingredientId &&
                    $quantity &&
                    $quantity > 0
                ) {

                    $product->ingredients()->attach(
                        $ingredientId,
                        [
                            'quantity' => $quantity
                        ]
                    );
                }
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Cập nhật sản phẩm thành công'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | XÓA
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // xóa ảnh
        if (
            $product->image &&
            Storage::disk('public')->exists($product->image)
        ) {

            Storage::disk('public')
                ->delete($product->image);
        }

        // xóa size
        ProductSize::where(
            'product_id',
            $product->id
        )->delete();

        // xóa công thức
        $product->ingredients()->detach();

        // xóa product
        $product->delete();

        return back()->with(
            'success',
            'Xóa sản phẩm thành công'
        );
    }
}
