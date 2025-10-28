<?php

namespace App\Repositories;

use App\Interfaces\SuppliersInterfaces;
use App\Models\Suppliers;
use Exception;
use Illuminate\Support\Facades\DB;

class SuppliersRepository implements SuppliersInterfaces
{
    private $suppliers;

    public function __construct(Suppliers $suppliers)
    {
        $this->suppliers = $suppliers;
    }

    public function get()
    {
        return $this->suppliers->all();
    }

    public function getById($id)
    {
        return $this->suppliers->find($id);
    }


    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $suppliers = $this->suppliers->create($data);
            return $suppliers;
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $suppliers = $this->suppliers->findOrFail($id);
            if ($suppliers) {
                $suppliers->update($data);
                return $suppliers;
            }
            throw new Exception('Suppliers not found');
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $suppliers = $this->suppliers->findOrFail($id);
            if ($suppliers) {
                return $suppliers->delete();
            }
            throw new Exception('Suppliers not found');
        });
    }

    public function datatable()
    {
        return $this->suppliers->orderBy('created_at', 'desc')->get();
    }
}
