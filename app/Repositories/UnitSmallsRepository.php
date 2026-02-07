<?php

namespace App\Repositories;

use App\Interfaces\UnitSmallsInterfaces;
use App\Models\UnitSmalls;
use Exception;
use Illuminate\Support\Facades\DB;

class UnitSmallsRepository implements UnitSmallsInterfaces
{
    private $unitSmalls;

    public function __construct(UnitSmalls $unitSmalls)
    {
        $this->unitSmalls = $unitSmalls;
    }

    public function get()
    {
        return $this->unitSmalls->all();
    }

    public function getById($id)
    {
        return $this->unitSmalls->find($id);
    }

    public function show()
    {
        return $this->unitSmalls->get();
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $unitSmalls = $this->unitSmalls->create($data);
            DB::commit();
            return $unitSmalls;
        } catch (Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function update($id, $data)
    {
        DB::beginTransaction();
        try {
            $unitSmalls = $this->unitSmalls->find($id);
            $unitSmalls->update($data);
            DB::commit();
            return $unitSmalls;
        } catch (Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $unitSmalls = $this->unitSmalls->find($id);
            if ($unitSmalls) {
                $result = $unitSmalls->delete();
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
        return $this->unitSmalls->orderBy('created_at', 'desc')->get();
    }
}
