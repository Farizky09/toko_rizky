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

    public function show()
    {
        return $this->categories->get();
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $categories = $this->categories->create($data);
            DB::commit();
            return $categories;
        } catch (Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function update($id, $data)
    {
        DB::beginTransaction();
        try {
            $categories = $this->categories->find($id);
            $categories->update($data);
            DB::commit();
            return $categories;
        } catch (Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $categories = $this->categories->find($id);
            if ($categories) {
                $result = $categories->delete();
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
        return $this->categories->orderBy('created_at', 'desc')->get();
    }
}
