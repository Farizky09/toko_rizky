<?php

namespace App\Repositories;

use App\Interfaces\UserManagementInterfaces;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Contracts\Role;


class UserManagementRepository implements UserManagementInterfaces
{
    private $user;
    private $role;
    private $permission;


    public function __construct(User $user, Role $role, Permission $permission)
    {
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
    }

    public function get()
    {
        return $this->user->orderBy('id', 'desc')->get();
    }

    public function getById($id)
    {
        return $this->user->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $pass = Hash::make('password');
            $user = $this->user->create(array_merge($data, ['password' => $pass]));
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }

        try {
            $role = $this->role->find($data['role']);
            $user->assignRole($role);
        } catch (\Throwable $th) {
            throw $th;
        }
        DB::commit();
    }

    public function show()
    {
        return $this->user->all();
    }
    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->user->find($id);
            if (!empty($data)) {
                $filtered = collect($data)->except('role')->toArray();
                if (!empty($filtered)) {
                    $user->update($filtered);
                }
            }
            if (isset($data['role'])) {
                $role = $this->role->find($data['role']);
                $user->syncRoles([$role]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();
    }

    public function updatePermission($data, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->user->find($id);
            $user->syncPermissions($data['permission']);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
        DB::commit();
    }
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $user = $this->user->find($id);
            if ($user) {
                $user->delete();
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function datatable()
    {
        return DB::table('users')
            ->join('model_has_roles', function ($join) {
                $join->on('users.id', '=', 'model_has_roles.model_id')
                    ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
            })
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                'roles.name as role',
            ]);
    }

    public function registerUser($data)
    {
        DB::beginTransaction();
        try {
            $user = $this->user->create([
                'name' => $data['name'],
                'username' => explode(' ', $data['name'])[0] . rand(1000, 9999),
                'email' => $data['email'],
                'phone_number' => $data['phone_number'],
                'password' => Hash::make($data['password']),
                'address' => $data['address'] ?? null,
                'birthdate' => $data['birthdate'] ?? null,
                'is_active' => true,
                'last_active_at' => null,
                'points' => 50,
                'profile_photo' => null,
            ]);
            $user->assignRole('user');
            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }

    public function updateProfileUser($data, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->user->findOrFail($id);
            $user->update([
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
                'username' => $data['username'] ?? $user->username,
                'phone_number' => $data['phone_number'] ?? $user->phone_number,
                'address' => $data['address'] ?? $user->address,
                'birthdate' => $data['birthdate'] ?? $user->birthdate,
            ]);
            if (isset($data['profile_photo'])) {
                $date = Carbon::now();
                $folderName = $date->format('j-n-Y');
                $folderPath = "profilePhotos/{$user->id}/{$folderName}";
                if (!Storage::disk('public')->exists($folderPath)) {
                    Storage::disk('public')->makeDirectory($folderPath);
                }
                $filename = 'img_' . time() . '_' . uniqid() . '.' . $data['profile_photo']->getClientOriginalExtension();
                $path = $data['profile_photo']->storeAs($folderPath, $filename, 'public');
                if (!$path || !Storage::disk('public')->exists($path)) {
                    throw new Exception("Gagal menyimpan file gambar");
                }
                if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $user->profile_photo = $path;
                $user->save();
            }
            DB::commit();
            return $user->fresh();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function changePassword($data, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->user->findOrFail($id);
            $user->update([
                'password' => Hash::make($data['password']),
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
