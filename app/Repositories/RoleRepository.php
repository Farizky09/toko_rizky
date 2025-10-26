<?php

namespace App\Repositories;

use App\Interfaces\RoleInterfaces;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;


class RoleRepository implements RoleInterfaces
{

    private $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function get()
    {
        return $this->role->all();
    }

    public function getById($id)
    {
        return $this->role->find($id);
    }

    public function show()
    {
        return $this->role->get();
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $name = isset($data['name']) ? $data['name'] : null;
            if ($name !== null && strpos($name, '_') === false) {
                $name = str_replace(' ', '_', $name);
            }
            $data['name'] = $name;
            $role = $this->role->create($data);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        try {
            foreach ($data['permissions'] as $permission) {
                $role->givePermissionTo($permission);
            }
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
        DB::commit();
    }

    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $role = $this->role->find($id);
            $name = isset($data['name']) ? $data['name'] : null;
            if ($name !== null && strpos($name, '_') === false) {
                $name = str_replace(' ', '_', $name);
            }
            $data['name'] = $name;
            $role->update($data);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        try {
            $role->syncPermissions($data['permissions']);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $role = $this->role->find($id);
            $role->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function datatable()
    {
        return $this->role->orderBy('created_at', 'desc')->get();
    }
}
