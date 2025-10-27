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

    public function show()
    {
        return $this->unitLarges->get();
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $unitLarges = $this->unitLarges->create($data);
            DB::commit();
            return $unitLarges;
        } catch (Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function update($id, $data)
    {
        DB::beginTransaction();
        try {
            $unitLarges = $this->unitLarges->find($id);
            $unitLarges->update($data);
            DB::commit();
            return $unitLarges;
        } catch (Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $unitLarges = $this->unitLarges->find($id);
            if ($unitLarges) {
                $result = $unitLarges->delete();
                DB::commit();
                return $result;
            }
            DB::rollBack();
            return false;
        } catch (Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public function datatable()
    {
        return $this->unitLarges->orderBy('created_at', 'desc')->get();
    }
}
