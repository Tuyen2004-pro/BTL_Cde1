<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->get();

        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Supplier::create($request->all());

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Thêm nhà cung cấp thành công');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);

        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->update($request->all());

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        Supplier::destroy($id);

        return back()->with('success', 'Xóa thành công');
    }
}
