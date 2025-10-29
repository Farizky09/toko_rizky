@extends('layouts.master')

@section('title', 'Tambah Lokasi Baru')

@section('content')
    <div class="container-fluid px-6 py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="h3 mb-2 text-gray-900 font-weight-bold">Tambah Lokasi Baru</h1>
                <p class="text-muted">Tambah data lokasi baru ke dalam sistem</p>
            </div>
            <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-2"></i>Kembali ke Daftar
            </a>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent py-3">
                <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                    <i class="mdi mdi-plus-box-outline me-2"></i>Form Tambah Lokasi
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('locations.store') }}" method="POST" id="locationForm">
                    @csrf

                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label fw-semibold text-gray-700">
                                Nama Lokasi <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                placeholder="Contoh: Gudang Utama, Ruangan A, Rak B1"
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
                                    Masukkan nama lokasi yang jelas dan deskriptif
                                </small>
                            </div>
                        </div>

                        <!-- Type Field -->
                        <div class="col-md-6 mb-4">
                            <label for="type" class="form-label fw-semibold text-gray-700">
                                Tipe Lokasi <span class="text-danger">*</span>
                            </label>
                            <select id="type" name="type"
                                class="form-control form-control-lg @error('type') is-invalid @enderror" required>
                                <option value="">Pilih Tipe Lokasi</option>
                                <option value="warehouse" {{ old('type') == 'warehouse' ? 'selected' : '' }}>Gudang</option>
                                <option value="store" {{ old('type') == 'store' ? 'selected' : '' }}>Toko</option>

                            </select>

                            @error('type')
                                <div class="invalid-feedback d-block">
                                    <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Branch Field -->
                    <div class="mb-4">
                        <label for="branch_id" class="form-label fw-semibold text-gray-700">
                            Cabang <span class="text-danger">*</span>
                        </label>
                        <select id="branch_id" name="branch_id"
                            class="form-control form-control-lg @error('branch_id') is-invalid @enderror" required>
                            <option value="">Pilih Cabang</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->code }} - {{ $branch->name }}
                                    @if (!$branch->status == 'active')
                                        (Nonaktif)
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        @error('branch_id')
                            <div class="invalid-feedback d-block">
                                <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                            </div>
                        @enderror

                        <div class="form-text text-muted mt-2">
                            <small>
                                <i class="mdi mdi-information-outline me-1"></i>
                                Pilih cabang tempat lokasi ini berada
                            </small>
                        </div>
                    </div>

                    <!-- Additional Info Section -->
                    <div class="alert alert-info border-0 bg-light-info">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-information-outline me-3 fs-4 text-info"></i>
                            <div>
                                <h6 class="alert-heading mb-2 text-info">Informasi Lokasi</h6>
                                <p class="mb-0 small">
                                    Lokasi digunakan untuk mengorganisir area spesifik dalam cabang seperti gudang, ruangan,
                                    atau rak penyimpanan.
                                    Pastikan setiap lokasi terkait dengan cabang yang tepat dan memiliki tipe yang sesuai.
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
                            <i class="mdi mdi-content-save-check me-2"></i>Simpan Lokasi
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
            const form = document.getElementById('locationForm');
            const nameInput = document.getElementById('name');
            const typeInput = document.getElementById('type');
            const branchInput = document.getElementById('branch_id');
            const submitBtn = document.getElementById('submitBtn');

            // Real-time validation for required fields
            const requiredFields = [nameInput, typeInput, branchInput];

            requiredFields.forEach(field => {
                if (field) {
                    field.addEventListener('change', function() {
                        const value = this.type === 'select-one' ? this.value : this.value.trim();
                        if (value) {
                            this.classList.remove('is-invalid');
                            this.classList.add('is-valid');
                        } else {
                            this.classList.remove('is-valid');
                        }
                    });

                    // For text input, use input event
                    if (field.type === 'text') {
                        field.addEventListener('input', function() {
                            const value = this.value.trim();
                            if (value) {
                                this.classList.remove('is-invalid');
                                this.classList.add('is-valid');
                            } else {
                                this.classList.remove('is-valid');
                            }
                        });
                    }
                }
            });

            // Form submission handling
            form.addEventListener('submit', function(e) {
                let hasError = false;

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
            const nameInput = document.getElementById('name');
            const typeInput = document.getElementById('type');
            const branchInput = document.getElementById('branch_id');
            const currentName = nameInput.value.trim();
            const currentType = typeInput.value;
            const currentBranch = branchInput.value;

            if (currentName || currentType || currentBranch) {
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
