@extends('layouts.master')

@section('title', 'Edit Pembelian - ' . $purchase->purchase_number)

@section('content')
    <div class="container-fluid px-6 py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="h3 mb-2 text-gray-900 font-weight-bold">Edit Pembelian</h1>
                <p class="text-muted">Perbarui informasi pembelian yang sudah ada</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">
                    <i class="mdi mdi-arrow-left me-2"></i>Kembali
                </a>
                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                    <i class="mdi mdi-delete-outline me-2"></i>Hapus
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Form Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-pencil-box-outline me-2"></i>Form Edit Pembelian
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('purchases.update', $purchase->id) }}" method="POST" id="purchaseForm">
                            @csrf
                            @method('PUT')

                            <!-- Header Information -->
                            <div class="row">
                                <div class="col-md-3 mb-4">
                                    <label for="purchase_number" class="form-label fw-semibold text-gray-700">
                                        Nomor Pembelian
                                    </label>
                                    <input type="text" id="purchase_number" value="{{ $purchase->purchase_number }}"
                                        class="form-control form-control-lg bg-light" readonly>
                                </div>

                                <div class="col-md-3 mb-4">
                                    <label for="purchase_date" class="form-label fw-semibold text-gray-700">
                                        Tanggal Pembelian <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" id="purchase_date" name="purchase_date"
                                        value="{{ old('purchase_date', $purchase->purchase_date) }}"
                                        class="form-control form-control-lg @error('purchase_date') is-invalid @enderror"
                                        required>

                                    @error('purchase_date')
                                        <div class="invalid-feedback d-block">
                                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-4">
                                    <label for="status" class="form-label fw-semibold text-gray-700">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select id="status" name="status"
                                        class="form-control form-control-lg @error('status') is-invalid @enderror" required>
                                        <option value="">Pilih Status</option>
                                        <option value="draft"
                                            {{ old('status', $purchase->status) == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                        <option value="pending"
                                            {{ old('status', $purchase->status) == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="completed"
                                            {{ old('status', $purchase->status) == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="cancelled"
                                            {{ old('status', $purchase->status) == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>

                                    @error('status')
                                        <div class="invalid-feedback d-block">
                                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Branch, Location, Supplier Selection -->
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label for="branch_id" class="form-label fw-semibold text-gray-700">
                                        Cabang <span class="text-danger">*</span>
                                    </label>
                                    <select id="branch_id" name="branch_id"
                                        class="form-control form-control-lg select2 @error('branch_id') is-invalid @enderror"
                                        required>
                                        <option value="">Pilih Cabang</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('branch_id', $purchase->branch_id) == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('branch_id')
                                        <div class="invalid-feedback d-block">
                                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label for="location_id" class="form-label fw-semibold text-gray-700">
                                        Lokasi <span class="text-danger">*</span>
                                    </label>
                                    <select id="location_id" name="location_id"
                                        class="form-control form-control-lg select2 @error('location_id') is-invalid @enderror"
                                        required>
                                        <option value="">Pilih Lokasi</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}"
                                                {{ old('location_id', $purchase->location_id) == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('location_id')
                                        <div class="invalid-feedback d-block">
                                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label for="supplier_id" class="form-label fw-semibold text-gray-700">
                                        Supplier <span class="text-danger">*</span>
                                    </label>
                                    <select id="supplier_id" name="supplier_id"
                                        class="form-control form-control-lg select2 @error('supplier_id') is-invalid @enderror"
                                        required>
                                        <option value="">Pilih Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_id', $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('supplier_id')
                                        <div class="invalid-feedback d-block">
                                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Items Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="font-weight-bold text-gray-900 mb-4">
                                        <i class="mdi mdi-cart-plus me-2"></i>Items Pembelian
                                    </h5>

                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="items-table">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th width="25%">Produk</th>
                                                    <th width="10%">Qty Besar</th>
                                                    <th width="10%">Qty Kecil</th>
                                                    <th width="15%">Harga Beli Besar</th>
                                                    <th width="15%">Harga Beli Kecil</th>
                                                    <th width="15%">Harga Jual Besar</th>
                                                    <th width="15%">Harga Jual Kecil</th>
                                                    <th width="10%">Subtotal</th>
                                                    <th width="5%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="items-tbody">
                                                <!-- Items will be populated by JavaScript -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="8" class="text-end">
                                                        <button type="button" class="btn btn-success" id="add-item">
                                                            <i class="mdi mdi-plus-circle-outline me-2"></i>Tambah Item
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary Section -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="notes" class="form-label fw-semibold text-gray-700">Catatan</label>
                                        <textarea id="notes" name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
                                            placeholder="Tambahkan catatan jika diperlukan">{{ old('notes', $purchase->notes) }}</textarea>

                                        @error('notes')
                                            <div class="invalid-feedback d-block">
                                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">Ringkasan Pembelian</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Subtotal:</span>
                                                <span id="summary-subtotal">Rp 0</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Pajak:</span>
                                                <div class="input-group" style="width: 150px;">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" id="tax" name="tax"
                                                        value="{{ old('tax', $purchase->tax) }}"
                                                        class="form-control form-control-sm money-input text-end">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Diskon:</span>
                                                <div class="input-group" style="width: 150px;">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" id="discount" name="discount"
                                                        value="{{ old('discount', $purchase->discount) }}"
                                                        class="form-control form-control-sm money-input text-end">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between mb-0">
                                                <strong>Total Amount:</strong>
                                                <strong id="summary-total">Rp 0</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Metadata Information -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-gray-700">Dibuat Oleh</label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $purchase->user_name ?? 'System' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-gray-700">Terakhir Diperbarui</label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ \Carbon\Carbon::parse($purchase->updated_at)->translatedFormat('d F Y H:i') }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end align-items-center gap-3 pt-4 border-top">
                                <button type="button" onclick="handleCancel()"
                                    class="btn btn-outline-secondary btn-lg px-5">
                                    <i class="mdi mdi-close-circle-outline me-2"></i>Batal
                                </button>
                                <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                                    <i class="mdi mdi-content-save-check me-2"></i>Perbarui Pembelian
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div class="col-lg-4">
                <!-- Purchase Info Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-information-outline me-2"></i>Informasi Pembelian
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="bg-light-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="mdi mdi-cart-arrow-down fs-2 text-primary"></i>
                            </div>
                            <h5 class="font-weight-bold text-gray-900">{{ $purchase->purchase_number }}</h5>
                            <p class="text-muted small">ID: #{{ $purchase->id }}</p>

                            @php
                                $statusConfig = [
                                    'draft' => [
                                        'class' => 'bg-gray-100 text-gray-800',
                                        'icon' => 'mdi-pencil',
                                        'label' => 'Draft',
                                    ],
                                    'pending' => [
                                        'class' => 'bg-amber-100 text-amber-800',
                                        'icon' => 'mdi-clock',
                                        'label' => 'Pending',
                                    ],
                                    'completed' => [
                                        'class' => 'bg-green-100 text-green-800',
                                        'icon' => 'mdi-check',
                                        'label' => 'Completed',
                                    ],
                                    'cancelled' => [
                                        'class' => 'bg-red-100 text-red-800',
                                        'icon' => 'mdi-close',
                                        'label' => 'Cancelled',
                                    ],
                                ];
                                $config = $statusConfig[$purchase->status] ?? $statusConfig['draft'];
                            @endphp

                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ $config['class'] }}">
                                <span class="mdi {{ $config['icon'] }}"></span>
                                {{ $config['label'] }}
                            </span>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-store text-blue-500 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Cabang</p>
                                    <p class="text-sm text-gray-600">{{ $purchase->branch_name ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-map-marker text-green-500 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Lokasi</p>
                                    <p class="text-sm text-gray-600">{{ $purchase->location_name ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-truck text-purple-500 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Supplier</p>
                                    <p class="text-sm text-gray-600">{{ $purchase->supplier_name ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-calendar text-amber-500 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Tanggal</p>
                                    <p class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($purchase->purchase_date)->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="small">
                            <div class="d-flex align-items-center mb-3">
                                <i class="mdi mdi-account me-3 text-success"></i>
                                <div>
                                    <strong>Dibuat Oleh:</strong><br>
                                    {{ $purchase->user_name ?? 'System' }}
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="mdi mdi-calendar-plus me-3 text-success"></i>
                                <div>
                                    <strong>Dibuat:</strong><br>
                                    {{ \Carbon\Carbon::parse($purchase->created_at)->diffForHumans() }}
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-calendar-edit me-3 text-info"></i>
                                <div>
                                    <strong>Diperbarui:</strong><br>
                                    {{ \Carbon\Carbon::parse($purchase->updated_at)->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-chart-bar me-2"></i>Ringkasan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="space-y-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-sm text-gray-600">Total Items:</span>
                                <span class="text-sm font-semibold">{{ $purchase->total_items }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-sm text-gray-600">Qty Besar:</span>
                                <span class="text-sm font-semibold">{{ $purchase->total_quantity_large }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-sm text-gray-600">Qty Kecil:</span>
                                <span class="text-sm font-semibold">{{ $purchase->total_quantity_small }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-sm text-gray-600">Total Qty:</span>
                                <span
                                    class="text-sm font-semibold">{{ $purchase->total_quantity_large + $purchase->total_quantity_small }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="text-sm text-gray-600">Subtotal:</span>
                                <span class="text-sm font-semibold">@currency($purchase->subtotal)</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-sm text-gray-600">Pajak:</span>
                                <span class="text-sm font-semibold">@currency($purchase->tax)</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-sm text-gray-600">Diskon:</span>
                                <span class="text-sm font-semibold">@currency($purchase->discount)</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-sm text-gray-600">Total:</span>
                                <span class="text-sm font-semibold text-success">@currency($purchase->total_amount)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" id="deleteForm" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 46px;
            padding: 10px 16px;
            font-size: 1rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 24px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        let itemIndex = 0;
        const products = @json($products);
        const purchaseItems = @json($purchase->purchasesItems ?? []);

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Pilih opsi',
                allowClear: true,
                width: '100%'
            });

            // Initialize money format
            initializeMoneyFormat();

            // Load existing items
            loadExistingItems();

            // Calculate initial totals
            calculateTotals();
        });

        function loadExistingItems() {
            purchaseItems.forEach(item => {
                addItemRow(item);
            });
        }

        function addItemRow(existingItem = null) {
            const tbody = document.getElementById('items-tbody');
            const row = document.createElement('tr');
            const index = itemIndex;

            row.innerHTML = `
            <td>
                <select name="items[${index}][product_id]" class="form-control select2-product" required>
                    <option value="">Pilih Produk</option>
                    ${products.map(product =>
                        `<option value="${product.id}"
                                    data-price-large="${product.purchase_price_large || 0}"
                                    data-price-small="${product.purchase_price_small || 0}"
                                    ${existingItem && existingItem.product_id == product.id ? 'selected' : ''}>
                                ${product.name} (${product.code})
                            </option>`
                    ).join('')}
                </select>
            </td>
            <td>
                <input type="number" name="items[${index}][qty_large]" class="form-control qty-large"
                    value="${existingItem ? existingItem.qty_large : 0}" min="0"
                    onchange="calculateRowTotal(this)" onkeyup="calculateRowTotal(this)">
            </td>
            <td>
                <input type="number" name="items[${index}][qty_small]" class="form-control qty-small"
                    value="${existingItem ? existingItem.qty_small : 0}" min="0"
                    onchange="calculateRowTotal(this)" onkeyup="calculateRowTotal(this)">
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="items[${index}][purchase_price_large]"
                        class="form-control money-input price-large text-end"
                        value="${existingItem ? formatMoney(existingItem.purchase_price_large) : ''}"
                        onchange="calculateRowTotal(this)" onkeyup="calculateRowTotal(this)">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="items[${index}][purchase_price_small]"
                        class="form-control money-input price-small text-end"
                        value="${existingItem ? formatMoney(existingItem.purchase_price_small) : ''}"
                        onchange="calculateRowTotal(this)" onkeyup="calculateRowTotal(this)">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="items[${index}][selling_price_large]"
                        class="form-control money-input selling-price-large text-end"
                        value="${existingItem ? formatMoney(existingItem.selling_price_large) : ''}">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="items[${index}][selling_price_small]"
                        class="form-control money-input selling-price-small text-end"
                        value="${existingItem ? formatMoney(existingItem.selling_price_small) : ''}">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control row-subtotal text-end bg-light"
                        value="${existingItem ? formatMoney(existingItem.subtotal) : '0'}" readonly>
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(this)">
                    <i class="mdi mdi-delete"></i>
                </button>
            </td>
        `;
            tbody.appendChild(row);

            // Initialize Select2 for product
            $(row).find('.select2-product').select2({
                placeholder: 'Pilih Produk',
                width: '100%'
            });

            // Initialize money format for new row
            initializeMoneyFormatForRow(row);

            // Add event listener for product change
            $(row).find('.select2-product').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const priceLarge = selectedOption.data('price-large') || 0;
                const priceSmall = selectedOption.data('price-small') || 0;

                const row = $(this).closest('tr');
                row.find('.price-large').val(formatMoney(priceLarge));
                row.find('.price-small').val(formatMoney(priceSmall));
                row.find('.selling-price-large').val(formatMoney(priceLarge * 1.1));
                row.find('.selling-price-small').val(formatMoney(priceSmall * 1.1));

                calculateRowTotal(this);
            });

            // Calculate row total for existing item
            if (existingItem) {
                calculateRowTotal(row.querySelector('.qty-large'));
            }

            itemIndex++;
        }

        function removeItemRow(button) {
            const row = button.closest('tr');
            row.remove();
            calculateTotals();
        }

        function calculateRowTotal(input) {
            const row = input.closest('tr');
            const qtyLarge = parseInt(row.querySelector('.qty-large').value) || 0;
            const qtySmall = parseInt(row.querySelector('.qty-small').value) || 0;
            const priceLarge = parseMoney(row.querySelector('.price-large').value) || 0;
            const priceSmall = parseMoney(row.querySelector('.price-small').value) || 0;

            const subtotal = (qtyLarge * priceLarge) + (qtySmall * priceSmall);
            row.querySelector('.row-subtotal').value = formatMoney(subtotal);

            calculateTotals();
        }

        function calculateTotals() {
            let subtotal = 0;
            const rows = document.querySelectorAll('#items-tbody tr');

            rows.forEach(row => {
                const rowSubtotal = parseMoney(row.querySelector('.row-subtotal').value) || 0;
                subtotal += rowSubtotal;
            });

            const tax = parseMoney(document.getElementById('tax').value) || 0;
            const discount = parseMoney(document.getElementById('discount').value) || 0;
            const total = subtotal + tax - discount;

            document.getElementById('summary-subtotal').textContent = 'Rp ' + formatMoney(subtotal);
            document.getElementById('summary-total').textContent = 'Rp ' + formatMoney(total);
        }

        function formatMoney(amount) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        function parseMoney(moneyString) {
            if (!moneyString) return 0;
            return parseFloat(moneyString.replace(/[^\d]/g, ''));
        }

        function initializeMoneyFormat() {
            document.querySelectorAll('.money-input').forEach(input => {
                input.addEventListener('focus', function() {
                    this.value = this.value.replace(/[^\d]/g, '');
                });

                input.addEventListener('blur', function() {
                    const value = parseFloat(this.value) || 0;
                    this.value = formatMoney(value);
                    calculateTotals();
                });

                // Format initial value
                if (input.value) {
                    const value = parseFloat(input.value.replace(/[^\d]/g, '')) || 0;
                    input.value = formatMoney(value);
                }
            });
        }

        function initializeMoneyFormatForRow(row) {
            row.querySelectorAll('.money-input').forEach(input => {
                input.addEventListener('focus', function() {
                    this.value = this.value.replace(/[^\d]/g, '');
                });

                input.addEventListener('blur', function() {
                    const value = parseFloat(this.value) || 0;
                    this.value = formatMoney(value);
                    calculateRowTotal(this);
                });
            });
        }

        // Add item button
        document.getElementById('add-item').addEventListener('click', function() {
            addItemRow();
        });

        // Event listeners for tax and discount
        document.getElementById('tax').addEventListener('blur', calculateTotals);
        document.getElementById('discount').addEventListener('blur', calculateTotals);

        function handleCancel() {
            Swal.fire({
                title: 'Perubahan belum disimpan',
                text: 'Anda memiliki perubahan yang belum disimpan. Yakin ingin kembali?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Kembali!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.history.back();
                }
            });
        }

        function confirmDelete() {
            Swal.fire({
                title: `Hapus Pembelian "{{ $purchase->purchase_number }}"?`,
                text: 'Pembelian akan dihapus permanen beserta semua itemnya. Tindakan ini tidak dapat dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }

        // Form submission
        document.getElementById('purchaseForm').addEventListener('submit', function(e) {
            const items = document.querySelectorAll('#items-tbody tr');
            if (items.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Minimal satu item harus ditambahkan'
                });
                return;
            }

            // Show loading state
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML =
                '<i class="mdi mdi-loading mdi-spin me-2"></i>Memperbarui...';
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: '{{ $errors->first() }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
@endpush
