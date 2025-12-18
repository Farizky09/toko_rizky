@extends('layouts.master')

@section('title', 'Tambah Pembelian Baru')

@push('styles')
    {{-- Tambahkan style untuk Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Style kustom untuk Select2 agar sesuai dengan form-control-lg */
        .select2-container .select2-selection--single {
            height: 48px !important;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
            padding-left: 8px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
            right: 8px;
        }

        .select2-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Style untuk input group (Rp) */
        .input-group-text {
            background-color: #f9fafb;
            border: 1px solid #d1d5db;
            color: #6b7280;
            border-right: 0;
            border-radius: 0.5rem 0 0 0.5rem;
        }

        .input-group .form-control {
            border-left: 0;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .table th {
            background-color: #f9fafb;
            font-weight: 600;
        }

        /* Fix tinggi input di dalam tabel */
        #items-tbody .form-control {
            height: calc(2.25rem + 2px);
            /* Default form-control height */
            padding: 0.375rem 0.75rem;
        }

        #items-tbody .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
            padding: 0.25rem 0.5rem;
        }

        #items-tbody .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
            padding-left: 4px;
        }

        #items-tbody .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        #items-tbody .input-group-text {
            padding: 0.375rem 0.75rem;
        }
    </style>
@endpush

@section('content')
    <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <div class="mb-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-3">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">
                            <span class="mdi mdi-cart-arrow-down text-xl text-blue-600"></span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Tambah Pembelian Baru</h1>
                            <p class="text-gray-600 mt-1">Tambah data pembelian baru ke dalam sistem</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('purchases.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <span class="mdi mdi-arrow-left text-lg"></span>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/60">

            <div class="border-b border-gray-200 pb-4 mb-6">
                <h5 class="card-title mb-0 text-gray-900 font-bold text-lg">
                    <i class="mdi mdi-plus-box-outline me-2 text-blue-600"></i>Form Tambah Pembelian
                </h5>
            </div>

            <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="purchase_number" class="form-label fw-semibold text-gray-700">
                            Nomor Pembelian
                        </label>
                        <input type="text" id="purchase_number" value="{{ $purchaseNumber }}"
                            class="form-control form-control-lg bg-gray-100 border-gray-200" readonly>
                        <div class="form-text text-gray-500 mt-2">
                            <small class="flex items-center gap-1">
                                <i class="mdi mdi-information-outline"></i>
                                Nomor pembelian digenerate otomatis
                            </small>
                        </div>
                    </div>

                    <div>
                        <label for="purchase_date" class="form-label fw-semibold text-gray-700">
                            Tanggal Pembelian <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="purchase_date" name="purchase_date"
                            value="{{ old('purchase_date', date('Y-m-d')) }}"
                            class="form-control form-control-lg @error('purchase_date') border-red-300 @enderror focus:border-blue-500 focus:ring-blue-500"
                            required>
                        @error('purchase_date')
                            <div class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="mdi mdi-alert-circle-outline"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="branch_id" class="form-label fw-semibold text-gray-700">
                            Cabang <span class="text-red-500">*</span>
                        </label>
                        <select id="branch_id" name="branch_id"
                            class="form-control form-control-lg select2 @error('branch_id') border-red-300 @enderror focus:border-blue-500 focus:ring-blue-500"
                            required>
                            <option value="">Pilih Cabang</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <div class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="mdi mdi-alert-circle-outline"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div>
                        <label for="location_id" class="form-label fw-semibold text-gray-700">
                            Lokasi <span class="text-red-500">*</span>
                        </label>
                        <select id="location_id" name="location_id"
                            class="form-control form-control-lg select2 @error('location_id') border-red-300 @enderror focus:border-blue-500 focus:ring-blue-500"
                            required>
                            <option value="">Pilih Cabang Terlebih Dahulu</option>
                        </select>
                        @error('location_id')
                            <div class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="mdi mdi-alert-circle-outline"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div>
                        <label for="supplier_id" class="form-label fw-semibold text-gray-700">
                            Supplier <span class="text-red-500">*</span>
                        </label>
                        <select id="supplier_id" name="supplier_id"
                            class="form-control form-control-lg select2 @error('supplier_id') border-red-300 @enderror focus:border-blue-500 focus:ring-blue-500"
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
                            <div class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="mdi mdi-alert-circle-outline"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="font-weight-bold text-gray-900 text-lg">
                            <i class="mdi mdi-cart-plus me-2 text-blue-600"></i>Items Pembelian
                        </h5>
                        <button type="button" class="btn btn-success" id="add-item">
                            <i class="mdi mdi-plus-circle-outline me-2"></i>Tambah Item
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="items-table"
                            style="min-width: 1600px;">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th width="20%" class="py-3 px-4 font-semibold text-gray-700">Produk</th>
                                    <th width="7%" class="py-3 px-4 font-semibold text-gray-700 text-center">Qty Besar
                                    </th>
                                    <th width="7%" class="py-3 px-4 font-semibold text-gray-700 text-center">Qty Kecil
                                    </th>
                                    <th width="13%" class="py-3 px-4 font-semibold text-gray-700 text-end">Harga Beli
                                        Besar</th>
                                    <th width="13%" class="py-3 px-4 font-semibold text-gray-700 text-end">Harga Beli
                                        Kecil</th>
                                    <th width="13%" class="py-3 px-4 font-semibold text-gray-700 text-end">Harga Jual
                                        Besar</th>
                                    <th width="13%" class="py-3 px-4 font-semibold text-gray-700 text-end">Harga Jual
                                        Kecil</th>
                                    <th width="13%" class="py-3 px-4 font-semibold text-gray-700 text-end">Subtotal
                                    </th>
                                    <th width="5%" class="py-3 px-4 font-semibold text-gray-700 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="items-tbody">
                                <tr id="no-items-row">
                                    <td colspan="10" class="text-center text-gray-500 py-4">
                                        <i class="mdi mdi-information-outline me-1"></i>
                                        Belum ada item yang ditambahkan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="notes" class="form-label fw-semibold text-gray-700">Catatan</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="form-control @error('notes') border-red-300 @enderror focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Tambahkan catatan jika diperlukan">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="mdi mdi-alert-circle-outline"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div>
                        <div class="card bg-gray-50 border border-gray-200">
                            <div class="card-header bg-white py-3 border-b border-gray-200">
                                <h6 class="card-title mb-0 font-semibold text-gray-900">Ringkasan Pembelian</h6>
                            </div>
                            <div class="card-body">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span id="summary-subtotal" class="font-semibold text-gray-900">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Pajak:</span>
                                        <div class="w-40">
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" id="tax" name="tax_display"
                                                    value="{{ old('tax', 0) }}"
                                                    class="form-control money-input text-end">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Diskon:</span>
                                        <div class="w-40">
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" id="discount" name="discount_display"
                                                    value="{{ old('discount', 0) }}"
                                                    class="form-control money-input text-end">
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="flex justify-between items-center">
                                        <strong class="text-gray-900 text-lg">Total Amount:</strong>
                                        <strong id="summary-total" class="text-blue-600 text-lg">Rp 0</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end items-center gap-3 pt-6 border-t border-gray-200">
                    <button type="button" onclick="handleCancel()" class="btn btn-outline-secondary btn-lg px-6 py-3">
                        <i class="mdi mdi-close-circle-outline me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success btn-lg px-6 py-3" id="submitBtn">
                        <i class="mdi mdi-content-save-check me-2"></i>Simpan Pembelian
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection

@push('scripts')
    {{-- Tambahkan script Select2, SweetAlert, dll --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let itemIndex = 0;
        const products = @json($products);
        const locationsByBranch = @json($locations);
        let oldLocationId = '{{ old('location_id') }}';

        document.addEventListener('DOMContentLoaded', function() {
            initializeSelect2();
            initializeMoneyFormat();


            $('#branch_id').on('change', function(e) {
                updateLocationDropdown();
            });

            // Panggil sekali saat load untuk menangani 'old data'
            updateLocationDropdown();
            // ==========================================================

            // Sembunyikan baris "no items" jika ada old data
            const oldItems = @json(old('items'));
            if (oldItems && oldItems.length > 0) {
                oldItems.forEach(item => {
                    addItemRow(item);
                });
                hideNoItemsRow();
            } else {
                showNoItemsRow();
            }

            calculateTotals();

            // Event listener untuk tombol tambah item
            document.getElementById('add-item').addEventListener('click', function(e) {
                e.preventDefault();
                addItemRow();
                hideNoItemsRow();
            });

            // Event listeners untuk tax dan discount
            document.getElementById('tax').addEventListener('input', calculateTotals);
            document.getElementById('discount').addEventListener('input', calculateTotals);

            // --- Form submission listener ---
            document.getElementById('purchaseForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const items = document.querySelectorAll('#items-tbody tr.item-row');
                if (items.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        text: 'Minimal satu item harus ditambahkan',
                        confirmButtonColor: '#d33'
                    });
                    return;
                }

                // Validasi field wajib
                const requiredFields = ['branch_id', 'location_id', 'supplier_id', 'purchase_date'];
                let hasError = false;
                let firstErrorEl = null;

                requiredFields.forEach(field => {
                    const element = document.getElementById(field);
                    if (!element || !element.value) {
                        const label = $(element).prev('label').text() || field;
                        if (element) {
                            $(element).closest('div').find('.select2-selection').css('border-color',
                                '#f87171');
                        } else {
                            $(`#${field}`).css('border-color', '#f87171');
                        }
                        if (!firstErrorEl) firstErrorEl = element;
                        hasError = true;
                    } else {
                        $(element).closest('div').find('.select2-selection').css('border-color',
                            '#d1d5db');
                        $(`#${field}`).css('border-color', '#d1d5db');
                    }
                });

                if (hasError) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        text: 'Harap lengkapi semua field yang wajib diisi (Cabang, Lokasi, Supplier, Tanggal).',
                        confirmButtonColor: '#d33'
                    });
                    if (firstErrorEl) firstErrorEl.focus();
                    return;
                }

                // Validasi items
                let itemsValid = true;
                let errorMessage = '';

                items.forEach((row, index) => {
                    const productSelect = row.querySelector('.select2-product');
                    const qtyLarge = row.querySelector('.qty-large').value;
                    const qtySmall = row.querySelector('.qty-small').value;

                    if (!productSelect || !productSelect.value) {
                        itemsValid = false;
                        errorMessage = `Pilih produk untuk item ${index + 1}`;
                    } else if (parseInt(qtyLarge) === 0 && parseInt(qtySmall) === 0) {
                        itemsValid = false;
                        errorMessage = `Quantity tidak boleh 0 untuk item ${index + 1}`;
                    }
                });

                if (!itemsValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Item Gagal',
                        text: errorMessage,
                        confirmButtonColor: '#d33'
                    });
                    return;
                }

                // Hapus input hidden lama jika ada
                $(this).find('input[type="hidden"]').remove();

                const form = this;

                // Convert money values di items
                document.querySelectorAll('.money-input').forEach(input => {
                    const numericValue = parseCurrency(input.value);
                    const originalName = $(input).attr('name').replace('_display', '');
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = originalName;
                    hiddenInput.value = numericValue;
                    form.appendChild(hiddenInput);
                });

                // Convert tax dan discount
                const taxInput = document.getElementById('tax');
                if (taxInput) {
                    const taxHidden = document.createElement('input');
                    taxHidden.type = 'hidden';
                    taxHidden.name = 'tax';
                    taxHidden.value = parseCurrency(taxInput.value);
                    form.appendChild(taxHidden);
                }

                const discountInput = document.getElementById('discount');
                if (discountInput) {
                    const discountHidden = document.createElement('input');
                    discountHidden.type = 'hidden';
                    discountHidden.name = 'discount';
                    discountHidden.value = parseCurrency(discountInput.value);
                    form.appendChild(discountHidden);
                }

                $(form).find('.money-input').prop('disabled', true);
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Menyimpan...';

                Swal.fire({
                    title: 'Menyimpan data pembelian...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                form.submit();
            });
        });

        function initializeSelect2() {
            $('.select2').select2({
                placeholder: 'Pilih opsi',
                allowClear: true,
                width: '100%'
            });
        }

        // Fungsi ini sekarang akan dipanggil oleh listener Select2
        function updateLocationDropdown() {
            const branchId = document.getElementById('branch_id').value;
            const locationSelect = $('#location_id');

            // Kode ini yang mengambil data dari variabel global
            const locations = locationsByBranch[branchId] || [];

            locationSelect.empty().append('<option value="">Pilih Lokasi</option>');

            if (locations.length > 0) {
                locations.forEach(location => {
                    const isSelected = (location.id == oldLocationId);
                    locationSelect.append(new Option(location.name, location.id, isSelected, isSelected));
                });
            } else {
                if (branchId) {
                    locationSelect.empty().append('<option value="">Tidak ada lokasi di cabang ini</option>');
                } else {
                    locationSelect.empty().append('<option value="">Pilih Cabang Terlebih Dahulu</option>');
                }
            }

            if (oldLocationId) {
                oldLocationId = null;
            }
            locationSelect.trigger('change');
        }

        // Modifikasi addItemRow untuk menerima data (untuk old input)
        function addItemRow(itemData = null) {
            const tbody = document.getElementById('items-tbody');
            if (!tbody) {
                console.error('Items tbody not found!');
                return;
            }
            const row = document.createElement('tr');
            row.className = 'border-b border-gray-200 hover:bg-gray-50 item-row';
            const qtyLargeVal = itemData ? itemData.qty_large : '0';
            const qtySmallVal = itemData ? itemData.qty_small : '0';
            const priceLargeVal = itemData ? formatCurrencyDisplay(itemData.purchase_price_large) : '0';
            const priceSmallVal = itemData ? formatCurrencyDisplay(itemData.purchase_price_small) : '0';
            const sellingLargeVal = itemData ? formatCurrencyDisplay(itemData.selling_price_large) : '0';
            const sellingSmallVal = itemData ? formatCurrencyDisplay(itemData.selling_price_small) : '0';

            const subtotalVal = (qtyLargeVal * (itemData ? itemData.purchase_price_large : 0)) + (qtySmallVal * (itemData ?
                itemData.purchase_price_small : 0));
            row.innerHTML = `
                <td class="py-3 px-4">
                    <select name="items[${itemIndex}][product_id]" class="form-control select2-product" required>
                        <option value="">Pilih Produk</option>
                        ${products.map(product =>
                            `<option value="${product.id}"
                                            ${itemData && product.id == itemData.product_id ? 'selected' : ''}
                                            data-price-large="${product.purchase_price_large || 0}"
                                            data-price-small="${product.purchase_price_small || 0}"
                                            data-selling-large="${product.selling_price_large || 0}"
                                            data-selling-small="${product.selling_price_small || 0}">
                                            ${product.name} (${product.code})
                                        </option>`
                        ).join('')}
                    </select>
                </td>
                <td class="py-3 px-4">
                    <input type="number" name="items[${itemIndex}][qty_large]"
                        class="form-control qty-large text-center border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        value="${qtyLargeVal}" min="0" step="1"
                        onfocus="handleQtyFocus(this)"
                        oninput="calculateRowTotal(this)">
                </td>
                <td class="py-3 px-4">
                    <input type="number" name="items[${itemIndex}][qty_small]"
                        class="form-control qty-small text-center border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        value="${qtySmallVal}" min="0" step="1"
                        onfocus="handleQtyFocus(this)"
                        oninput="calculateRowTotal(this)">
                </td>
                <td class="py-3 px-4">
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" name="items[${itemIndex}][purchase_price_large_display]"
                            class="form-control money-input price-large text-end"
                            value="${priceLargeVal}" oninput="formatCurrency(this)" onblur="calculateRowTotal(this)">
                    </div>
                </td>
                <td class="py-3 px-4">
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" name="items[${itemIndex}][purchase_price_small_display]"
                            class="form-control money-input price-small text-end"
                            value="${priceSmallVal}" oninput="formatCurrency(this)" onblur="calculateRowTotal(this)">
                    </div>
                </td>
                <td class="py-3 px-4">
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" name="items[${itemIndex}][selling_price_large_display]"
                            class="form-control money-input selling-price-large text-end"
                            value="${sellingLargeVal}" oninput="formatCurrency(this)">
                    </div>
                </td>
                <td class="py-3 px-4">
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" name="items[${itemIndex}][selling_price_small_display]"
                            class="form-control money-input selling-price-small text-end"
                            value="${sellingSmallVal}" oninput="formatCurrency(this)">
                    </div>
                </td>
                <td class="py-3 px-4">
                    <div class="input-group">
                        <span class="input-group-text bg-gray-100">Rp</span>
                        <input type="text" class="form-control row-subtotal text-end bg-gray-100"
                            value="${formatCurrencyDisplay(subtotalVal)}" readonly>
                    </div>
                </td>
                <td class="py-3 px-4 text-center">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(this)">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
            $(row).find('.select2-product').select2({
                placeholder: 'Pilih Produk',
                width: '100%',
                dropdownParent: $(row).find('td').first()
            });
            $(row).find('.select2-product').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const priceLarge = selectedOption.data('price-large') || 0;
                const priceSmall = selectedOption.data('price-small') || 0;
                const sellingLarge = selectedOption.data('selling-large') || 0;
                const sellingSmall = selectedOption.data('selling-small') || 0;
                const row = $(this).closest('tr');
                row.find('.price-large').val(formatCurrencyDisplay(priceLarge)).trigger('input');
                row.find('.price-small').val(formatCurrencyDisplay(priceSmall)).trigger('input');
                row.find('.selling-price-large').val(formatCurrencyDisplay(sellingLarge)).trigger('input');
                row.find('.selling-price-small').val(formatCurrencyDisplay(sellingSmall)).trigger('input');
                calculateRowTotal(this);
            });
            itemIndex++;
        }

        function handleQtyFocus(input) {
            if (input.value === '0') {
                setTimeout(() => {
                    input.select();
                }, 10);
            }
        }

        function removeItemRow(button) {
            const row = button.closest('tr');
            row.remove();
            calculateTotals();
            if (document.querySelectorAll('#items-tbody tr.item-row').length === 0) {
                showNoItemsRow();
            }
        }

        function showNoItemsRow() {
            const tbody = document.getElementById('items-tbody');
            tbody.innerHTML = `<tr id="no-items-row">
                                    <td colspan="10" class="text-center text-gray-500 py-4">
                                        <i class="mdi mdi-information-outline me-1"></i>
                                        Belum ada item yang ditambahkan.
                                    </td>
                                </tr>`;
        }

        function hideNoItemsRow() {
            const noItemsRow = document.getElementById('no-items-row');
            if (noItemsRow) {
                noItemsRow.remove();
            }
        }

        function calculateRowTotal(input) {
            const row = input.closest('tr');
            const qtyLarge = parseInt(row.querySelector('.qty-large').value) || 0;
            const qtySmall = parseInt(row.querySelector('.qty-small').value) || 0;
            const priceLarge = parseCurrency(row.querySelector('.price-large').value) || 0;
            const priceSmall = parseCurrency(row.querySelector('.price-small').value) || 0;
            const subtotal = (qtyLarge * priceLarge) + (qtySmall * priceSmall);
            row.querySelector('.row-subtotal').value = formatCurrencyDisplay(subtotal);
            calculateTotals();
        }

        function calculateTotals() {
            let subtotal = 0;
            const rows = document.querySelectorAll('#items-tbody tr.item-row');
            rows.forEach(row => {
                const rowSubtotal = parseCurrency(row.querySelector('.row-subtotal').value) || 0;
                subtotal += rowSubtotal;
            });
            const tax = parseCurrency(document.getElementById('tax').value) || 0;
            const discount = parseCurrency(document.getElementById('discount').value) || 0;
            const total = subtotal + tax - discount;
            document.getElementById('summary-subtotal').textContent = 'Rp ' + formatCurrencyDisplay(subtotal);
            document.getElementById('summary-total').textContent = 'Rp ' + formatCurrencyDisplay(total);
        }

        function formatCurrency(input) {
            const cursorPosition = input.selectionStart;
            const originalLength = input.value.length;
            let value = input.value.replace(/[^\d]/g, '');
            let number = parseInt(value) || 0;
            const formatted = new Intl.NumberFormat('id-ID').format(number);
            input.value = formatted;
            const newCursorPosition = cursorPosition + (formatted.length - originalLength);
            input.setSelectionRange(newCursorPosition, newCursorPosition);
            if (input.classList.contains('price-large') || input.classList.contains('price-small')) {
                calculateRowTotal(input);
            }
        }

        function formatCurrencyDisplay(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }

        function parseCurrency(currencyString) {
            if (!currencyString) return 0;
            return parseInt(currencyString.replace(/[^\d]/g, '')) || 0;
        }

        function initializeMoneyFormat() {
            const taxInput = document.getElementById('tax');
            const discountInput = document.getElementById('discount');
            [taxInput, discountInput].forEach(input => {
                if (input) {
                    input.addEventListener('input', function() {
                        formatCurrency(this);
                        calculateTotals();
                    });
                    if (input.value && input.value !== '0') {
                        const numericValue = parseInt(input.value) || 0;
                        input.value = formatCurrencyDisplay(numericValue);
                    }
                }
            });
        }

        function handleCancel() {
            const items = document.querySelectorAll('#items-tbody tr.item-row');
            const hasData = items.length > 0 ||
                document.getElementById('branch_id').value ||
                document.getElementById('supplier_id').value;
            if (hasData) {
                Swal.fire({
                    title: 'Batalkan Pembelian?',
                    text: 'Data yang sudah diinput akan hilang. Yakin ingin membatalkan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('purchases.index') }}";
                    }
                });
            } else {
                window.location.href = "{{ route('purchases.index') }}";
            }
        }

        // Ekspos fungsi ke global scope
        window.addItemRow = addItemRow;
        window.removeItemRow = removeItemRow;
        window.calculateRowTotal = calculateRowTotal;
        window.formatCurrency = formatCurrency;
        window.handleCancel = handleCancel;
        window.handleQtyFocus = handleQtyFocus;
    </script>
@endpush
