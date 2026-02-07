<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitLargesRequest;
use App\Interfaces\UnitLargesInterfaces;
use Illuminate\Http\Request;

class UnitLargesController extends Controller
{
    private $untiLargesRepository;
    public function __construct(UnitLargesInterfaces $untiLargesRepository)
    {
        $this->untiLargesRepository = $untiLargesRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->untiLargesRepository->datatable();
            return datatables()->of($data)
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('abbreviation', function ($data) {
                    return $data->abbreviation;
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
        $data = $this->untiLargesRepository->get();
        return view('admin.unit_larges.create', compact('data'));
    }
    public function store(UnitLargesRequest $request)
    {

        try {
            $this->untiLargesRepository->store($request->validated());
            return redirect()
                ->route('unit-larges.index')
                ->with('success', 'Satuan Besar berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()
                ->route('unit-larges.create')
                ->with('error', 'Satuan Besar gagal dibuat: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = $this->untiLargesRepository->getById($id);
        return view('admin.unit_larges.detail', compact('data'));
    }
    public function edit($id)
    {
        $data = $this->untiLargesRepository->getById($id);
        return view('admin.unit_larges.edit', compact('data'));
    }

    public function update($id, UnitLargesRequest $request)
    {

        try {
            $this->untiLargesRepository->update($id, $request->validated());
            return redirect()
                ->route('unit-larges.index')
                ->with('success', 'Satuan Besar berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->route('unit-larges.edit', $id)
                ->with('error', 'Satuan Besar gagal diperbarui: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {

        try {
            $this->untiLargesRepository->delete($id);
            return redirect()
                ->route('unit-larges.index')
                ->with('success', 'Satuan Besar berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('unit-larges.index')
                ->with('error', 'Satuan Besar gagal dihapus: ' . $e->getMessage());
        }
    }
}
