@extends('layouts.master')

@section('title', 'Tambah Produk Baru')

@section('content')
    <div class="container-fluid px-6 py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="h3 mb-2 text-gray-900 font-weight-bold">Tambah Produk Baru</h1>
                <p class="text-muted">Tambah data produk baru ke dalam sistem inventory</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-2"></i>Kembali ke Daftar
            </a>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent py-3">
                <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                    <i class="mdi mdi-plus-box-outline me-2"></i>Form Tambah Produk
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" id="productForm">
                    @csrf

                    <div class="row">
                        <!-- Code Field -->
                        <div class="col-md-6 mb-4">
                            <label for="code" class="form-label fw-semibold text-gray-700">
                                Kode Produk <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="code" name="code" value="{{ old('code') }}"
                                placeholder="Contoh: PRD-001, B001, PROD-2024"
                                class="form-control form-control-lg @error('code') is-invalid @enderror" required
                                maxlength="50">

                            @error('code')
                                <div class="invalid-feedback d-block">
                                    <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                </div>
                            @enderror

                            <div class="form-text text-muted mt-2">
                                <small>
                                    <i class="mdi mdi-information-outline me-1"></i>
                                    Kode unik untuk identifikasi produk (maksimal 50 karakter)
                                </small>
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label fw-semibold text-gray-700">
                                Nama Produk <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                placeholder="Contoh: Beras Premium, Gula Pasir, Minyak Goreng"
                                class="form-control form-control-lg @error('name') is-invalid @enderror" required
                                maxlength="255">

                            @error('name')
                                <div class="invalid-feedback d-block">
                                    <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Category Field -->
                        <div class="col-md-4 mb-4">
                            <label for="category_id" class="form-label fw-semibold text-gray-700">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            <select id="category_id" name="category_id"
                                class="form-control form-control-lg select2 @error('category_id') is-invalid @enderror"
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category_id')
                                <div class="invalid-feedback d-block">
                                    <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Status Field -->
                        <div class="col-md-4 mb-4">
                            <label for="status" class="form-label fw-semibold text-gray-700">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select id="status" name="status"
                                class="form-control form-control-lg @error('status') is-invalid @enderror" required>
                                <option value="">Pilih Status</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>

                            @error('status')
                                <div class="invalid-feedback d-block">
                                    <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Min Stock Field -->
                        <div class="col-md-4 mb-4">
                            <label for="min_stock" class="form-label fw-semibold text-gray-700">
                                Stok Minimum <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', 0) }}"
                                placeholder="Contoh: 10, 50, 100"
                                class="form-control form-control-lg @error('min_stock') is-invalid @enderror" required
                                min="0" step="1">

                            @error('min_stock')
                                <div class="invalid-feedback d-block">
                                    <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Unit Large Field -->
                        <div class="col-md-4 mb-4">
                            <label for="unit_large_id" class="form-label fw-semibold text-gray-700">
                                Satuan Besar <span class="text-danger">*</span>
                            </label>
                            <select id="unit_large_id" name="unit_large_id"
                                class="form-control form-control-lg select2 @error('unit_large_id') is-invalid @enderror"
                                required>
                                <option value="">Pilih Satuan Besar</option>
                                @foreach ($unitLarge as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ old('unit_large_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->abbreviation }})
                                    </option>
                                @endforeach
                            </select>

                            @error('unit_large_id')
                                <div class="invalid-feedback d-block">
                                    <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Unit Small Field -->
                        <div class="col-md-4 mb-4">
                            <label for="unit_small_id" class="form-label fw-semibold text-gray-700">
                                Satuan Kecil <span class="text-danger">*</span>
                            </label>
                            <select id="unit_small_id" name="unit_small_id"
                                class="form-control form-control-lg select2 @error('unit_small_id') is-invalid @enderror"
                                required>
                                <option value="">Pilih Satuan Kecil</option>
                                @foreach ($unitSmall as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ old('unit_small_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->abbreviation }})
                                    </option>
                                @endforeach
                            </select>

                            @error('unit_small_id')
                                <div class="invalid-feedback d-block">
                                    <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Conversion Field -->
                        <div class="col-md-4 mb-4">
                            <label for="conversion" class="form-label fw-semibold text-gray-700">
                                Konversi <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="conversion" name="conversion" value="{{ old('conversion', 1) }}"
                                placeholder="Contoh: 10, 1000, 0.5"
                                class="form-control form-control-lg @error('conversion') is-invalid @enderror" required
                                min="0.01" step="0.01">

                            @error('conversion')
                                <div class="invalid-feedback d-block">
                                    <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                </div>
                            @enderror

                            <div class="form-text text-muted mt-2">
                                <small>
                                    <i class="mdi mdi-information-outline me-1"></i>
                                    1 Satuan Besar = ? Satuan Kecil
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold text-gray-700">
                            Deskripsi Produk
                        </label>
                        <textarea id="description" name="description" rows="4"
                            placeholder="Contoh: Beras premium kualitas terbaik dengan tekstur pulen dan aroma wangi..."
                            class="form-control form-control-lg @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                        @error('description')
                            <div class="invalid-feedback d-block">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror

                        <div class="form-text text-muted mt-2">
                            <small>
                                <i class="mdi mdi-information-outline me-1"></i>
                                Deskripsi tambahan tentang produk (opsional, maksimal 1000 karakter)
                            </small>
                        </div>
                    </div>

                    <!-- Additional Info Section -->
                    <div class="alert alert-info border-0 bg-light-info">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-information-outline me-3 fs-4 text-info"></i>
                            <div>
                                <h6 class="alert-heading mb-2 text-info">Informasi Produk</h6>
                                <p class="mb-0 small">
                                    Produk adalah barang yang dikelola dalam sistem inventory. Setiap produk harus memiliki
                                    satuan besar dan kecil dengan konversi yang jelas.
                                    Contoh: 1 Karung (Satuan Besar) = 25 Kg (Satuan Kecil) dengan konversi 25.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end align-items-center gap-3 pt-4 border-top">
                        <button type="button" onclick="handleCancel()" class="btn btn-outline-secondary btn-lg px-5">
                            <i class="mdi mdi-close-circle-outline me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                            <i class="mdi mdi-content-save-check me-2"></i>Simpan Produk
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
            height: 48px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 46px !important;
            padding-left: 1rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Pilih opsi",
                allowClear: true,
                width: '100%'
            });

            const form = document.getElementById('productForm');
            const codeInput = document.getElementById('code');
            const nameInput = document.getElementById('name');
            const categoryInput = $('#category_id');
            const unitLargeInput = $('#unit_large_id');
            const unitSmallInput = $('#unit_small_id');
            const conversionInput = document.getElementById('conversion');
            const minStockInput = document.getElementById('min_stock');
            const statusInput = document.getElementById('status');
            const submitBtn = document.getElementById('submitBtn');

            // Auto-uppercase for code field
            codeInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Real-time validation for required fields
            const requiredFields = [codeInput, nameInput, conversionInput, minStockInput, statusInput];

            requiredFields.forEach(field => {
                if (field) {
                    field.addEventListener('input', function() {
                        const value = this.type === 'select-one' ? this.value : this.value.trim();
                        if (value) {
                            this.classList.remove('is-invalid');
                            this.classList.add('is-valid');
                        } else {
                            this.classList.remove('is-valid');
                        }
                    });
                }
            });

            // For Select2 fields
            [categoryInput, unitLargeInput, unitSmallInput].forEach(select => {
                select.on('change', function() {
                    if (this.value) {
                        $(this).removeClass('is-invalid').addClass('is-valid');
                    } else {
                        $(this).removeClass('is-valid');
                    }
                });
            });

            // Form submission handling
            form.addEventListener('submit', function(e) {
                let hasError = false;

                // Check required inputs
                requiredFields.forEach(field => {
                    if (field) {
                        const value = field.type === 'select-one' ? field.value : field.value
                    .trim();
                        if (!value) {
                            field.classList.add('is-invalid');
                            if (!hasError) {
                                field.focus();
                                hasError = true;
                            }
                        }
                    }
                });

                // Check Select2 fields
                const select2Fields = [categoryInput, unitLargeInput, unitSmallInput];
                select2Fields.forEach(select => {
                    if (!select.val()) {
                        select.addClass('is-invalid');
                        if (!hasError) {
                            select.focus();
                            hasError = true;
                        }
                    }
                });

                if (hasError) {
                    e.preventDefault();
                    return;
                }

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Menyimpan...';
            });
        });

        function handleCancel() {
            const codeInput = document.getElementById('code');
            const nameInput = document.getElementById('name');
            const currentCode = codeInput.value.trim();
            const currentName = nameInput.value.trim();

            if (currentCode || currentName) {
                Alert.confirm(
                    'Data belum disimpan',
                    'Anda memiliki data yang belum disimpan. Yakin ingin kembali?',
                    function() {
                        window.history.back();
                    }
                );
            } else {
                window.history.back();
            }
        }

        @if (session('success'))
            Alert.success('{{ session('success') }}');
        @endif

        @if (session('error'))
            Alert.error('{{ session('error') }}');
        @endif

        @if ($errors->any())
            Alert.error('{{ $errors->first() }}');
        @endif
    </script>
@endpush
