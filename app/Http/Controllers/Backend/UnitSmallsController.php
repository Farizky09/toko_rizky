<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\UnitSmallsInterfaces;
use Illuminate\Http\Request;

class UnitSmallsController extends Controller
{
    private $unitSmalls;
    public function __construct(UnitSmallsInterfaces $unitSmalls)
    {
        $this->unitSmalls = $unitSmalls;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->unitSmalls->datatable();
            return datatables()->of($data)
                ->addColumn('name', function ($data) {
                    return ucwords(str_replace('_', ' ', $data->name));
                })
                ->addColumn('action', function ($data) {
                    return view('admin.unit_smalls.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.unit_smalls.index');
    }
    public function create()
    {
        $data = $this->unitSmalls->get();
        return view('admin.unit_smalls.create', compact('data'));
    }
    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:unitSmallss,name',
        ]);

        try {
            $this->unitSmalls->store($data);
            return redirect()->route('unit-smalls.index')->with('success', 'Satuan Kecil berhasil dibuat.');
        } catch (\Throwable $th) {
            return redirect()->route('error', 'Satuan Kecil gagal dibuat: ' . $th->getMessage());
        }
    }

    public function show($id)
    {
        $data = $this->unitSmalls->show($id);
        return view('admin.unit_smalls.detail', compact('data'));
    }
    public function edit($id)
    {
        $data = $this->unitSmalls->getById($id);
        return view('admin.unit_smalls.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:unitSmallss,name,' . $id,
        ]);

        try {
            $this->unitSmalls->update($id, $data);
            return redirect()->route('unit-smalls.index')->with('success', 'Satuan Kecil berhasil diperbarui.');
        } catch (\Throwable $th) {
            return redirect()->route('error', 'Satuan Kecil gagal diperbarui: ' . $th->getMessage());
        }
    }

    public function delete($id)
    {

        try {
            $this->unitSmalls->delete($id);
            return redirect()->route('unit-smalls.index')->with('success', 'Satuan Kecil berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->route('error', 'Satuan Kecil gagal dihapus: ' . $th->getMessage());
        }
    }
}
