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
        if ($request->ajax()) {
            $data = $this->purchasesRepository->datatable();


            $statusConfig = [
                'draft' => ['class' => 'bg-amber-100 text-amber-800', 'icon' => 'mdi-pencil', 'label' => 'Draft'],
                'completed' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'mdi-check-circle', 'label' => 'Completed'],
                'cancelled' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'mdi-close-circle', 'label' => 'Cancelled']
            ];

            return datatables()->of($data)
                ->addColumn('purchase_number', function ($data) {
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">' . $data->purchase_number . '</span>';
                })
                ->addColumn('supplier_name', fn($data) => $data->supplier_name ?? '-')
                ->addColumn('branch_name', fn($data) => $data->branch_name ?? '-')
                ->addColumn('purchase_date', fn($data) => date('d/m/Y', strtotime($data->purchase_date)))
                ->addColumn('total_quantity', function ($data) {
                    $total = ($data->total_quantity_large ?? 0) + ($data->total_quantity_small ?? 0);
                    return '<span class="font-medium">' . $total . '</span>';
                })
                ->addColumn('total_amount', fn($data) => 'Rp ' . number_format($data->total_amount, 0, ',', '.'))
                ->addColumn('status', function ($data) use ($statusConfig) {
                    $config = $statusConfig[$data->status] ?? $statusConfig['draft'];
                    return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium ' . $config['class'] . '">
                                <span class="mdi ' . $config['icon'] . '"></span>
                                ' . $config['label'] . '
                            </span>';
                })
                ->addColumn('user_name', fn($data) => $data->user_name ?? '-')
                ->addColumn('action', function ($data) {
                    // Tombol aksi sekarang akan bergantung pada status
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
        $suppliers = DB::table('suppliers')->get();

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
        // dd($purchase->purchasesItems);
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
        $suppliers = DB::table('suppliers')->get();


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

    public function showReceiveForm($id)
    {
        $purchase = $this->purchasesRepository->getById($id);

        if (!$purchase) {
            return redirect()->route('purchases.index')->with('error', 'Data pembelian tidak ditemukan.');
        }


        if ($purchase->status !== 'draft') {
            return redirect()->route('purchases.index')->with('error', 'Hanya PO status "Draft" yang bisa diterima.');
        }

        return view('admin.purchases.receive', compact('purchase'));
    }


    public function processReceive($id, ReceivePurchaseRequest $request)
    {
        try {
            $this->purchasesRepository->receivePurchase($id, $request->validated());
            return redirect()
                ->route('purchases.index')
                ->with('success', 'Barang berhasil diterima dan stok telah ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->route('purchases.receive.form', $id)
                ->with('error', 'Gagal memproses penerimaan: ' . $e->getMessage());
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
