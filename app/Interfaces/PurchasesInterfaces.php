<?php

namespace App\Interfaces;

use App\Http\Requests\PurchasesRequest;

interface PurchasesInterfaces
{
    public function get();
    public function getById($id);
    public function store(PurchasesRequest $request);
    public function update(PurchasesRequest $request, $id);
    public function delete($id);
    public function generatePurchaseNumber();
    public function datatable();
}
