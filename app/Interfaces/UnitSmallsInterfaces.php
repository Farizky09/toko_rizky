<?php

namespace App\Interfaces;

interface UnitSmallsInterfaces
{
    public function get();
    public function getById($id);
    public function store($data);
    public function show();
    public function update($data, $id);
    public function delete($id);
    public function datatable();
}
