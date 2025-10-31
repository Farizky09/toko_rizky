<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductsRequest;
use App\Interfaces\ProductsInterfaces;
use App\Models\Categories;
use App\Models\UnitLarges;
use App\Models\UnitSmalls;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    private $productsRepository;
    public function __construct(ProductsInterfaces $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->productsRepository->datatable();
            return datatables()->of($data)
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('code', function ($data) {
                    return $data->code;
                })
                ->addColumn('category_name', function ($data) {

                    return $data->category ? $data->category->name : '-';
                })
                ->addColumn('unitLarge_name', function ($data) {
                    return $data->unitLarge ? $data->unitLarge->name : '-';
                })
                ->addColumn('unitLarge_abbreviation', function ($data) {
                    return $data->unitLarge ? $data->unitLarge->abbreviation : '-';
                })
                ->addColumn('unitSmall_name', function ($data) {
                    return $data->unitSmall ? $data->unitSmall->name : '-';
                })
                ->addColumn('unitSmall_abbreviation', function ($data) {
                    return $data->unitSmall ? $data->unitSmall->abbreviation : '-';
                })
                ->addColumn('conversion', function ($data) {
                    return $data->conversion ?? '0';
                })
                ->addColumn('min_stock', function ($data) {
                    return $data->min_stock ?? '0';
                })
                ->addColumn('status', function ($data) {
                    return $data->status ?? 'active';
                })

                ->addColumn('action', function ($data) {
                    return view('admin.products.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.products.index');
    }
    public function create()
    {
        $categories = Categories::all();
        $unitLarge = UnitLarges::all();
        $unitSmall = UnitSmalls::all();
        return view('admin.products.create', compact('categories', 'unitLarge', 'unitSmall'));
    }
    public function store(ProductsRequest $request)
    {

        try {
            $this->productsRepository->store($request->validated());
            return redirect()
                ->route('products.index')
                ->with('success', 'Produk berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()
                ->route('products.create')
                ->with('error', 'Produk gagal dibuat: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = $this->productsRepository->getById($id);
        return view('admin.products.detail', compact('data'));
    }
    public function edit($id)
    {
        $data = $this->productsRepository->getById($id);
        $categories = Categories::all();
        $unitLarge = UnitLarges::all();
        $unitSmall = UnitSmalls::all();
        return view('admin.products.edit',  compact('data', 'categories', 'unitLarge', 'unitSmall'));
    }

    public function update($id, ProductsRequest $request)
    {

        try {
            $this->productsRepository->update($id, $request->validated());
            return redirect()
                ->route('products.index')
                ->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->route('products.edit', $id)
                ->with('error', 'Produk gagal diperbarui: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->productsRepository->delete($id);
            return redirect()
                ->route('products.index')
                ->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Produk gagal dihapus: ' . $e->getMessage());
        }
    }
}
