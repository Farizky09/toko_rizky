@extends('layouts.master')

@section('title', 'Edit Cabang - ' . $data->name)

@section('content')
<div class="container-fluid px-6 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-6">
        <div>
            <h1 class="h3 mb-2 text-gray-900 font-weight-bold">Edit Cabang</h1>
            <p class="text-muted">Perbarui informasi cabang yang sudah ada</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary">
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
                        <i class="mdi mdi-pencil-box-outline me-2"></i>Form Edit Cabang
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('branches.update', $data->id) }}" method="POST" id="branchForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Code Field -->
                            <div class="col-md-6 mb-4">
                                <label for="code" class="form-label fw-semibold text-gray-700">
                                    Kode Cabang <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="code" name="code"
                                    value="{{ old('code', $data->code) }}"
                                    placeholder="Contoh: CAB-001, B001, MAIN"
                                    class="form-control form-control-lg @error('code') is-invalid @enderror" required
                                    maxlength="20">

                                @error('code')
                                    <div class="invalid-feedback d-block">
                                        <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Name Field -->
                            <div class="col-md-6 mb-4">
                                <label for="name" class="form-label fw-semibold text-gray-700">
                                    Nama Cabang <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', $data->name) }}"
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
                                    <option value="1" {{ old('status', $data->status) == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('status', $data->status) == '0' ? 'selected' : '' }}>Nonaktif</option>
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
                                class="form-control form-control-lg @error('address') is-invalid @enderror" required>{{ old('address', $data->address) }}</textarea>

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
                                <input type="text" id="city" name="city"
                                    value="{{ old('city', $data->city) }}"
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
                                <input type="text" id="province" name="province"
                                    value="{{ old('province', $data->province) }}"
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
                                <input type="text" id="postal_code" name="postal_code"
                                    value="{{ old('postal_code', $data->postal_code) }}"
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
                            <input type="text" id="phone" name="phone"
                                value="{{ old('phone', $data->phone) }}"
                                placeholder="Contoh: (021) 1234567, 081234567890"
                                class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                maxlength="20">

                            @error('phone')
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
                                <i class="mdi mdi-content-save-check me-2"></i>Perbarui Cabang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Branch Info Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent py-3">
                    <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                        <i class="mdi mdi-information-outline me-2"></i>Informasi Cabang
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-light-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="mdi mdi-store fs-2 text-success"></i>
                        </div>
                        <h5 class="font-weight-bold text-gray-900">{{ $data->name }}</h5>
                        <p class="text-muted small">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $data->code }}
                            </span>
                        </p>
                        <p class="text-muted small">ID: #{{ $data->id }}</p>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ $data->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            @if($data->status)
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
                            <i class="mdi mdi-map-marker text-green-500 mt-0.5"></i>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700">Alamat</p>
                                <p class="text-sm text-gray-600">{{ $data->address }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <i class="mdi mdi-city text-blue-500 mt-0.5"></i>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700">Lokasi</p>
                                <p class="text-sm text-gray-600">{{ $data->city }}, {{ $data->province }} {{ $data->postal_code }}</p>
                            </div>
                        </div>

                        @if($data->phone)
                        <div class="flex items-start gap-3">
                            <i class="mdi mdi-phone text-purple-500 mt-0.5"></i>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700">Telepon</p>
                                <p class="text-sm text-gray-600">{{ $data->phone }}</p>
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
                        <i class="mdi mdi-lightbulb-on-outline me-2"></i>Tips Cabang
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                            Kode cabang harus unik dan mudah diingat
                        </li>
                        <li class="mb-2">
                            <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                            Pastikan data cabang lengkap dan akurat
                        </li>
                        <li class="mb-0">
                            <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                            Update status cabang secara berkala
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form action="{{ route('branches.delete', $data->id) }}" method="POST" id="deleteForm" class="d-none">
    @csrf
    @method('DELETE')
</form>
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
            submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Memperbarui...';
        });
    });

    function handleCancel() {
        const codeInput = document.getElementById('code');
        const nameInput = document.getElementById('name');
        const addressInput = document.getElementById('address');
        const cityInput = document.getElementById('city');
        const provinceInput = document.getElementById('province');
        const postalCodeInput = document.getElementById('postal_code');
        const statusInput = document.getElementById('status');

        const originalCode = "{{ $data->code }}";
        const originalName = "{{ $data->name }}";
        const originalAddress = "{{ $data->address }}";
        const originalCity = "{{ $data->city }}";
        const originalProvince = "{{ $data->province }}";
        const originalPostalCode = "{{ $data->postal_code }}";
        const originalStatus = "{{ $data->status }}";

        const currentCode = codeInput.value.trim();
        const currentName = nameInput.value.trim();
        const currentAddress = addressInput.value.trim();
        const currentCity = cityInput.value.trim();
        const currentProvince = provinceInput.value.trim();
        const currentPostalCode = postalCodeInput.value.trim();
        const currentStatus = statusInput.value;

        if (currentCode !== originalCode || currentName !== originalName || currentAddress !== originalAddress ||
            currentCity !== originalCity || currentProvince !== originalProvince ||
            currentPostalCode !== originalPostalCode || currentStatus !== originalStatus) {
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
            `Hapus Cabang "{{ $data->name }}"?`,
            'Cabang akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!',
            function() {
                document.getElementById('deleteForm').submit();
            }
        );
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
