<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\PermissionInterfaces;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    private $permission;
    public function __construct(PermissionInterfaces $permission)
    {
        $this->permission = $permission;
    }

    public function index(Request $request)
    {
        // dd($this->permission->datatable());
        if ($request->ajax()) {
            $data = $this->permission->datatable();
            return datatables()->of($data)
                ->addColumn('name', function ($data) {
                    return ucwords(str_replace('_', ' ', $data->name));
                })
                ->addColumn('action', function ($data) {
                    return view('admin.permission.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.permission.index');
    }
    public function create()
    {
        $data = $this->permission->get();
        return view('admin.permission.create', compact('data'));
    }
    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        try {
            $this->permission->store($data);
            return redirect()->route('permission.index')->with('success', 'Permission berhasil dibuat.');
        } catch (\Throwable $th) {
            return redirect()->route('error', 'Permission gagal dibuat: ' . $th->getMessage());
        }
    }

    public function show($id)
    {
        $data = $this->permission->show($id);
        return view('admin.permission.detail', compact('data'));
    }
    public function edit($id)
    {
        $data = $this->permission->getById($id);
        return view('admin.permission.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        try {
            $this->permission->update($id, $data);
            return redirect()->route('permission.index')->with('success', 'Permission berhasil diperbarui.');
        } catch (\Throwable $th) {
            return redirect()->route('error', 'Permission gagal diperbarui: ' . $th->getMessage());
        }
    }

    public function delete($id)
    {
        // try {
        //     $this->permission->delete($id);
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'Permission berhasil dihapus.'
        //     ]);
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Permission gagal dihapus: ' . $th->getMessage()
        //     ]);
        // }
        try {
            $this->permission->delete($id);
            return redirect()->route('permission.index')->with('success', 'Permission berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->route('error', 'Permission gagal dihapus: ' . $th->getMessage());
        }
    }
}
