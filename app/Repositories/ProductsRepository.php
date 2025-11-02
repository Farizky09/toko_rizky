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
        $locationId = request('location_id');
        return DB::table('products')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('unit_larges', 'unit_larges.id', '=', 'products.unit_large_id')
            ->join('unit_smalls', 'unit_smalls.id', '=', 'products.unit_small_id')
            ->leftJoin('batches', 'batches.product_id', '=', 'products.id')
            ->leftJoin('batch_locations', 'batch_locations.batch_id', '=', 'batches.id')
            ->leftJoin('locations', 'locations.id', '=', 'batch_locations.location_id')
            ->leftJoin('branches', 'branches.id', '=', 'locations.branch_id')
            ->select(
                'products.id',
                'products.code',
                'products.name',
                'products.description',
                'products.conversion',
                'products.min_stock',
                'products.status',
                'categories.name as category_name',
                'unit_larges.name as unitLarge_name',
                'unit_larges.abbreviation as unitLarge_abbreviation',
                'unit_smalls.name as unitSmall_name',
                'unit_smalls.abbreviation as unitSmall_abbreviation',

                DB::raw('COALESCE(SUM(CASE WHEN ' . ($locationId ? "locations.id = {$locationId}" : '1=1') . ' THEN batch_locations.quantity_large ELSE 0 END), 0) as stock_large'),

                DB::raw('COALESCE(SUM(CASE WHEN ' . ($locationId ? "locations.id = {$locationId}" : '1=1') . ' THEN batch_locations.quantity_small ELSE 0 END), 0) as stock_small'),

                DB::raw('COALESCE(SUM(CASE WHEN ' . ($locationId ? "locations.id = {$locationId}" : '1=1') . ' THEN (batch_locations.quantity_large * products.conversion + batch_locations.quantity_small) ELSE 0 END), 0) as total_stock_small')
            )
            ->when(request('category_id'), function ($query) {
                return $query->where('products.category_id', request('category_id'));
            })
            ->when(request('status'), function ($query) {
                return $query->where('products.status', request('status'));
            })
            ->when(request('unit_small_id'), function ($query) {
                return $query->where('products.unit_small_id', request('unit_small_id'));
            })
            ->when(request('unit_large_id'), function ($query) {
                return $query->where('products.unit_large_id', request('unit_large_id'));
            })
            ->groupBy(
                'products.id',
                'products.code',
                'products.name',
                'products.description',
                'products.conversion',
                'products.min_stock',
                'products.status',
                'categories.name',
                'unit_larges.name',
                'unit_larges.abbreviation',
                'unit_smalls.name',
                'unit_smalls.abbreviation'
            )
            ->orderBy('products.created_at', 'desc');
    }
}
