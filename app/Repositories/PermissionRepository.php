<?php

namespace App\Repositories;

use App\Interfaces\PermissionInterfaces;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionRepository implements PermissionInterfaces
{
    private $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function get()
    {
        return $this->permission->all();
    }

    public function getById($id)
    {
        return $this->permission->find($id);
    }

    public function show()
    {
        return $this->permission->get();
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
            $permission = $this->permission->create($data);
            DB::commit();
            return $permission;
        } catch (Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function update($id, $data)
    {
        DB::beginTransaction();
        try {
            $permission = $this->permission->find($id);
            $name = isset($data['name']) ? $data['name'] : null;
            if ($name !== null && strpos($name, '_') === false) {
                $name = str_replace(' ', '_', $name);
            }
            $data['name'] = $name;
            if ($permission) {
                $permission->update($data);
                DB::commit();
                return $permission;
            }
            DB::rollBack();
            return null;
        } catch (Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $permission = $this->permission->find($id);
            if ($permission) {
                $result = $permission->delete();
                DB::commit();
                return $result;
            }
            DB::rollBack();
            return false;
        } catch (Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public function datatable()
    {
        return $this->permission->orderBy('created_at', 'desc')->get();
    }
}
