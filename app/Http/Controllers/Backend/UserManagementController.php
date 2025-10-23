<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\RoleInterfaces;
use App\Interfaces\UserManagementInterfaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    private $userManagement;
    private $role;

    public function __construct(UserManagementInterfaces $userManagement, RoleInterfaces $role)
    {
        $this->userManagement = $userManagement;
        $this->role = $role;
    }

    public function index(Request $request)
    {
        // dd($this->userManagement->datatable());
        if ($request->ajax()) {
            $data = $this->userManagement->datatable();
            return datatables()->of($data)
                ->addColumn('name', fn($data) => $data->name)
                ->addColumn('email', fn($data) => $data->email)
                ->addColumn('role', fn($data) => str_replace('_', ' ', $data->role  ?? 'Tidak ada role'))
                ->addColumn('action', function ($data) {
                    return view('admin.user_management.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        // return $data;
        return view('admin.user_management.index');
    }

    public function create()
    {
        $roles = $this->role->get();
        return view('admin.user_management.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:13',
            'role' => 'required|exists:roles,id',
        ]);

        try {
            $this->userManagement->store($data);
            return redirect()->route('user-management.index')->with('success', 'User berhasil dibuat.');
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->route('user-management.create')->with('error', 'User gagal dibuat: ' . $th->getMessage());
        }
    }
    public function show($id)
    {
        $data = $this->userManagement->show($id);
        return view('admin.user_management.detail', compact('data'));
    }
    public function edit($id)
    {
        $data = $this->userManagement->getById($id);
        $roles = $this->role->get();
        return view('admin.user_management.edit', compact('data', 'roles'));
    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:13',
            'role' => 'required|exists:roles,id',

        ]);

        try {
            $this->userManagement->update($data, $id);
            return redirect()->route('user-management.index')->with('success', 'User berhasil diperbarui.');
        } catch (\Throwable $th) {
            return redirect()->route('user-management.edit', $id)->with('error', 'User gagal diperbarui: ' . $th->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            $this->userManagement->delete($id);
            return redirect()->route('user-management.index')->with('success', 'User berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->route('user-management.index')->with('error', 'User gagal dihapus: ' . $th->getMessage());
        }
        // try {
        //     $this->userManagement->delete($id);
        //     return response()->json(['success' => 'User berhasil dihapus.']);
        // } catch (\Throwable $th) {
        //     return response()->json(['error' => 'User gagal dihapus: ' . $th->getMessage()], 500);
        // }
    }
    public function updatePermission(Request $request, $id)
    {
        $data = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            $this->userManagement->updatePermission($data, $id);
            return redirect()->route('user-management.index')->with('success', 'Permissions berhasil diperbarui.');
        } catch (\Throwable $th) {
            return redirect()->route('user-management.edit', $id)->with('error', 'Permissions gagal diperbarui: ' . $th->getMessage());
        }
    }
    public function resetPassword($id)
    {
        $user = $this->userManagement->getById($id);
        try {
            $user->password = Hash::make('password');
            $user->save();
            return response()->json(['success' => 'Password berhasil direset.']);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['error' => 'Password gagal direset: ' . $th->getMessage()], 500);
        }
    }
}
