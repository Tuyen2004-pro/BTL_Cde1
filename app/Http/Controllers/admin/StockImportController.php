<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\StockImport;
use Illuminate\Http\Request;

class StockImportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DANH SÁCH NHẬP HÀNG
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $ingredients = Ingredient::with('supplier')
            ->latest()
            ->get();

        $imports = StockImport::with([
            'ingredient.supplier'
        ])
            ->latest()
            ->get();

        return view(
            'admin.stock-imports.index',
            compact(
                'ingredients',
                'imports'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | NHẬP HÀNG
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'ingredient_id' => 'required|exists:ingredients,id',

            'quantity' => 'required|numeric|min:0.1',

            'total_price' => 'required|numeric|min:0',

            'import_unit' => 'required',

        ]);

        $ingredient = Ingredient::findOrFail(
            $request->ingredient_id
        );

        /*
        |--------------------------------------------------------------------------
        | SỐ LƯỢNG NGƯỜI DÙNG NHẬP
        |--------------------------------------------------------------------------
        */

        $quantity = $request->quantity;

        /*
        |--------------------------------------------------------------------------
        | CONVERT VỀ ĐƠN VỊ GỐC
        |--------------------------------------------------------------------------
        |
        | Hệ thống luôn lưu:
        |
        | g  hoặc  ml
        |
        */

        if ($request->import_unit == 'kg') {

            $quantity = $quantity * 1000;
        }

        if ($request->import_unit == 'l') {

            $quantity = $quantity * 1000;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE STOCK
        |--------------------------------------------------------------------------
        */

        $ingredient->increment(
            'stock',
            $quantity
        );

        /*
        |--------------------------------------------------------------------------
        | CẬP NHẬT GIÁ VỐN
        |--------------------------------------------------------------------------
        |
        | unit_price luôn tính theo:
        |
        | g hoặc ml
        |
        */

        $unitPrice =
            $request->total_price
            / $quantity;

        $ingredient->update([

            'unit_price' => $unitPrice

        ]);

        /*
        |--------------------------------------------------------------------------
        | LƯU LỊCH SỬ NHẬP
        |--------------------------------------------------------------------------
        */

        StockImport::create([

            'ingredient_id' => $ingredient->id,

            // quantity sau convert
            'quantity' => $quantity,

            'total_price' => $request->total_price,

        ]);

        return back()->with(

            'success',

            'Nhập hàng thành công'

        );
    }
}
