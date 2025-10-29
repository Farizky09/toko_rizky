<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchesRequest;
use App\Interfaces\BranchesInterfaces;
use Illuminate\Http\Request;

class BranchesController extends Controller
{
    private $branchesRepository;
    public function __construct(BranchesInterfaces $branchesRepository)
    {
        $this->branchesRepository = $branchesRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->branchesRepository->datatable();
            return datatables()->of($data)
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('code', function ($data) {
                    return $data->code;
                })
                ->addColumn('city', function ($data) {
                    return $data->city;
                })
                ->addColumn('province', function ($data) {
                    return $data->province;
                })
                ->addColumn('status', function ($data) {
                    return $data->status;
                })
                ->addColumn('action', function ($data) {
                    return view('admin.branches.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.branches.index');
    }
    public function create()
    {
        $data = $this->branchesRepository->get();
        return view('admin.branches.create', compact('data'));
    }
    public function store(BranchesRequest $request)
    {

        try {
            $this->branchesRepository->store($request->validated());
            return redirect()
                ->route('branches.index')
                ->with('success', 'Cabang berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()
                ->route('branches.create')
                ->with('error', 'Cabang gagal dibuat: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = $this->branchesRepository->getById($id);
        return view('admin.branches.detail', compact('data'));
    }
    public function edit($id)
    {
        $data = $this->branchesRepository->getById($id);
        return view('admin.branches.edit', compact('data'));
    }

    public function update($id, BranchesRequest $request)
    {

        try {
            $this->branchesRepository->update($id, $request->validated());
            return redirect()
                ->route('branches.index')
                ->with('success', 'Cabang berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->route('branches.edit', $id)
                ->with('error', 'Cabang gagal diperbarui: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->branchesRepository->delete($id);
            return redirect()
                ->route('branches.index')
                ->with('success', 'Cabang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('branches.index')
                ->with('error', 'Cabang gagal dihapus: ' . $e->getMessage());
        }
    }
}
