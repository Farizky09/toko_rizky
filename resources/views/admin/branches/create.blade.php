@extends('layouts.master')

@section('title', 'Tambah Cabang Baru')

@section('content')
<div class="container-fluid px-6 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-6">
        <div>
            <h1 class="h3 mb-2 text-gray-900 font-weight-bold">Tambah Cabang Baru</h1>
            <p class="text-muted">Tambah data cabang baru ke dalam sistem</p>
        </div>
        <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary">
            <i class="mdi mdi-arrow-left me-2"></i>Kembali ke Daftar
        </a>
    </div>

    <!-- Form Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent py-3">
            <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                <i class="mdi mdi-plus-box-outline me-2"></i>Form Tambah Cabang
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('branches.store') }}" method="POST" id="branchForm">
                @csrf

                <div class="row">
                    <!-- Code Field -->
                    <div class="col-md-6 mb-4">
                        <label for="code" class="form-label fw-semibold text-gray-700">
                            Kode Cabang <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}"
                            placeholder="Contoh: CAB-001, B001, MAIN"
                            class="form-control form-control-lg @error('code') is-invalid @enderror" required
                            maxlength="20">

                        @error('code')
                            <div class="invalid-feedback d-block">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror

                        <div class="form-text text-muted mt-2">
                            <small>
                                <i class="mdi mdi-information-outline me-1"></i>
                                Kode unik untuk identifikasi cabang (maksimal 20 karakter)
                            </small>
                        </div>
                    </div>

                    <!-- Name Field -->
                    <div class="col-md-6 mb-4">
                        <label for="name" class="form-label fw-semibold text-gray-700">
                            Nama Cabang <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            placeholder="Contoh: Cabang Pusat, Cabang Surabaya, Cabang Bandung"
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
                    <!-- Status Field -->
                    <div class="col-md-6 mb-4">
                        <label for="status" class="form-label fw-semibold text-gray-700">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select id="status" name="status"
                            class="form-control form-control-lg @error('status') is-invalid @enderror" required>
                            <option value="">Pilih Status</option>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback d-block">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Address Field -->
                <div class="mb-4">
                    <label for="address" class="form-label fw-semibold text-gray-700">
                        Alamat Lengkap <span class="text-danger">*</span>
                    </label>
                    <textarea id="address" name="address" rows="3"
                        placeholder="Contoh: Jl. Contoh Alamat No. 123, Kelurahan Contoh, Kecamatan Contoh"
                        class="form-control form-control-lg @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>

                    @error('address')
                        <div class="invalid-feedback d-block">
                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="row">
                    <!-- City Field -->
                    <div class="col-md-4 mb-4">
                        <label for="city" class="form-label fw-semibold text-gray-700">
                            Kota <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}"
                            placeholder="Contoh: Jakarta Selatan, Surabaya, Bandung"
                            class="form-control form-control-lg @error('city') is-invalid @enderror" required
                            maxlength="100">

                        @error('city')
                            <div class="invalid-feedback d-block">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Province Field -->
                    <div class="col-md-4 mb-4">
                        <label for="province" class="form-label fw-semibold text-gray-700">
                            Provinsi <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="province" name="province" value="{{ old('province') }}"
                            placeholder="Contoh: DKI Jakarta, Jawa Timur, Jawa Barat"
                            class="form-control form-control-lg @error('province') is-invalid @enderror" required
                            maxlength="100">

                        @error('province')
                            <div class="invalid-feedback d-block">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Postal Code Field -->
                    <div class="col-md-4 mb-4">
                        <label for="postal_code" class="form-label fw-semibold text-gray-700">
                            Kode Pos <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                            placeholder="Contoh: 12345, 60272, 40115"
                            class="form-control form-control-lg @error('postal_code') is-invalid @enderror" required
                            maxlength="10">

                        @error('postal_code')
                            <div class="invalid-feedback d-block">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Phone Field -->
                <div class="mb-4">
                    <label for="phone" class="form-label fw-semibold text-gray-700">
                        Nomor Telepon
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                        placeholder="Contoh: (021) 1234567, 081234567890"
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
                            <h6 class="alert-heading mb-2 text-info">Informasi Cabang</h6>
                            <p class="mb-0 small">
                                Cabang mewakili lokasi fisik atau unit bisnis perusahaan.
                                Pastikan data cabang lengkap dan akurat untuk pengelolaan yang efektif.
                                Kode cabang harus unik dan tidak boleh sama dengan cabang lain.
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
                        <i class="mdi mdi-content-save-check me-2"></i>Simpan Cabang
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
        const form = document.getElementById('branchForm');
        const codeInput = document.getElementById('code');
        const nameInput = document.getElementById('name');
        const addressInput = document.getElementById('address');
        const cityInput = document.getElementById('city');
        const provinceInput = document.getElementById('province');
        const postalCodeInput = document.getElementById('postal_code');
        const statusInput = document.getElementById('status');
        const submitBtn = document.getElementById('submitBtn');

        // Auto-uppercase for code field
        codeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Real-time validation for required fields
        const requiredFields = [codeInput, nameInput, addressInput, cityInput, provinceInput, postalCodeInput, statusInput];

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

        // Form submission handling
        form.addEventListener('submit', function(e) {
            let hasError = false;

            requiredFields.forEach(field => {
                if (field) {
                    const value = field.type === 'select-one' ? field.value : field.value.trim();
                    if (!value) {
                        field.classList.add('is-invalid');
                        if (!hasError) {
                            field.focus();
                            hasError = true;
                        }
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
