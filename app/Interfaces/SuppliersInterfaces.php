<?php

namespace App\Interfaces;

interface SuppliersInterfaces
{
    public function get();
    public function getById($id);
    public function store($data);
    public function update($data, $id);
    public function delete($id);
    public function datatable();
}
