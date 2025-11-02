<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchasesRequest;
use App\Interfaces\PurchasesInterfaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchasesController extends Controller
{
    private $purchasesRepository;
    public function __construct(PurchasesInterfaces $purchasesRepository)
    {
        $this->purchasesRepository = $purchasesRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->purchasesRepository->datatable();
            return datatables()->of($data)
                ->addColumn('purchase_number', function ($data) {
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">' . $data->purchase_number . '</span>';
                })
                ->addColumn('supplier_name', function ($data) {
                    return $data->supplier_name ?? '-';
                })
                ->addColumn('branch_name', function ($data) {
                    return $data->branch_name ?? '-';
                })
                ->addColumn('purchase_date', function ($data) {
                    return date('d/m/Y', strtotime($data->purchase_date));
                })
                ->addColumn('total_quantity', function ($data) {
                    $total = ($data->total_quantity_large ?? 0) + ($data->total_quantity_small ?? 0);
                    return '<span class="font-medium">' . $total . '</span>';
                })
                ->addColumn('total_amount', function ($data) {
                    return 'Rp ' . number_format($data->total_amount, 0, ',', '.');
                })
                ->addColumn('status', function ($data) {
                    $statusConfig = [
                        'draft' => ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'mdi-pencil', 'label' => 'Draft'],
                        'completed' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'mdi-check', 'label' => 'Completed'],
                        'cancelled' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'mdi-close', 'label' => 'Cancelled']
                    ];

                    $config = $statusConfig[$data->status] ?? $statusConfig['draft'];
                    return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium ' . $config['class'] . '">
                            <span class="mdi ' . $config['icon'] . '"></span>
                            ' . $config['label'] . '
                        </span>';
                })
                ->addColumn('user_name', function ($data) {
                    return $data->user_name ?? '-';
                })
                ->addColumn('action', function ($data) {
                    return view('admin.purchases.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->rawColumns(['purchase_number', 'total_quantity', 'total_amount', 'status', 'action'])
                ->make(true);
        }
        return view('admin.purchases.index');
    }
    public function create()
    {
        $purchaseNumber = $this->purchasesRepository->generatePurchaseNumber();
        $branches = DB::table('branches')->where('status', 'active')->get();
        $locations = DB::table('locations')->get();
        $suppliers = DB::table('suppliers')->get();
        $products = DB::table('products')->where('status', 'active')->get();
        return view('admin.purchases.create', compact(
            'purchaseNumber',
            'branches',
            'locations',
            'suppliers',
            'products',

        ));
    }
    public function store(PurchasesRequest $request)
    {
        try {
            $purchase = $this->purchasesRepository->store($request->validated());
            // dd($purchase);

            return redirect()
                ->route('purchases.index')
                ->with('success', 'Pembelian berhasil dibuat.');
        } catch (\Exception $e) {

            return redirect()
                ->route('purchases.create')
                ->with('error', 'Pembelian gagal dibuat: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $purchase = $this->purchasesRepository->getById($id);

        if (!$purchase) {
            return redirect()->route('purchases.index')->with('error', 'Data pembelian tidak ditemukan.');
        }

        return view('admin.purchases.detail', compact('purchase'));
    }
    public function edit($id)
    {
        $purchase = $this->purchasesRepository->getById($id);

        if (!$purchase) {
            return redirect()->route('purchases.index')->with('error', 'Data pembelian tidak ditemukan.');
        }

        $branches = DB::table('branches')->where('status', 'active')->get();
        $locations = DB::table('locations')->get();
        $suppliers = DB::table('suppliers')->get();
        $products = DB::table('products')->where('status', 'active')->get();
        return view('admin.purchases.edit', compact(
            'purchase',
            'branches',
            'locations',
            'suppliers',
            'products',

        ));
    }

    public function update($id, PurchasesRequest $request)
    {

        try {
            $this->purchasesRepository->update($id, $request->validated());
            return redirect()
                ->route('purchases.index')
                ->with('success', 'Pembelian berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->route('purchases.edit', $id)
                ->with('error', 'Pembelian gagal diperbarui: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->purchasesRepository->delete($id);
            return redirect()
                ->route('purchases.index')
                ->with('success', 'Pembelian berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('purchases.index')
                ->with('error', 'Pembelian gagal dihapus: ' . $e->getMessage());
        }
    }
}
