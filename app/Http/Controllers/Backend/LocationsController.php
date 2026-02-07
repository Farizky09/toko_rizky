<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationsRequest;
use App\Interfaces\LocationsInterfaces;
use App\Models\Branches;
use Illuminate\Http\Request;


class LocationsController extends Controller
{
    private $locationsRepository;
    public function __construct(LocationsInterfaces $locationsRepository)
    {
        $this->locationsRepository = $locationsRepository;
    }

    public function index(Request $request)
    {
        // dd($this->locationsRepository->datatable());
        if ($request->ajax()) {
            $data = $this->locationsRepository->datatable();
            return datatables()->of($data)
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('type', function ($data) {
                    return $data->type === 'warehouse' ? 'Gudang' : 'Toko';
                })
                ->addColumn('status', function ($data) {
                    return $data->status; // 'active' atau 'inactive'
                })
                ->addColumn('action', function ($data) {
                    return view('admin.locations.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.locations.index');
    }
    public function create()
    {
        $branches = Branches::all();
        return view('admin.locations.create', compact('branches'));
    }
    public function store(LocationsRequest $request)
    {
        try {
            $this->locationsRepository->store($request->validated());
            return redirect()
                ->route('locations.index')
                ->with('success', 'Lokasi berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()
                ->route('locations.create')
                ->with('error', 'Lokasi gagal dibuat: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = $this->locationsRepository->getById($id);
        return view('admin.locations.detail', compact('data'));
    }
    public function edit($id)
    {
        $data = $this->locationsRepository->getById($id);
        return view('admin.locations.edit', compact('data'));
    }

    public function update($id, LocationsRequest $request)
    {

        try {
            $this->locationsRepository->update($id, $request->validated());
            return redirect()
                ->route('locations.index')
                ->with('success', 'Lokasi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->route('locations.edit', $id)
                ->with('error', 'Lokasi gagal diperbarui: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->locationsRepository->delete($id);
            return redirect()
                ->route('locations.index')
                ->with('success', 'Lokasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('locations.index')
                ->with('error', 'Lokasi gagal dihapus: ' . $e->getMessage());
        }
    }
}
