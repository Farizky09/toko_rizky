@extends('layouts.master')

@section('title', 'Edit Produk - ' . $data->name)

@section('content')
    <div class="container-fluid px-6 py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="h3 mb-2 text-gray-900 font-weight-bold">Edit Produk</h1>
                <p class="text-muted">Perbarui informasi produk yang sudah ada</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
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
                            <i class="mdi mdi-pencil-box-outline me-2"></i>Form Edit Produk
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.update', $data->id) }}" method="POST" id="productForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Code Field -->
                                <div class="col-md-6 mb-4">
                                    <label for="code" class="form-label fw-semibold text-gray-700">
                                        Kode Produk <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="code" name="code"
                                        value="{{ old('code', $data->code) }}"
                                        placeholder="Contoh: PRD-001, B001, PROD-2024"
                                        class="form-control form-control-lg @error('code') is-invalid @enderror" required
                                        maxlength="50">

                                    @error('code')
                                        <div class="invalid-feedback d-block">
                                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Name Field -->
                                <div class="col-md-6 mb-4">
                                    <label for="name" class="form-label fw-semibold text-gray-700">
                                        Nama Produk <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $data->name) }}"
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
                                                {{ old('category_id', $data->category_id) == $category->id ? 'selected' : '' }}>
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
                                        <option value="active"
                                            {{ old('status', $data->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive"
                                            {{ old('status', $data->status) == 'inactive' ? 'selected' : '' }}>Nonaktif
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
                                    <input type="number" id="min_stock" name="min_stock"
                                        value="{{ old('min_stock', $data->min_stock) }}" placeholder="Contoh: 10, 50, 100"
                                        class="form-control form-control-lg @error('min_stock') is-invalid @enderror"
                                        required min="0" step="1">

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
                                                {{ old('unit_large_id', $data->unit_large_id) == $unit->id ? 'selected' : '' }}>
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
                                                {{ old('unit_small_id', $data->unit_small_id) == $unit->id ? 'selected' : '' }}>
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
                                    <input type="number" id="conversion" name="conversion"
                                        value="{{ old('conversion', $data->conversion) }}"
                                        placeholder="Contoh: 10, 1000, 0.5"
                                        class="form-control form-control-lg @error('conversion') is-invalid @enderror"
                                        required min="0.01" step="0.01">

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
                                    class="form-control form-control-lg @error('description') is-invalid @enderror">{{ old('description', $data->description) }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback d-block">
                                        <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Metadata Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-gray-700">Dibuat Pada</label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $data->created_at->translatedFormat('d F Y H:i') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-gray-700">Diperbarui Pada</label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $data->updated_at->translatedFormat('d F Y H:i') }}" readonly>
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
                                    <i class="mdi mdi-content-save-check me-2"></i>Perbarui Produk
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div class="col-lg-4">
                <!-- Product Info Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-information-outline me-2"></i>Informasi Produk
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="bg-light-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="mdi mdi-package-variant fs-2 text-success"></i>
                            </div>
                            <h5 class="font-weight-bold text-gray-900">{{ $data->name }}</h5>
                            <p class="text-muted small">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $data->code }}
                                </span>
                            </p>
                            <p class="text-muted small">ID: #{{ $data->id }}</p>
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ $data->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                @if ($data->status == 'active')
                                    <span class="mdi mdi-check-circle text-green-500"></span>
                                    Aktif
                                @else
                                    <span class="mdi mdi-close-circle text-red-500"></span>
                                    Nonaktif
                                @endif
                            </span>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-folder-outline text-green-500 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Kategori</p>
                                    <p class="text-sm text-gray-600">{{ $data->category->name ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-scale text-orange-500 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Satuan</p>
                                    <p class="text-sm text-gray-600">
                                        1 {{ $data->unitLarge->abbreviation ?? '-' }} =
                                        {{ number_format($data->conversion, 2) }}
                                        {{ $data->unitSmall->abbreviation ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-alert-circle text-amber-500 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Stok Minimum</p>
                                    <p class="text-sm text-gray-600">{{ number_format($data->min_stock) }}
                                        {{ $data->unitSmall->abbreviation ?? '' }}</p>
                                </div>
                            </div>

                            @if ($data->description)
                                <div class="flex items-start gap-3">
                                    <i class="mdi mdi-text-box-outline text-blue-500 mt-0.5"></i>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-700">Deskripsi</p>
                                        <p class="text-sm text-gray-600">{{ Str::limit($data->description, 100) }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">

                        <div class="small">
                            <div class="d-flex align-items-center mb-3">
                                <i class="mdi mdi-calendar-plus me-3 text-success"></i>
                                <div>
                                    <strong>Dibuat:</strong><br>
                                    {{ $data->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-calendar-edit me-3 text-info"></i>
                                <div>
                                    <strong>Diperbarui:</strong><br>
                                    {{ $data->updated_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-lightbulb-on-outline me-2"></i>Tips Produk
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                                Pastikan konversi satuan sudah tepat
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                                Setel stok minimum untuk peringatan
                            </li>
                            <li class="mb-0">
                                <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                                Update status produk secara berkala
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form action="{{ route('products.delete', $data->id) }}" method="POST" id="deleteForm" class="d-none">
        @csrf
        @method('DELETE')
    </form>
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
                submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Memperbarui...';
            });
        });

        function handleCancel() {
            const codeInput = document.getElementById('code');
            const nameInput = document.getElementById('name');
            const categoryInput = $('#category_id');
            const unitLargeInput = $('#unit_large_id');
            const unitSmallInput = $('#unit_small_id');
            const conversionInput = document.getElementById('conversion');
            const minStockInput = document.getElementById('min_stock');
            const statusInput = document.getElementById('status');

            const originalCode = "{{ $data->code }}";
            const originalName = "{{ $data->name }}";
            const originalCategory = "{{ $data->category_id }}";
            const originalUnitLarge = "{{ $data->unit_large_id }}";
            const originalUnitSmall = "{{ $data->unit_small_id }}";
            const originalConversion = "{{ $data->conversion }}";
            const originalMinStock = "{{ $data->min_stock }}";
            const originalStatus = "{{ $data->status }}";

            const currentCode = codeInput.value.trim();
            const currentName = nameInput.value.trim();
            const currentCategory = categoryInput.val();
            const currentUnitLarge = unitLargeInput.val();
            const currentUnitSmall = unitSmallInput.val();
            const currentConversion = conversionInput.value;
            const currentMinStock = minStockInput.value;
            const currentStatus = statusInput.value;

            if (currentCode !== originalCode || currentName !== originalName || currentCategory !== originalCategory ||
                currentUnitLarge !== originalUnitLarge || currentUnitSmall !== originalUnitSmall ||
                currentConversion !== originalConversion || currentMinStock !== originalMinStock ||
                currentStatus !== originalStatus) {
                Alert.confirm(
                    'Perubahan belum disimpan',
                    'Anda memiliki perubahan yang belum disimpan. Yakin ingin kembali?',
                    function() {
                        window.history.back();
                    }
                );
            } else {
                window.history.back();
            }
        }

        function confirmDelete() {
            Alert.confirm(
                `Hapus Produk "{{ $data->name }}"?`,
                'Produk akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!',
                function() {
                    document.getElementById('deleteForm').submit();
                }
            );
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
