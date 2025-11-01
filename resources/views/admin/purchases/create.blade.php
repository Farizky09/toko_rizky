@extends('layouts.master')

@section('title', 'Tambah Pembelian Baru')

@section('content')
    <div class="container-fluid px-6 py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="h3 mb-2 text-gray-900 font-weight-bold">Tambah Pembelian Baru</h1>
                <p class="text-muted">Tambah data pembelian baru ke dalam sistem</p>
            </div>
            <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-2"></i>Kembali ke Daftar
            </a>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent py-3">
                <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                    <i class="mdi mdi-plus-box-outline me-2"></i>Form Tambah Pembelian
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
                    @csrf

                    <!-- Header Information -->
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <label for="purchase_number" class="form-label fw-semibold text-gray-700">
                                Nomor Pembelian
                            </label>
                            <input type="text" id="purchase_number" value="{{ $purchaseNumber }}"
                                class="form-control form-control-lg bg-light" readonly>
                            <div class="form-text text-muted mt-2">
                                <small>Nomor pembelian akan digenerate otomatis</small>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <label for="purchase_date" class="form-label fw-semibold text-gray-700">
                                Tanggal Pembelian <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="purchase_date" name="purchase_date"
                                value="{{ old('purchase_date', date('Y-m-d')) }}"
                                class="form-control form-control-lg @error('purchase_date') is-invalid @enderror" required>

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
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed
                                </option>
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
                                        {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
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
                                        {{ old('location_id') == $location->id ? 'selected' : '' }}>
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
                                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                                        <!-- Items will be added dynamically -->
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
                                    placeholder="Tambahkan catatan jika diperlukan">{{ old('notes') }}</textarea>

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
                                                value="{{ old('tax', 0) }}"
                                                class="form-control form-control-sm money-input text-end">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Diskon:</span>
                                        <div class="input-group" style="width: 150px;">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" id="discount" name="discount"
                                                value="{{ old('discount', 0) }}"
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

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end align-items-center gap-3 pt-4 border-top">
                        <button type="button" onclick="handleCancel()" class="btn btn-outline-secondary btn-lg px-5">
                            <i class="mdi mdi-close-circle-outline me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                            <i class="mdi mdi-content-save-check me-2"></i>Simpan Pembelian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Pilih opsi',
                allowClear: true,
                width: '100%'
            });

            // Initialize money format
            initializeMoneyFormat();

            // Add first item
            addItemRow();

            // Calculate initial totals
            calculateTotals();
        });

        function addItemRow() {
            const tbody = document.getElementById('items-tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
            <td>
                <select name="items[${itemIndex}][product_id]" class="form-control select2-product" required>
                    <option value="">Pilih Produk</option>
                    ${products.map(product =>
                        `<option value="${product.id}" data-price-large="${product.purchase_price_large || 0}" data-price-small="${product.purchase_price_small || 0}">
                                    ${product.name} (${product.code})
                                </option>`
                    ).join('')}
                </select>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][qty_large]" class="form-control qty-large"
                    value="0" min="0" onchange="calculateRowTotal(this)" onkeyup="calculateRowTotal(this)">
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][qty_small]" class="form-control qty-small"
                    value="0" min="0" onchange="calculateRowTotal(this)" onkeyup="calculateRowTotal(this)">
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="items[${itemIndex}][purchase_price_large]"
                        class="form-control money-input price-large text-end"
                        onchange="calculateRowTotal(this)" onkeyup="calculateRowTotal(this)">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="items[${itemIndex}][purchase_price_small]"
                        class="form-control money-input price-small text-end"
                        onchange="calculateRowTotal(this)" onkeyup="calculateRowTotal(this)">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="items[${itemIndex}][selling_price_large]"
                        class="form-control money-input selling-price-large text-end">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="items[${itemIndex}][selling_price_small]"
                        class="form-control money-input selling-price-small text-end">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control row-subtotal text-end bg-light" readonly>
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
                row.find('.selling-price-large').val(formatMoney(priceLarge * 1.1)); // Example markup
                row.find('.selling-price-small').val(formatMoney(priceSmall * 1.1)); // Example markup

                calculateRowTotal(this);
            });

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

            document.getElementById('summary-subtotal').textContent = formatMoney(subtotal);
            document.getElementById('summary-total').textContent = formatMoney(total);
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
                    const value = parseFloat(input.value) || 0;
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

        // Event listeners for tax and discount
        document.getElementById('tax').addEventListener('blur', calculateTotals);
        document.getElementById('discount').addEventListener('blur', calculateTotals);

        // Add item button
        document.getElementById('add-item').addEventListener('click', addItemRow);

        function handleCancel() {
            if (confirm('Anda yakin ingin membatalkan? Data yang sudah diinput akan hilang.')) {
                window.history.back();
            }
        }

        // Form submission
        document.getElementById('purchaseForm').addEventListener('submit', function(e) {
            const items = document.querySelectorAll('#items-tbody tr');
            if (items.length === 0) {
                e.preventDefault();
                alert('Minimal satu item harus ditambahkan');
                return;
            }

            // Convert money values to numbers before submission
            document.querySelectorAll('.money-input').forEach(input => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = input.name + '_numeric';
                hiddenInput.value = parseMoney(input.value);
                this.appendChild(hiddenInput);
            });

            // Show loading state
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML =
                '<i class="mdi mdi-loading mdi-spin me-2"></i>Menyimpan...';
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
