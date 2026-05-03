<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        // load kèm orders để check trạng thái bàn
        $tables = Table::with('orders')->latest()->paginate(10);

        return view('admin.tables.index', compact('tables'));
    }

    public function create()
    {
        return view('admin.tables.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:tables,name',
            'capacity' => 'nullable|integer|min:1|max:20'
        ]);

        Table::create($data);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Thêm bàn thành công');
    }

    public function edit($id)
    {
        $table = Table::findOrFail($id);

        return view('admin.tables.edit', compact('table'));
    }

    public function update(Request $request, $id)
    {
        $table = Table::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:tables,name,' . $id,
            'capacity' => 'nullable|integer|min:1|max:20'
        ]);

        $table->update($data);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        $table = Table::findOrFail($id);

        // (optional) không cho xoá nếu đang có order
        if ($table->orders()->where('status', 'pending')->exists()) {
            return redirect()->back()
                ->with('error', 'Không thể xoá bàn đang sử dụng');
        }

        $table->delete();

        return redirect()->route('admin.tables.index')
            ->with('success', 'Xoá thành công');
    }
}
