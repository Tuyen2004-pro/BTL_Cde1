<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        return view('tables.index', compact('tables'));
    }

    public function create()
    {
        return view('tables.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Table::create($request->all());

        return redirect()->route('tables.index')->with('success', 'Thêm bàn thành công');
    }

    public function edit($id)
    {
        $table = Table::findOrFail($id);
        return view('tables.edit', compact('table'));
    }

    public function update(Request $request, $id)
    {
        $table = Table::findOrFail($id);
        $table->update($request->all());

        return redirect()->route('tables.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        Table::destroy($id);
        return back()->with('success', 'Xoá thành công');
    }
}
