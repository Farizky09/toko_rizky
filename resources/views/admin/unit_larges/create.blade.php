@extends('layouts.master')

@section('title', 'Tambah Satuan Besar Baru')

@section('content')
    <div class="container-fluid px-6 py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="h3 mb-2 text-gray-900 font-weight-bold">Tambah Satuan Besar Baru</h1>
                <p class="text-muted">Buat satuan besar baru untuk sistem pengukuran</p>
            </div>
            <a href="{{ route('unit-larges.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-2"></i>Kembali ke Daftar
            </a>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent py-3">
                <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                    <i class="mdi mdi-plus-box-outline me-2"></i>Form Tambah Satuan Besar
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('unit-larges.store') }}" method="POST" id="unitLargeForm">
                    @csrf

                    <!-- Name Field -->
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold text-gray-700">
                            Nama Satuan Besar <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            placeholder="Contoh: Kilogram, Liter, Meter"
                            class="form-control form-control-lg @error('name') is-invalid @enderror" required autofocus
                            maxlength="255">

                        @error('name')
                            <div class="invalid-feedback d-block">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror

                        <div class="form-text text-muted mt-2">
                            <small>
                                <i class="mdi mdi-information-outline me-1"></i>
                                Masukkan nama lengkap satuan besar
                            </small>
                        </div>
                    </div>

                    <!-- Abbreviation Field -->
                    <div class="mb-4">
                        <label for="abbreviation" class="form-label fw-semibold text-gray-700">
                            Singkatan <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="abbreviation" name="abbreviation" value="{{ old('abbreviation') }}"
                            placeholder="Contoh: kg, l, m"
                            class="form-control form-control-lg @error('abbreviation') is-invalid @enderror" required
                            maxlength="10">

                        @error('abbreviation')
                            <div class="invalid-feedback d-block">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror

                        <div class="form-text text-muted mt-2">
                            <small>
                                <i class="mdi mdi-information-outline me-1"></i>
                                Masukkan singkatan satuan (maksimal 10 karakter)
                            </small>
                        </div>
                    </div>

                    <!-- Additional Info Section -->
                    <div class="alert alert-info border-0 bg-light-info">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-information-outline me-3 fs-4 text-info"></i>
                            <div>
                                <h6 class="alert-heading mb-2 text-info">Informasi Satuan Besar</h6>
                                <p class="mb-0 small">
                                    Satuan besar digunakan untuk pengukuran standar dalam sistem.
                                    Pastikan nama dan singkatan konsisten dengan standar yang berlaku.
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
                            <i class="mdi mdi-content-save-check me-2"></i>Simpan Satuan Besar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('unitLargeForm');
            const nameInput = document.getElementById('name');
            const abbreviationInput = document.getElementById('abbreviation');
            const submitBtn = document.getElementById('submitBtn');

            // Real-time validation
            nameInput.addEventListener('input', function() {
                const value = this.value.trim();
                if (value) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                }
            });

            abbreviationInput.addEventListener('input', function() {
                const value = this.value.trim();
                if (value) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                }
            });

            // Form submission handling
            form.addEventListener('submit', function(e) {
                const nameValue = nameInput.value.trim();
                const abbreviationValue = abbreviationInput.value.trim();

                if (!nameValue || !abbreviationValue) {
                    e.preventDefault();
                    if (!nameValue) {
                        nameInput.classList.add('is-invalid');
                        nameInput.focus();
                    } else {
                        abbreviationInput.classList.add('is-invalid');
                        abbreviationInput.focus();
                    }
                    return;
                }

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Menyimpan...';
            });
        });

        function handleCancel() {
            const nameInput = document.getElementById('name');
            const abbreviationInput = document.getElementById('abbreviation');
            const currentName = nameInput.value.trim();
            const currentAbbreviation = abbreviationInput.value.trim();

            if (currentName || currentAbbreviation) {
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
