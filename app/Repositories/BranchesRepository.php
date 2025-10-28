<?php

namespace App\Repositories;

use App\Interfaces\BranchesInterfaces;
use App\Models\Branches;
use Exception;
use Illuminate\Support\Facades\DB;

class BranchesRepository implements BranchesInterfaces
{
    private $branches;

    public function __construct(Branches $branches)
    {
        $this->branches = $branches;
    }

    public function get()
    {
        return $this->branches->all();
    }

    public function getById($id)
    {
        return $this->branches->find($id);
    }


    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $branches = $this->branches->create($data);
            return $branches;
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $branches = $this->branches->findOrFail($id);
            if ($branches) {
                $branches->update($data);
                return $branches;
            }
            throw new Exception('Branches not found');
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $branches = $this->branches->findOrFail($id);
            if ($branches) {
                return $branches->delete();
            }
            throw new Exception('Branches not found');
        });
    }

    public function datatable()
    {
        return $this->branches->orderBy('created_at', 'desc')->get();
    }
}
