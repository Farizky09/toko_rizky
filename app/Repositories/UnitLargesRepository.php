<?php

namespace App\Repositories;

use App\Interfaces\UnitLargesInterfaces;
use App\Models\UnitLarges;
use Exception;
use Illuminate\Support\Facades\DB;

class UnitLargesRepository implements UnitLargesInterfaces
{
    private $unitLarges;

    public function __construct(UnitLarges $unitLarges)
    {
        $this->unitLarges = $unitLarges;
    }

    public function get()
    {
        return $this->unitLarges->all();
    }

    public function getById($id)
    {
        return $this->unitLarges->find($id);
    }


    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $unitLarges = $this->unitLarges->create($data);
            return $unitLarges;
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $unitLarges = $this->unitLarges->findOrFail($id);
            if ($unitLarges) {
                $unitLarges->update($data);
                return $unitLarges;
            }
            throw new Exception('Unit Larges not found');
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $unitLarges = $this->unitLarges->findOrFail($id);
            if ($unitLarges) {
                return $unitLarges->delete();
            }
            throw new Exception('Unit Larges not found');
        });
    }

    public function datatable()
    {
        return $this->unitLarges->orderBy('created_at', 'desc')->get();
    }
}
