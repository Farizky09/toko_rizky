<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\UnitLargesInterfaces;
use Illuminate\Http\Request;

class UnitLargesController extends Controller
{
    private $unitLarges;
    public function __construct(UnitLargesInterfaces $unitLarges)
    {
        $this->unitLarges = $unitLarges;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->unitLarges->datatable();
            return datatables()->of($data)
                ->addColumn('name', function ($data) {
                    return ucwords(str_replace('_', ' ', $data->name));
                })
                ->addColumn('action', function ($data) {
                    return view('admin.unit_larges.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.unit_larges.index');
    }
    public function create()
    {
        $data = $this->unitLarges->get();
        return view('admin.unit_larges.create', compact('data'));
    }
    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:unitLargess,name',
        ]);

        try {
            $this->unitLarges->store($data);
            return redirect()->route('unit_larges.index')->with('success', 'Satuan Besar berhasil dibuat.');
        } catch (\Throwable $th) {
            return redirect()->route('error', 'Satuan Besar gagal dibuat: ' . $th->getMessage());
        }
    }

    public function show($id)
    {
        $data = $this->unitLarges->show($id);
        return view('admin.unit_larges.detail', compact('data'));
    }
    public function edit($id)
    {
        $data = $this->unitLarges->getById($id);
        return view('admin.unit_larges.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:unitLargess,name,' . $id,
        ]);

        try {
            $this->unitLarges->update($id, $data);
            return redirect()->route('unit_larges.index')->with('success', 'Satuan Besar berhasil diperbarui.');
        } catch (\Throwable $th) {
            return redirect()->route('error', 'Satuan Besar gagal diperbarui: ' . $th->getMessage());
        }
    }

    public function delete($id)
    {

        try {
            $this->unitLarges->delete($id);
            return redirect()->route('unit_larges.index')->with('success', 'Satuan Besar berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->route('error', 'Satuan Besar gagal dihapus: ' . $th->getMessage());
        }
    }
}
