<?php

namespace App\Repositories;

use App\Interfaces\ProductsInterfaces;
use App\Models\Products;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductsRepository implements ProductsInterfaces
{
    private $products;

    public function __construct(Products $products)
    {
        $this->products = $products;
    }

    public function get()
    {
        return $this->products->all();
    }

    public function getById($id)
    {
        return $this->products->find($id);
    }


    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $products = $this->products->create($data);
            return $products;
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $products = $this->products->findOrFail($id);
            if ($products) {
                $products->update($data);
                return $products;
            }
            throw new Exception('Products not found');
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $products = $this->products->findOrFail($id);
            if ($products) {
                return $products->delete();
            }
            throw new Exception('Products not found');
        });
    }

    public function datatable()
    {
        return $this->products->orderBy('created_at', 'desc')->get();
    }
}
