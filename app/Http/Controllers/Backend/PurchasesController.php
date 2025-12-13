<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchasesRequest;
use App\Http\Requests\ReceivePurchaseRequest;
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
        $data = $this->purchasesRepository->datatable();
        // dd($data->get());

        if ($request->ajax()) {
            $data = $this->purchasesRepository->datatable();
            return datatables()->of($data)
                ->addColumn('purchase_number', fn($data) => $data->purchase_number)
                ->addColumn('supplier_name', fn($data) => $data->supplier_name ?? '-')
                ->addColumn('branch_name', fn($data) => $data->branch_name ?? '-')
                ->addColumn(
                    'purchase_date',
                    fn($data) =>
                    date('Y-m-d', strtotime($data->purchase_date))
                )
                ->addColumn('total_items', fn($data) => $data->total_items ?? 0)
                ->addColumn('total_quantity_large', fn($data) => $data->total_quantity_large ?? 0)
                ->addColumn('total_quantity_small', fn($data) => $data->total_quantity_small ?? 0)
                ->addColumn('total_quantitiy', fn($data) => ($data->total_quantity_large ?? 0) + ($data->total_quantity_small ?? 0))
                ->addColumn('total_amount', fn($data) => (int) $data->total_amount)
                ->addColumn('status', fn($data) => $data->status)
                ->addColumn('action', function ($data) {
                    return view('admin.purchases.column.action', compact('data'));
                })

                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.purchases.index');
    }

    public function create()
    {

        $purchaseNumber = $this->purchasesRepository->generatePurchaseNumber();
        $branches = DB::table('branches')->where('status', 'active')->get();
        $suppliers = DB::table('suppliers')->where('status', 'active')->get();

        $locations = DB::table('locations')->get()->groupBy('branch_id');

        $latestBatchIds = DB::table('batches')
            ->select('product_id', DB::raw('MAX(id) as max_id'))
            ->groupBy('product_id');

        $products = DB::table('products as p')
            ->where('p.status', 'active')
            ->leftJoinSub($latestBatchIds, 'latest_batches', function ($join) {
                $join->on('p.id', '=', 'latest_batches.product_id');
            })
            ->leftJoin('batches as b', 'b.id', '=', 'latest_batches.max_id')
            ->select(
                'p.id',
                'p.code',
                'p.name',
                DB::raw('COALESCE(b.purchase_price_large, 0) as purchase_price_large'),
                DB::raw('COALESCE(b.purchase_price_small, 0) as purchase_price_small'),
                DB::raw('COALESCE(b.selling_price_large, 0) as selling_price_large'),
                DB::raw('COALESCE(b.selling_price_small, 0) as selling_price_small')
            )
            ->orderBy('p.name')
            ->get();

        return view('admin.purchases.create', compact(
            'purchaseNumber',
            'branches',
            'suppliers',
            'products',
            'locations'
        ));
    }

    public function store(PurchasesRequest $request)
    {
        try {
            $purchase = $this->purchasesRepository->store($request->validated());
            return redirect()
                ->route('purchases.index')
                ->with('success', 'PO berhasil dibuat sebagai Draft.');
        } catch (\Exception $e) {
            return redirect()
                ->route('purchases.create')
                ->with('error', 'PO gagal dibuat: ' . $e->getMessage())
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

        if ($purchase->status !== 'draft') {
            return redirect()->route('purchases.index')->with('error', 'Hanya PO status "Draft" yang bisa diedit.');
        }

        $branches = DB::table('branches')->where('status', 'active')->get();
        $suppliers = DB::table('suppliers')->where('status', 'active')->get();
        $locations = DB::table('locations')->get()->groupBy('branch_id');

        $latestBatchIds = DB::table('batches')
            ->select('product_id', DB::raw('MAX(id) as max_id'))
            ->groupBy('product_id');

        $products = DB::table('products as p')
            ->where('p.status', 'active')
            ->leftJoinSub($latestBatchIds, 'latest_batches', function ($join) {
                $join->on('p.id', '=', 'latest_batches.product_id');
            })
            ->leftJoin('batches as b', 'b.id', '=', 'latest_batches.max_id')
            ->select(
                'p.id',
                'p.code',
                'p.name',
                DB::raw('COALESCE(b.purchase_price_large, 0) as purchase_price_large'),
                DB::raw('COALESCE(b.purchase_price_small, 0) as purchase_price_small'),
                DB::raw('COALESCE(b.selling_price_large, 0) as selling_price_large'),
                DB::raw('COALESCE(b.selling_price_small, 0) as selling_price_small')
            )
            ->orderBy('p.name')
            ->get();

        return view('admin.purchases.edit', compact(
            'purchase',
            'branches',
            'locations',
            'suppliers',
            'products'
        ));
    }

    public function update($id, PurchasesRequest $request)
    {
        try {
            $this->purchasesRepository->update($id, $request->validated());
            return redirect()
                ->route('purchases.index')
                ->with('success', 'PO Draft berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->route('purchases.edit', $id)
                ->with('error', 'PO Draft gagal diperbarui: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        try {
            $this->purchasesRepository->cancel($id);
            return redirect()
                ->route('purchases.index')
                ->with('success', 'Pembelian berhasil dibatalkan.');
        } catch (\Exception $e) {
            return redirect()
                ->route('purchases.index')
                ->with('error', 'Pembelian gagal dibatalkan: ' . $e->getMessage());
        }
    }


    public function delete($id)
    {
        try {
            $this->purchasesRepository->delete($id);
            return redirect()
                ->route('purchases.index')
                ->with('success', 'Pembelian berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return redirect()
                ->route('purchases.index')
                ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
