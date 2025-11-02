<?php

namespace App\Interfaces;

interface ProductsInterfaces
{
    public function get();
    public function getById($id);
    public function store($data);
    public function update($data, $id);
    public function delete($id);
    public function datatable();
    public function datatable2();
}
