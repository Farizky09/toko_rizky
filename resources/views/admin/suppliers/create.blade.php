@extends('layouts.master')

@section('title', 'Tambah Supplier Baru')

@section('content')
<div class="container-fluid px-6 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-6">
        <div>
            <h1 class="h3 mb-2 text-gray-900 font-weight-bold">Tambah Supplier Baru</h1>
            <p class="text-muted">Tambah data supplier baru ke dalam sistem</p>
        </div>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
            <i class="mdi mdi-arrow-left me-2"></i>Kembali ke Daftar
        </a>
    </div>

    <!-- Form Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent py-3">
            <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                <i class="mdi mdi-plus-box-outline me-2"></i>Form Tambah Supplier
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('suppliers.store') }}" method="POST" id="supplierForm">
                @csrf

                <!-- Name Field -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold text-gray-700">
                        Nama Supplier <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Contoh: PT. Contoh Supplier, CV. Mandiri Jaya"
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
                            Masukkan nama lengkap supplier
                        </small>
                    </div>
                </div>

                <!-- Address Field -->
                <div class="mb-4">
                    <label for="address" class="form-label fw-semibold text-gray-700">
                        Alamat <span class="text-danger">*</span>
                    </label>
                    <textarea id="address" name="address" rows="3"
                        placeholder="Contoh: Jl. Contoh Alamat No. 123, Kota Contoh"
                        class="form-control form-control-lg @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>

                    @error('address')
                        <div class="invalid-feedback d-block">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </div>
                    @enderror

                    <div class="form-text text-muted mt-2">
                        <small>
                            <i class="mdi mdi-information-outline me-1"></i>
                            Masukkan alamat lengkap supplier
                        </small>
                    </div>
                </div>

                <!-- Phone Field -->
                <div class="mb-4">
                    <label for="phone" class="form-label fw-semibold text-gray-700">
                        Nomor Telepon
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                        placeholder="Contoh: 081234567890, (021) 1234567"
                        class="form-control form-control-lg @error('phone') is-invalid @enderror"
                        maxlength="20">

                    @error('phone')
                        <div class="invalid-feedback d-block">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </div>
                    @enderror

                    <div class="form-text text-muted mt-2">
                        <small>
                            <i class="mdi mdi-information-outline me-1"></i>
                            Masukkan nomor telepon yang dapat dihubungi
                        </small>
                    </div>
                </div>

                <!-- Additional Info Section -->
                <div class="alert alert-info border-0 bg-light-info">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-information-outline me-3 fs-4 text-info"></i>
                        <div>
                            <h6 class="alert-heading mb-2 text-info">Informasi Supplier</h6>
                            <p class="mb-0 small">
                                Supplier adalah mitra bisnis yang menyediakan barang atau jasa.
                                Pastikan data supplier lengkap dan akurat untuk kelancaran proses bisnis.
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
                        <i class="mdi mdi-content-save-check me-2"></i>Simpan Supplier
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
        const form = document.getElementById('supplierForm');
        const nameInput = document.getElementById('name');
        const addressInput = document.getElementById('address');
        const phoneInput = document.getElementById('phone');
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

        addressInput.addEventListener('input', function() {
            const value = this.value.trim();
            if (value) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
            }
        });

        // Phone number formatting
        phoneInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value) {
                this.classList.remove('is-invalid');
            }
        });

        // Form submission handling
        form.addEventListener('submit', function(e) {
            const nameValue = nameInput.value.trim();
            const addressValue = addressInput.value.trim();

            if (!nameValue || !addressValue) {
                e.preventDefault();
                if (!nameValue) {
                    nameInput.classList.add('is-invalid');
                    nameInput.focus();
                } else {
                    addressInput.classList.add('is-invalid');
                    addressInput.focus();
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
        const addressInput = document.getElementById('address');
        const phoneInput = document.getElementById('phone');
        const currentName = nameInput.value.trim();
        const currentAddress = addressInput.value.trim();
        const currentPhone = phoneInput.value.trim();

        if (currentName || currentAddress || currentPhone) {
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

    @if(session('success'))
        Alert.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        Alert.error('{{ session('error') }}');
    @endif

    @if($errors->any())
        Alert.error('{{ $errors->first() }}');
    @endif
</script>
@endpush
