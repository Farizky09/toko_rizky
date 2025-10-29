<?php

namespace App\Repositories;

use App\Interfaces\LocationsInterfaces;
use App\Models\Locations;
use Exception;
use Illuminate\Support\Facades\DB;

class LocationsRepository implements LocationsInterfaces
{
    private $locations;

    public function __construct(Locations $locations)
    {
        $this->locations = $locations;
    }

    public function get()
    {
        return $this->locations->all();
    }

    public function getById($id)
    {
        return $this->locations->find($id);
    }


    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $locations = $this->locations->create($data);
            return $locations;
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $locations = $this->locations->findOrFail($id);
            if ($locations) {
                $locations->update($data);
                return $locations;
            }
            throw new Exception('Locations not found');
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $locations = $this->locations->findOrFail($id);
            if ($locations) {
                return $locations->delete();
            }
            throw new Exception('Locations not found');
        });
    }

    public function datatable()
    {
        return $this->locations->orderBy('created_at', 'desc')->get();
    }
}
