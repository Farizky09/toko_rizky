<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoodReceiptRquest;
use App\Interfaces\GoodReceiptsInterfaces;
use App\Models\Purchases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoodReceiptsController extends Controller
{
    private $goodReceiptsRepository;

    public function __construct(GoodReceiptsInterfaces $goodReceiptsRepository)
    {
        $this->goodReceiptsRepository = $goodReceiptsRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->goodReceiptsRepository->datatable();
            return datatables()->of($data)
                ->addColumn('gr_number', fn($data) => $data->gr_number)
                ->addColumn('receipt_date', fn($data) => date('Y-m-d', strtotime($data->receipt_date)))
                ->addColumn('purchase_number', fn($data) => $data->purchase_number ?? '-')
                ->addColumn('supplier_name', fn($data) => $data->supplier_name ?? '-')
                ->addColumn('branch_name', fn($data) => $data->branch_name ?? '-')
                ->addColumn('location_name', fn($data) => $data->location_name ?? '-')
                ->addColumn('received_by_name', fn($data) => $data->received_by_name ?? '-')
                ->addColumn('action', function ($data) {
                    return view('admin.good_receipts.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        $branches = DB::table('branches')->where('status', 'active')->get();
        return view('admin.good_receipts.index', compact('branches'));
    }

    public function create()
    {

        $purchases = Purchases::with(['supplier', 'branch', 'location', 'purchasesItems.product'])
            ->whereIn('status', ['draft', 'partial'])
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
            $goodReceipt = $this->goodReceiptsRepository->getById($id);
            return view('admin.good_receipts.show', compact('goodReceipt'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $goodReceipt = $this->goodReceiptsRepository->getById($id);
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

    public function process($id)
    {
        try {
            $this->goodReceiptsRepository->process($id);
            return redirect()->route('admin.good-receipts.index')->with('success', 'Good Receipt berhasil diproses.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        try {
            $this->goodReceiptsRepository->cancel($id);
            return redirect()->route('admin.good-receipts.index')->with('success', 'Good Receipt berhasil dibatalkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function complete($id)
    {
        try {
            $this->goodReceiptsRepository->complete($id);
            return redirect()->route('admin.good-receipts.index')->with('success', 'Good Receipt berhasil diselesaikan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
