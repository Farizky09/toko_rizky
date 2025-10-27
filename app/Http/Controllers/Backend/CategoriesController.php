<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\CategoriesInterfaces;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    private $categories;
    public function __construct(CategoriesInterfaces $categories)
    {
        $this->categories = $categories;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->categories->datatable();
            return datatables()->of($data)
                ->addColumn('name', function ($data) {
                    return ucwords(str_replace('_', ' ', $data->name));
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
        $data = $this->categories->get();
        return view('admin.categories.create', compact('data'));
    }
    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categoriess,name',
        ]);

        try {
            $this->categories->store($data);
            return redirect()->route('categories.index')->with('success', 'kategori berhasil dibuat.');
        } catch (\Throwable $th) {
            return redirect()->route('error', 'kategori gagal dibuat: ' . $th->getMessage());
        }
    }

    public function show($id)
    {
        $data = $this->categories->show($id);
        return view('admin.categories.detail', compact('data'));
    }
    public function edit($id)
    {
        $data = $this->categories->getById($id);
        return view('admin.categories.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categoriess,name,' . $id,
        ]);

        try {
            $this->categories->update($id, $data);
            return redirect()->route('categories.index')->with('success', 'kategori berhasil diperbarui.');
        } catch (\Throwable $th) {
            return redirect()->route('error', 'kategori gagal diperbarui: ' . $th->getMessage());
        }
    }

    public function delete($id)
    {

        try {
            $this->categories->delete($id);
            return redirect()->route('categories.index')->with('success', 'kategori berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->route('error', 'kategori gagal dihapus: ' . $th->getMessage());
        }
    }
}
