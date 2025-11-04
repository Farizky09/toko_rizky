<?php

namespace App\Interfaces;

use App\Http\Requests\PurchasesRequest;

interface PurchasesInterfaces
{
    public function get();
    public function getById($id);
    public function store($data);
    public function update($data, $id);
    public function delete($id);
    public function generatePurchaseNumber();
    public function datatable();
    public function receivePurchase($id, $receivedData);
    public function cancel($id);
}
