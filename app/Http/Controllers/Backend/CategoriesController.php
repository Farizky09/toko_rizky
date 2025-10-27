<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Interfaces\CategoriesInterfaces;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    private $categoriesRepository;
    public function __construct(CategoriesInterfaces $categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->categoriesRepository->datatable();
            return datatables()->of($data)
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($data) {
                    return view('admin.categories.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.categories.index');
    }
    public function create()
    {
        $data = $this->categoriesRepository->get();
        return view('admin.categories.create', compact('data'));
    }
    public function store(CategoryRequest $request)
    {

        try {
            $this->categoriesRepository->store($request->validated());
            return redirect()
                ->route('categories.index')
                ->with('success', 'Kategori berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()
                ->route('categories.create')
                ->with('error', 'Kategori gagal dibuat: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = $this->categoriesRepository->getById($id);
        return view('admin.categories.detail', compact('data'));
    }
    public function edit($id)
    {
        $data = $this->categoriesRepository->getById($id);
        return view('admin.categories.edit', compact('data'));
    }

    public function update($id, CategoryRequest $request)
    {


        try {
            $this->categoriesRepository->update($id, $request->validated());
            return redirect()
                ->route('categories.index')
                ->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->route('categories.edit', $id)
                ->with('error', 'Kategori gagal diperbarui: ' . $e->getMessage());
        }
    }


    public function delete($id)
    {
        try {
            $this->categoriesRepository->delete($id);
            return redirect()
                ->route('categories.index')
                ->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Kategori gagal dihapus: ' . $e->getMessage());
        }
    }
}
