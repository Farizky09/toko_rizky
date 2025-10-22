<?php

namespace App\Http\Controllers\Backend\UserManagement;

use App\Http\Controllers\Controller;
use App\Interfaces\PermissionInterfaces;
use App\Interfaces\RoleInterfaces;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $role;
    private $permission;
    public function __construct(RoleInterfaces $role, PermissionInterfaces $permission)
    {
        $this->role = $role;
        $this->permission = $permission;
    }

    public function index(Request $request)
    {
        // dd($this->role->get());
        if ($request->ajax()) {
            $data = $this->role->datatable();
            return datatables()->of($data)
                ->addColumn('name', fn($data) => $data->name)
                ->addColumn('action', function ($data) {
                    return view('admin.role.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.role.index');
    }

    public function create()
    {
        $permissions = $this->permission->get();
        return view('admin.role.create', compact('permissions'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array',
        ]);
        try {
            $this->role->store($data);
            return redirect()->route('role.index')->with('success', 'Role berhasil dibuat.');
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->route('role.create')->with('error', 'Role gagal dibuat: ' . $th->getMessage());
        }
    }

    public function show($id)
    {
        $data = $this->role->show($id);
        return view('admin.role.detail', compact('data'));
    }

    public function edit($id)
    {
        $data = $this->role->getById($id);
        $permissions = $this->permission->get();
        return view('admin.role.edit', compact('data', 'permissions'));
    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'required|array',
        ]);
        try {
            $this->role->update($data, $id);
            return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui.');
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->route('role.edit', $id)->with('error', 'Role gagal diperbarui: ' . $th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->role->delete($id);
            return redirect()->route('role.index')->with('success', 'Role berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->route('role.index')->with('error', 'Role gagal dihapus: ' . $th->getMessage());
        }
        // try {
        //     $this->role->delete($id);
        //     return response()->json(['success' => 'Role berhasil dihapus.']);
        // } catch (\Throwable $th) {
        //     return response()->json(['error' => 'Role gagal dihapus: ' . $th->getMessage()], 500);
        // }
    }
}
