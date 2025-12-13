<?php

namespace App\Repositories;

use App\Interfaces\GoodReceiptsInterfaces;
use App\Models\GoodReceipt;
use Illuminate\Support\Facades\DB;

class GoodReceiptsRepostiory implements GoodReceiptsInterfaces
{
    private $goodReceipt;

    public function __construct(GoodReceipt $goodReceipt)
    {
        $this->goodReceipt = $goodReceipt;
    }

    public function get()
    {
        return $this->goodReceipt->all();
    }

    public function getById($id)
    {
        return $this->goodReceipt->find($id);
    }

    public function store($data)
    {
        return DB::transaction(function () use ($data) {});
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {});
    }


    public function delete($id)
    {
        return DB::transaction(function () use ($id) {});
    }
}
