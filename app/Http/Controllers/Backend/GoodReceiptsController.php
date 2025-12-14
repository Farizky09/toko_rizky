<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoodReceiptRquest;
use App\Models\Purchases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoodReceiptsController extends Controller
{
    private $goodReceiptsRepository;

    public function __construct($goodReceiptsRepository)
    {
        $this->goodReceiptsRepository = $goodReceiptsRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->goodReceiptsRepository->datatable();
            return datatables()->of($data)
                ->addColumn('good_receipt_number', fn($data) => $data->good_receipt_number)
                ->addColumn('purchase_number', fn($data) => $data->purchase_number ?? '-')
                ->addColumn('branch_name', fn($data) => $data->branch_name ?? '-')
                ->addColumn(
                    'receipt_date',
                    fn($data) =>
                    date('Y-m-d', strtotime($data->receipt_date))
                )
                ->addColumn('total_items', fn($data) => $data->total_items ?? 0)
                ->addColumn('total_quantity_large', fn($data) => $data->total_quantity_large ?? 0)
                ->addColumn('total_quantity_small', fn($data) => $data->total_quantity_small ?? 0)
                ->addColumn('total_quantitiy', fn($data) => ($data->total_quantity_large ?? 0) + ($data->total_quantity_small ?? 0))
                ->addColumn('total_amount', fn($data) => (int) $data->total_amount)
                ->addColumn('status', fn($data) => $data->status)
                ->addColumn('action', function ($data) {
                    return view('admin.good_receipts.column.action', compact('data'));
                })

                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.good_receipts.index');
    }

    public function create()
    {

        $purchases = Purchases::with(['supplier', 'branch', 'location', 'purchasesItems.product'])
            ->where('status', ['draft', 'partial'])
            ->get();
        $grNumber = $this->goodReceiptsRepository->generateGoodReceiptNumber();
        $branches = DB::table('branches')->where('status', 'active')->get();
        $suppliers = DB::table('suppliers')->where('status', 'active')->get();
        $locations = DB::table('locations')->get()->groupBy('branch_id');


        return view('admin.good_receipts.create', compact('purchases', 'grNumber', 'branches', 'suppliers', 'locations'));
    }

    public function store(GoodReceiptRquest $request)
    {
        $data = $request->validated();
        try {
            $this->goodReceiptsRepository->store($data);
            return redirect()->route('admin.good-receipts.index')->with('success', 'Good Receipt berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $goodReceipt = $this->goodReceiptsRepository->findById($id);
            return view('admin.good_receipts.show', compact('goodReceipt'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $goodReceipt = $this->goodReceiptsRepository->findById($id);
            $branches = DB::table('branches')->where('status', 'active')->get();
            $suppliers = DB::table('suppliers')->where('status', 'active')->get();
            $locations = DB::table('locations')->get()->groupBy('branch_id');

            return view('admin.good_receipts.edit', compact('goodReceipt', 'branches', 'suppliers', 'locations'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(GoodReceiptRquest $request, $id)
    {
        $data = $request->validated();
        try {
            $this->goodReceiptsRepository->update($id, $data);
            return redirect()->route('admin.good-receipts.index')->with('success', 'Good Receipt berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->goodReceiptsRepository->delete($id);
            return redirect()->route('admin.good-receipts.index')->with('success', 'Good Receipt berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
}
