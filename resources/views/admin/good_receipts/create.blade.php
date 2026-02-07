@extends('layouts.master')

@section('title', 'Tambah Good Receipt')

@section('content')
    <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">
                                <span class="mdi mdi-plus-circle-outline text-xl text-green-600"></span>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Tambah Good Receipt Baru</h1>
                                <p class="text-gray-600 mt-1">Buat dokumen penerimaan barang baru dari Purchase Order</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="{{ route('good-receipts.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition-all duration-200 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <span class="mdi mdi-arrow-left text-lg"></span>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('good-receipts.store') }}" method="POST" id="goodReceiptForm">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - GR Info -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- GR Number Card -->
                        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi GR</h3>
                            <div class="space-y-4">
                                <!-- GR Number -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">GR Number</label>
                                    <div class="relative">
                                        <div class="flex items-center">
                                            <span class="mdi mdi-barcode text-gray-400 absolute left-3"></span>
                                            <input type="text" value="{{ $grNumber }}" readonly
                                                class="pl-10 w-full rounded-lg border-gray-300 bg-gray-50 text-gray-600 focus:border-green-500 focus:ring-green-500">
                                        </div>
                                        <input type="hidden" name="gr_number" value="{{ $grNumber }}">
                                    </div>
                                </div>

                                <!-- Receipt Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Penerimaan <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="mdi mdi-calendar text-gray-400 absolute left-3 top-3"></span>
                                        <input type="date" name="receipt_date" required
                                            value="{{ old('receipt_date', date('Y-m-d')) }}"
                                            class="pl-10 w-full rounded-lg border-gray-300 text-gray-700 focus:border-green-500 focus:ring-green-500">
                                    </div>
                                    @error('receipt_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Received By -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Diterima Oleh <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="mdi mdi-account text-gray-400 absolute left-3 top-3"></span>
                                        <input type="text" name="received_by" required
                                            value="{{ old('received_by', auth()->user()->name) }}"
                                            class="pl-10 w-full rounded-lg border-gray-300 text-gray-700 focus:border-green-500 focus:ring-green-500">
                                    </div>
                                    @error('received_by')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Info Card -->
                        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pembelian</h3>
                            <div class="space-y-4">
                                <!-- Purchase Order -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Purchase Order <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="mdi mdi-file-document text-gray-400 absolute left-3 top-3"></span>
                                        <select name="purchase_id" id="purchase_id" required
                                            class="pl-10 w-full rounded-lg border-gray-300 text-gray-700 focus:border-green-500 focus:ring-green-500">
                                            <option value="">Pilih Purchase Order</option>
                                            @foreach ($purchases as $purchase)
                                                <option value="{{ $purchase->id }}"
                                                    data-supplier="{{ $purchase->supplier_id }}"
                                                    data-branch="{{ $purchase->branch_id }}"
                                                    data-location="{{ $purchase->location_id }}"
                                                    {{ old('purchase_id') == $purchase->id ? 'selected' : '' }}>
                                                    {{ $purchase->purchase_number }} - {{ $purchase->supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('purchase_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Supplier (Auto-filled) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                                    <div class="relative">
                                        <span class="mdi mdi-truck text-gray-400 absolute left-3 top-3"></span>
                                        <select name="supplier_id" id="supplier_id" required
                                            class="pl-10 w-full rounded-lg border-gray-300 bg-gray-50 text-gray-600 focus:border-green-500 focus:ring-green-500"
                                            readonly>
                                            <option value="">Pilih PO terlebih dahulu</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Location & Items -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Location Info Card -->
                        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Lokasi</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Branch -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Cabang <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="mdi mdi-store text-gray-400 absolute left-3 top-3"></span>
                                        <select name="branch_id" id="branch_id" required
                                            class="pl-10 w-full rounded-lg border-gray-300 text-gray-700 focus:border-green-500 focus:ring-green-500">
                                            <option value="">Pilih Cabang</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('branch_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Location -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Lokasi <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="mdi mdi-map-marker text-gray-400 absolute left-3 top-3"></span>
                                        <select name="location_id" id="location_id" required
                                            class="pl-10 w-full rounded-lg border-gray-300 text-gray-700 focus:border-green-500 focus:ring-green-500">
                                            <option value="">Pilih Lokasi</option>
                                            <!-- Locations will be populated based on branch -->
                                        </select>
                                    </div>
                                    @error('location_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea name="notes" rows="3"
                                    class="w-full rounded-lg border-gray-300 text-gray-700 focus:border-green-500 focus:ring-green-500"
                                    placeholder="Tambahkan catatan jika diperlukan...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <!-- Items Card -->
                        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Item Barang</h3>
                                <span class="text-sm text-gray-500">Pilih dari Purchase Order yang dipilih</span>
                            </div>

                            <!-- Purchase Items Table -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <input type="checkbox" id="selectAll"
                                                    class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Produk
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Order Qty
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Sisa Qty
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Diterima
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Ditolak
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Catatan
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="purchaseItems" class="bg-white divide-y divide-gray-200">
                                        <tr id="noItemsRow">
                                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                                <div class="flex flex-col items-center justify-center">
                                                    <span
                                                        class="mdi mdi-package-variant text-3xl text-gray-300 mb-2"></span>
                                                    <p>Pilih Purchase Order terlebih dahulu</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Item Actions -->
                            <div class="mt-4 flex justify-between items-center">
                                <div>
                                    <span class="text-sm text-gray-600">
                                        <span id="selectedCount">0</span> item terpilih
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" id="addSelectedItems"
                                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        disabled>
                                        <span class="mdi mdi-plus"></span>
                                        Tambahkan Item
                                    </button>
                                </div>
                            </div>

                            <!-- Selected Items Table -->
                            <div class="mt-6">
                                <h4 class="text-md font-semibold text-gray-900 mb-3">Item yang akan diterima</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Produk
                                                </th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Qty Large
                                                </th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Qty Small
                                                </th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Ditolak Large
                                                </th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Ditolak Small
                                                </th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Expiry Date
                                                </th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="selectedItems" class="bg-white divide-y divide-gray-200">
                                            <!-- Items will be added here -->
                                            <tr id="emptyItemsRow">
                                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                                    Belum ada item yang dipilih
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot id="itemsTotals" class="bg-gray-50 hidden">
                                            <tr>
                                                <td class="px-4 py-3 text-right font-medium">Total:</td>
                                                <td class="px-4 py-3 font-medium" id="totalLarge">0</td>
                                                <td class="px-4 py-3 font-medium" id="totalSmall">0</td>
                                                <td class="px-4 py-3 font-medium" id="totalRejectedLarge">0</td>
                                                <td class="px-4 py-3 font-medium" id="totalRejectedSmall">0</td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Card -->
                        <div class="rounded-xl bg-green-50 p-6 shadow-sm ring-1 ring-green-200/50">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-green-900">Ringkasan</h3>
                                    <p class="text-sm text-green-700 mt-1">Pastikan semua data sudah benar sebelum
                                        menyimpan</p>
                                </div>
                                <div class="mt-4 md:mt-0 flex flex-col items-end">
                                    <div class="text-right">
                                        <p class="text-sm text-green-700">Total Item: <span id="totalItemsCount"
                                                class="font-semibold">0</span></p>
                                        <p class="text-sm text-green-700">Total Quantity Large: <span id="totalItemsLarge"
                                                class="font-semibold">0</span></p>
                                        <p class="text-sm text-green-700">Total Quantity Small: <span id="totalItemsSmall"
                                                class="font-semibold">0</span></p>
                                    </div>
                                    <button type="submit"
                                        class="mt-4 inline-flex items-center gap-2 rounded-xl bg-green-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-green-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                        <span class="mdi mdi-content-save text-lg"></span>
                                        Simpan Good Receipt
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden fields for items -->
                <div id="itemsData"></div>
                <input type="hidden" name="total_items" id="total_items" value="0">
                <input type="hidden" name="total_quantity_large" id="total_quantity_large" value="0">
                <input type="hidden" name="total_quantity_small" id="total_quantity_small" value="0">
            </form>
        </div>
    </main>
@endsection

@push('styles')
    <style>
        .select2-container .select2-selection--single {
            height: 42px;
            padding-top: 8px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let locations = @json($locations);
            let selectedItems = [];

            // Initialize Select2
            $('#purchase_id').select2({
                placeholder: "Pilih Purchase Order",
                allowClear: true
            });

            $('#supplier_id, #branch_id, #location_id').select2({
                placeholder: "Pilih...",
                allowClear: true
            });

            // When purchase is selected
            $('#purchase_id').on('change', function() {
                const purchaseId = $(this).val();
                const selectedOption = $(this).find('option:selected');

                if (purchaseId) {
                    // Auto-fill supplier, branch, location
                    const supplierId = selectedOption.data('supplier');
                    const branchId = selectedOption.data('branch');
                    const locationId = selectedOption.data('location');

                    $('#supplier_id').val(supplierId).trigger('change');
                    $('#branch_id').val(branchId).trigger('change');

                    // Update locations based on branch
                    updateLocations(branchId, locationId);

                    // Load purchase items
                    loadPurchaseItems(purchaseId);
                } else {
                    resetForm();
                }
            });

            // When branch changes, update locations
            $('#branch_id').on('change', function() {
                const branchId = $(this).val();
                updateLocations(branchId);
            });

            function updateLocations(branchId, selectedLocationId = null) {
                const $locationSelect = $('#location_id');
                $locationSelect.empty();
                $locationSelect.append('<option value="">Pilih Lokasi</option>');

                if (branchId && locations[branchId]) {
                    locations[branchId].forEach(location => {
                        $locationSelect.append(new Option(location.name, location.id));
                    });

                    if (selectedLocationId) {
                        $locationSelect.val(selectedLocationId).trigger('change');
                    }
                }
            }

            function loadPurchaseItems(purchaseId) {
                $.ajax({
                    url: '{{ route('purchases.get-items', ':id') }}'.replace(':id', purchaseId),
                    method: 'GET',
                    success: function(response) {
                        const items = response.items || [];
                        const $itemsTable = $('#purchaseItems');
                        const $noItemsRow = $('#noItemsRow');

                        if (items.length > 0) {
                            $noItemsRow.hide();

                            items.forEach(item => {
                                const remainingLarge = item.remaining_large;
                                const remainingSmall = item.remaining_small;

                                if (remainingLarge > 0 || remainingSmall > 0) {
                                    const row = `
                            <tr data-item-id="${item.id}" data-product-id="${item.product_id}">
                                <td class="px-4 py-3">
                                    <input type="checkbox" class="item-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500"
                                           data-item='${JSON.stringify(item)}'>
                                </td>
                                <td class="px-4 py-3">
                                    <div>
                                        <div class="font-medium text-gray-900">${item.product.name}</div>
                                        <div class="text-xs text-gray-500">${item.product.code || '-'}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm">
                                        <div>Large: ${item.qty_large}</div>
                                        <div>Small: ${item.qty_small}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm">
                                        <div>Large: <span class="remaining-large">${remainingLarge}</span></div>
                                        <div>Small: <span class="remaining-small">${remainingSmall}</span></div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-1">
                                        <input type="number" min="0" max="${remainingLarge}"
                                               class="received-large w-20 rounded border-gray-300 text-sm"
                                               placeholder="Large" value="${remainingLarge}">
                                        <input type="number" min="0" max="${remainingSmall}"
                                               class="received-small w-20 rounded border-gray-300 text-sm"
                                               placeholder="Small" value="${remainingSmall}">
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-1">
                                        <input type="number" min="0" max="${remainingLarge}"
                                               class="rejected-large w-20 rounded border-gray-300 text-sm"
                                               placeholder="Large" value="0">
                                        <input type="number" min="0" max="${remainingSmall}"
                                               class="rejected-small w-20 rounded border-gray-300 text-sm"
                                               placeholder="Small" value="0">
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" class="item-note w-full rounded border-gray-300 text-sm"
                                           placeholder="Catatan...">
                                </td>
                            </tr>
                        `;
                                    $itemsTable.append(row);
                                }
                            });

                            if ($itemsTable.children().length === 0) {
                                $noItemsRow.show().find('p').text('Semua item sudah diterima');
                            }
                        } else {
                            $noItemsRow.show().find('p').text('Tidak ada item yang tersedia');
                        }

                        updateSelectedCount();
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.error || 'Gagal memuat item pembelian',
                            'error');
                    }
                });
            }

            // Select all items
            $('#selectAll').on('change', function() {
                const isChecked = $(this).prop('checked');
                $('.item-checkbox').prop('checked', isChecked);
                updateSelectedCount();
            });

            // Update selected count
            function updateSelectedCount() {
                const selectedCount = $('.item-checkbox:checked').length;
                $('#selectedCount').text(selectedCount);
                $('#addSelectedItems').prop('disabled', selectedCount === 0);
            }

            // Item checkbox change
            $(document).on('change', '.item-checkbox', updateSelectedCount);

            // Add selected items
            $('#addSelectedItems').on('click', function() {
                $('.item-checkbox:checked').each(function() {
                    const $checkbox = $(this);
                    const item = $checkbox.data('item');
                    const $row = $checkbox.closest('tr');

                    // Get values from inputs
                    const receivedLarge = parseInt($row.find('.received-large').val()) || 0;
                    const receivedSmall = parseInt($row.find('.received-small').val()) || 0;
                    const rejectedLarge = parseInt($row.find('.rejected-large').val()) || 0;
                    const rejectedSmall = parseInt($row.find('.rejected-small').val()) || 0;
                    const note = $row.find('.item-note').val();

                    // Validate quantities
                    const remainingLarge = item.qty_large - (item.qty_received_large || 0);
                    const remainingSmall = item.qty_small - (item.qty_received_small || 0);

                    if (receivedLarge > remainingLarge) {
                        Swal.fire('Error', `Qty Large tidak boleh melebihi ${remainingLarge}`,
                            'error');
                        return;
                    }

                    if (receivedSmall > remainingSmall) {
                        Swal.fire('Error', `Qty Small tidak boleh melebihi ${remainingSmall}`,
                            'error');
                        return;
                    }

                    if (rejectedLarge > receivedLarge) {
                        Swal.fire('Error', 'Ditolak tidak boleh lebih dari diterima', 'error');
                        return;
                    }

                    if (rejectedSmall > receivedSmall) {
                        Swal.fire('Error', 'Ditolak tidak boleh lebih dari diterima', 'error');
                        return;
                    }

                    // Check if item already selected
                    const existingIndex = selectedItems.findIndex(i => i.id === item.id);
                    if (existingIndex === -1) {
                        // Add new item
                        selectedItems.push({
                            id: item.id,
                            purchase_items_id: item.id,
                            product_id: item.product_id,
                            product_name: item.product.name,
                            qty_received_large: receivedLarge,
                            qty_received_small: receivedSmall,
                            qty_rejected_large: rejectedLarge,
                            qty_rejected_small: rejectedSmall,
                            note: note
                        });

                        addItemToTable(item, receivedLarge, receivedSmall, rejectedLarge,
                            rejectedSmall, note);
                    }

                    // Uncheck checkbox
                    $checkbox.prop('checked', false);
                });

                updateSelectedCount();
                updateTotals();
                updateSummary();
            });

            function addItemToTable(item, receivedLarge, receivedSmall, rejectedLarge, rejectedSmall, note) {
                const $table = $('#selectedItems');
                const $emptyRow = $('#emptyItemsRow');

                if ($emptyRow.length) {
                    $emptyRow.remove();
                    $('#itemsTotals').removeClass('hidden');
                }

                const rowId = `item-${item.id}`;
                const row = `
            <tr id="${rowId}">
                <td class="px-4 py-3">
                    <div>
                        <div class="font-medium text-gray-900">${item.product.name}</div>
                        <div class="text-xs text-gray-500">SKU: ${item.product.sku || '-'}</div>
                        <input type="hidden" name="items[${item.id}][purchase_items_id]" value="${item.id}">
                        <input type="hidden" name="items[${item.id}][product_id]" value="${item.product_id}">
                        <input type="hidden" name="items[${item.id}][qty_received_large]" value="${receivedLarge}">
                        <input type="hidden" name="items[${item.id}][qty_received_small]" value="${receivedSmall}">
                        <input type="hidden" name="items[${item.id}][qty_rejected_large]" value="${rejectedLarge}">
                        <input type="hidden" name="items[${item.id}][qty_rejected_small]" value="${rejectedSmall}">
                        <input type="hidden" name="items[${item.id}][note]" value="${note}">
                    </div>
                </td>
                <td class="px-4 py-3">
                    <span class="font-medium">${receivedLarge}</span>
                </td>
                <td class="px-4 py-3">
                    <span class="font-medium">${receivedSmall}</span>
                </td>
                <td class="px-4 py-3">
                    <span class="font-medium">${rejectedLarge}</span>
                </td>
                <td class="px-4 py-3">
                    <span class="font-medium">${rejectedSmall}</span>
                </td>
                <td class="px-4 py-3">
                    <input type="date" name="items[${item.id}][expiry_date]"
                           class="w-full rounded border-gray-300 text-sm">
                </td>
                <td class="px-4 py-3">
                    <button type="button" onclick="removeItem('${item.id}')"
                        class="text-red-600 hover:text-red-900">
                        <span class="mdi mdi-delete"></span>
                    </button>
                </td>
            </tr>
        `;

                $table.append(row);
            }

            // Global function to remove item
            window.removeItem = function(itemId) {
                selectedItems = selectedItems.filter(item => item.id !== itemId);
                $(`#item-${itemId}`).remove();

                if (selectedItems.length === 0) {
                    $('#selectedItems').append(`
                <tr id="emptyItemsRow">
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                        Belum ada item yang dipilih
                    </td>
                </tr>
            `);
                    $('#itemsTotals').addClass('hidden');
                }

                updateTotals();
                updateSummary();
            };

            function updateTotals() {
                let totalLarge = 0;
                let totalSmall = 0;
                let totalRejectedLarge = 0;
                let totalRejectedSmall = 0;

                selectedItems.forEach(item => {
                    totalLarge += item.qty_received_large;
                    totalSmall += item.qty_received_small;
                    totalRejectedLarge += item.qty_rejected_large;
                    totalRejectedSmall += item.qty_rejected_small;
                });

                $('#totalLarge').text(totalLarge);
                $('#totalSmall').text(totalSmall);
                $('#totalRejectedLarge').text(totalRejectedLarge);
                $('#totalRejectedSmall').text(totalRejectedSmall);
            }

            function updateSummary() {
                $('#total_items').val(selectedItems.length);
                $('#totalItemsCount').text(selectedItems.length);

                const totalLarge = selectedItems.reduce((sum, item) => sum + item.qty_received_large, 0);
                const totalSmall = selectedItems.reduce((sum, item) => sum + item.qty_received_small, 0);

                $('#total_quantity_large').val(totalLarge);
                $('#total_quantity_small').val(totalSmall);
                $('#totalItemsLarge').text(totalLarge);
                $('#totalItemsSmall').text(totalSmall);
            }

            function resetForm() {
                $('#supplier_id').val('').trigger('change');
                $('#branch_id').val('').trigger('change');
                $('#location_id').empty().append('<option value="">Pilih Lokasi</option>');
                $('#purchaseItems').empty().append(`
            <tr id="noItemsRow">
                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                        <span class="mdi mdi-package-variant text-3xl text-gray-300 mb-2"></span>
                        <p>Pilih Purchase Order terlebih dahulu</p>
                    </div>
                </td>
            </tr>
        `);

                // Clear selected items
                selectedItems = [];
                $('#selectedItems').empty().append(`
            <tr id="emptyItemsRow">
                <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                    Belum ada item yang dipilih
                </td>
            </tr>
        `);
                $('#itemsTotals').addClass('hidden');
                updateSummary();
            }

            // Form validation
            $('#goodReceiptForm').on('submit', function(e) {
                if (selectedItems.length === 0) {
                    e.preventDefault();
                    Swal.fire('Error', 'Harap tambahkan minimal satu item', 'error');
                    return;
                }

                // Validate quantities
                for (const item of selectedItems) {
                    if (item.qty_received_large <= 0 && item.qty_received_small <= 0) {
                        e.preventDefault();
                        Swal.fire('Error',
                            `Item ${item.product_name} harus memiliki quantity yang diterima`, 'error');
                        return;
                    }
                }
            });
        });

        // Add route for getting purchase items
        if (typeof routes === 'undefined') {
            window.routes = {};
        }
        window.routes.getPurchaseItems = '{{ route('purchases.get-items', ":id") }}';
    </script>
@endpush
