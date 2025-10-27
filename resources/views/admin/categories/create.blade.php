@extends('layouts.master')

@section('title', 'Tambah Kategori Baru')

@section('content')
    <div class="container-fluid px-6 py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="h3 mb-2 text-gray-900 font-weight-bold">Tambah Kategori Baru</h1>
                <p class="text-muted">Buat kategori baru untuk sistem Anda</p>
            </div>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-2"></i>Kembali ke Daftar
            </a>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent py-3">
                <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                    <i class="mdi mdi-plus-box-outline me-2"></i>Form Tambah Kategori
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST" id="categoryForm">
                    @csrf

                    <!-- Category Name Field -->
                    <div class="mb-4">
                        <label for="categoryName" class="form-label fw-semibold text-gray-700">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="categoryName" name="name" value="{{ old('name') }}"
                            placeholder="Contoh: Elektronik, Pakaian, Makanan"
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
                                Masukkan nama kategori yang deskriptif dan mudah dipahami
                            </small>
                        </div>
                    </div>

                    <!-- Additional Info Section -->
                    <div class="alert alert-info border-0 bg-light-info">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-information-outline me-3 fs-4 text-info"></i>
                            <div>
                                <h6 class="alert-heading mb-2 text-info">Informasi Kategori</h6>
                                <p class="mb-0 small">
                                    Kategori digunakan untuk mengelompokkan dan mengorganisir konten atau produk
                                    dalam sistem Anda. Pastikan nama kategori jelas dan konsisten.
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
                            <i class="mdi mdi-content-save-check me-2"></i>Simpan Kategori
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
            const form = document.getElementById('categoryForm');
            const categoryInput = document.getElementById('categoryName');
            const submitBtn = document.getElementById('submitBtn');

            // Real-time validation
            categoryInput.addEventListener('input', function() {
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
                const value = categoryInput.value.trim();

                if (!value) {
                    e.preventDefault();
                    categoryInput.classList.add('is-invalid');
                    categoryInput.focus();
                    return;
                }

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Menyimpan...';
            });
        });

        function handleCancel() {
            const categoryInput = document.getElementById('categoryName');
            const currentValue = categoryInput.value.trim();

            if (currentValue) {
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

        // SweetAlert untuk status dari session
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
