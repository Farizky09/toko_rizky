<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuppliersRequest;
use App\Interfaces\SuppliersInterfaces;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    private $suppliersRepository;
    public function __construct(SuppliersInterfaces $suppliersRepository)
    {
        $this->suppliersRepository = $suppliersRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->suppliersRepository->datatable();
            return datatables()->of($data)
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('address', function ($data) {
                    return $data->address;
                })
                ->addColumn('phone', function ($data) {
                    return $data->phone;
                })
                ->addColumn('action', function ($data) {
                    return view('admin.suppliers.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.suppliers.index');
    }
    public function create()
    {
        $data = $this->suppliersRepository->get();
        return view('admin.suppliers.create', compact('data'));
    }
    public function store(SuppliersRequest $request)
    {

        try {
            $this->suppliersRepository->store($request->validated());
            return redirect()
                ->route('suppliers.index')
                ->with('success', 'Suppliers berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()
                ->route('suppliers.create')
                ->with('error', 'Suppliers gagal dibuat: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = $this->suppliersRepository->getById($id);
        return view('admin.suppliers.detail', compact('data'));
    }
    public function edit($id)
    {
        $data = $this->suppliersRepository->getById($id);
        return view('admin.suppliers.edit', compact('data'));
    }

    public function update($id, SuppliersRequest $request)
    {

        try {
            $this->suppliersRepository->update($id, $request->validated());
            return redirect()
                ->route('suppliers.index')
                ->with('success', 'Suppliers berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->route('suppliers.edit', $id)
                ->with('error', 'Suppliers gagal diperbarui: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {

        try {
            $this->suppliersRepository->delete($id);
            return redirect()
                ->route('suppliers.index')
                ->with('success', 'Suppliers berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('suppliers.index')
                ->with('error', 'Suppliers gagal dihapus: ' . $e->getMessage());
        }
    }
}
