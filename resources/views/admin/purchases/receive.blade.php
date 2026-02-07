@extends('layouts.master')

@section('title', 'Penerimaan Barang: ' . $purchase->purchase_number)

@section('content')
    <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <form action="{{ route('purchases.receive-process', $purchase->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">
                                <span class="mdi mdi-truck-check-outline text-xl text-green-600"></span>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Form Penerimaan Barang</h1>
                                <p class="text-gray-600 mt-1">Verifikasi kuantitas untuk:
                                    <span class="font-semibold text-blue-600">{{ $purchase->purchase_number }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="{{ route('purchases.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-200">
                            <span class="mdi mdi-arrow-left"></span>
                            Kembali
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-green-700 hover:shadow-md">
                            <span class="mdi mdi-check-circle-outline text-lg"></span>
                            Konfirmasi & Terima Stok
                        </button>
                    </div>
                </div>
            </div>

            <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Informasi PO</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Supplier</dt>
                        {{-- PERBAIKAN: Gunakan relasi Eloquent jika Anda sudah memperbaikinya di getById --}}
                        <dd class="mt-1 text-base text-gray-900 font-semibold">
                            {{ $purchase->supplier->name ?? ($purchase->supplier_name ?? '-') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Lokasi Penerimaan</dt>
                        {{-- PERBAIKAN: Gunakan relasi Eloquent jika Anda sudah memperbaikinya di getById --}}
                        <dd class="mt-1 text-base text-gray-900 font-semibold">
                            {{ $purchase->location->name ?? ($purchase->location_name ?? '-') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tanggal PO</dt>
                        <dd class="mt-1 text-base text-gray-900 font-semibold">
                            {{-- PERBAIKAN: Gunakan created_at untuk tanggal PO dibuat, bukan purchase_date (tanggal acara) --}}
                            {{ \Carbon\Carbon::parse($purchase->created_at)->translatedFormat('d F Y H:i') }}</dd>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200/60">
                <div class="p-4">
                    <table id="itemsTable" class="w-full">
                        <thead>
                            <tr
                                class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                <th class="py-3 px-4 w-2/5">Produk</th>
                                <th class="py-3 px-4 text-center">Qty Dipesan (Besar)</th>
                                <th class="py-3 px-4 text-center">Qty Dipesan (Kecil)</th>
                                <th class="py-3 px-4 text-center bg-green-50 w-1/6">Qty Diterima (Besar)</th>
                                <th class="py-3 px-4 text-center bg-green-50 w-1/6">Qty Diterima (Kecil)</th>
                                <th class="py-3 px-4 w-1/5 bg-green-50">Catatan Item</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($purchase->purchasesItems as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        {{-- PERBAIKAN: Gunakan relasi Eloquent --}}
                                        <span
                                            class="font-semibold">{{ $item->product->name ?? ($item->product_name ?? 'Produk Dihapus') }}</span>
                                        <span class="block text-sm text-gray-500">Kode:
                                            {{ $item->product->code ?? ($item->product_code ?? '-') }}</span>
                                    </td>

                                    {{-- ====================================================== --}}
                                    {{-- ============ PERBAIKAN DESIMAL DIMULAI ============= --}}
                                    {{-- ====================================================== --}}
                                    <td class="py-3 px-4 text-center">
                                        {{-- Ubah (float) menjadi (int) untuk hapus ,00 --}}
                                        <span class="text-lg font-mono">{{ (int) $item->qty_large }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        {{-- Ubah (float) menjadi (int) untuk hapus ,00 --}}
                                        <span class="text-lg font-mono">{{ (int) $item->qty_small }}</span>
                                    </td>
                                    {{-- ====================================================== --}}
                                    {{-- ============= PERBAIKAN DESIMAL SELESAI ============= --}}
                                    {{-- ====================================================== --}}

                                    <td class="py-3 px-4 bg-green-50/50">
                                        <input type="number" step="0.01" min="0"
                                            name="items[{{ $item->id }}][qty_received_large]" {{-- PERBAIKAN: Default value harus qty_large (jumlah dipesan) --}}
                                            value="{{ old('items.' . $item->id . '.qty_received_large', (float) $item->qty_large) }}"
                                            class="form-input block w-full rounded-md border-gray-300 text-center shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                    </td>
                                    <td class="py-3 px-4 bg-green-50/50">
                                        <input type="number" step="0.01" min="0"
                                            name="items[{{ $item->id }}][qty_received_small]" {{-- PERBAIKAN: Default value harus qty_small (jumlah dipesan) --}}
                                            value="{{ old('items.' . $item->id . '.qty_received_small', (float) $item->qty_small) }}"
                                            class="form-input block w-full rounded-md border-gray-300 text-center shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                    </td>
                                    <td class="py-3 px-4 bg-green-50/50">
                                        <input type="text" name="items[{{ $item->id }}][item_notes]"
                                            placeholder="Cth: 1 dus rusak"
                                            value="{{ old('items.' . $item->id . '.item_notes') }}"
                                            class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </main>
@endsection
