<?php

namespace App\Interfaces;

interface GoodReceiptsInterfaces
{
    public function get();
    public function getById($id);
    public function store($data);
    public function update($id, $data);
    public function delete($id);
    public function process($id);
    public function cancel($id);
    public function complete($id);
    public function datatable();
    public function generateGoodReceiptNumber();
}
