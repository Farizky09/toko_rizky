<?php

namespace App\Interfaces;

interface UserManagementInterfaces
{
    public function get();
    public function getById($id);
    public function show();
    public function store($data);
    public function update($data, $id);
    public function updatePermission($data, $id);
    public function delete($id);
    public function datatable();
    public function registerUser($data);
    public function updateProfileUser($data, $id);
    public function changePassword($data, $id);
}
