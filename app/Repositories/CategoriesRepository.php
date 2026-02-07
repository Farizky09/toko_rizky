<?php

namespace App\Repositories;

use App\Interfaces\CategoriesInterfaces;
use App\Models\Categories;
use Exception;
use Illuminate\Support\Facades\DB;


class CategoriesRepository implements CategoriesInterfaces
{
    private $categories;

    public function __construct(Categories $categories)
    {
        $this->categories = $categories;
    }

    public function get()
    {
        return $this->categories->all();
    }

    public function getById($id)
    {
        return $this->categories->find($id);
    }


    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $categories = $this->categories->create($data);
            return $categories;
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $categories = $this->categories->findOrFail($id);
            if ($categories) {
                $categories->update($data);
                return $categories;
            }
            throw new Exception('Categories not found');
        });
    }


    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $categories = $this->categories->findOrFail($id);
            if ($categories) {
                return $categories->delete();
            }
            throw new Exception('Categories not found');
        });
    }

    public function datatable()
    {
        return $this->categories->orderBy('created_at', 'desc')->get();
    }
}
