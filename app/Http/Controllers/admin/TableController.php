<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::latest()->paginate(10);
        return view('admin.tables.index', compact('tables'));
    }

    public function create()
    {
        return view('admin.tables.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:available,occupied'
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
            'name' => 'required|string|max:255',
            'status' => 'required|in:available,occupied'
        ]);

        $table->update($data);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        Table::destroy($id);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Xoá thành công');
    }
}
