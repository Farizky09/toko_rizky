<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductsRequest;
use App\Interfaces\ProductsInterfaces;
use App\Models\Categories;
use App\Models\Locations;
use App\Models\Products;
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

        // dd($this->productsRepository->datatable2()->get());
        if ($request->ajax()) {
            $data = $this->productsRepository->datatable();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('code', function ($data) {
                    return $data->code;
                })
                ->addColumn('category_name', function ($data) {
                    return $data->category_name ?? '-';
                })
                ->addColumn('unitLarge_name', function ($data) {
                    return $data->unitLarge_name ?? '-';
                })
                ->addColumn('unitLarge_abbreviation', function ($data) {
                    return $data->unitLarge_abbreviation ?? '-';
                })
                ->addColumn('unitSmall_name', function ($data) {
                    return $data->unitSmall_name ?? '-';
                })
                ->addColumn('unitSmall_abbreviation', function ($data) {
                    return $data->unitSmall_abbreviation ?? '-';
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
                ->addColumn('stock_large', function ($data) {
                    return $data->stock_large ?? 0;
                })
                ->addColumn('stock_small', function ($data) {
                    return $data->stock_small ?? 0;
                })
                ->addColumn('total_stock_small', function ($data) {
                    return intval($data->total_stock_small ?? 0);
                })
                ->addColumn('action', function ($data) {
                    return view('admin.products.column.action', compact('data'));
                })
                ->make(true);
        }

        $locations = Locations::all();
        $categories = Categories::all();
        $unitSmalls = UnitSmalls::all();
        $unitLarges = UnitLarges::all();

        return view('admin.products.index', compact('locations', 'categories', 'unitSmalls', 'unitLarges'));
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
        try {
            $product = Products::with(['category', 'unitLarge', 'unitSmall'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'description' => $product->description,
                    'category_name' => $product->category ? $product->category->name : '-',
                    'unitLarge_name' => $product->unitLarge ? $product->unitLarge->name : '-',
                    'unitLarge_abbreviation' => $product->unitLarge ? $product->unitLarge->abbreviation : '-',
                    'unitSmall_name' => $product->unitSmall ? $product->unitSmall->name : '-',
                    'unitSmall_abbreviation' => $product->unitSmall ? $product->unitSmall->abbreviation : '-',
                    'conversion' => $product->conversion,
                    'min_stock' => $product->min_stock,
                    'status' => $product->status,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
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
