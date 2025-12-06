@extends('layouts.master')

@section('title', 'Edit Supplier - ' . $data->name)

@section('content')
    <div class="container-fluid px-6 py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="h3 mb-2 text-gray-900 font-weight-bold">Edit Supplier</h1>
                <p class="text-muted">Perbarui informasi supplier yang sudah ada</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
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
                            <i class="mdi mdi-pencil-box-outline me-2"></i>Form Edit Supplier
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('suppliers.update', $data->id) }}" method="POST" id="supplierForm">
                            @csrf
                            @method('PUT')

                            <!-- Name Field -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold text-gray-700">
                                    Nama Supplier <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $data->name) }}"
                                    placeholder="Contoh: PT. Contoh Supplier, CV. Mandiri Jaya"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror" required
                                    autofocus maxlength="255">

                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Address Field -->
                            <div class="mb-4">
                                <label for="address" class="form-label fw-semibold text-gray-700">
                                    Alamat <span class="text-danger">*</span>
                                </label>
                                <textarea id="address" name="address" rows="3" placeholder="Contoh: Jl. Contoh Alamat No. 123, Kota Contoh"
                                    class="form-control form-control-lg @error('address') is-invalid @enderror" required>{{ old('address', $data->address) }}</textarea>

                                @error('address')
                                    <div class="invalid-feedback d-block">
                                        <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Phone Field -->
                            <div class="mb-4">
                                <label for="phone" class="form-label fw-semibold text-gray-700">
                                    Nomor Telepon
                                </label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $data->phone) }}"
                                    placeholder="Contoh: 081234567890, (021) 1234567"
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
                                            value="{{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y H:i') }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-gray-700">Diperbarui Pada</label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ \Carbon\Carbon::parse($data->updated_at)->translatedFormat('d F Y H:i') }}"
                                            readonly>
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
                                    <i class="mdi mdi-content-save-check me-2"></i>Perbarui Supplier
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div class="col-lg-4">
                <!-- Supplier Info Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-information-outline me-2"></i>Informasi Supplier
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="bg-light-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="mdi mdi-account-tie fs-2 text-success"></i>
                            </div>
                            <h5 class="font-weight-bold text-gray-900">{{ $data->name }}</h5>
                            <p class="text-muted small">ID: #{{ $data->id }}</p>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-map-marker text-orange-500 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Alamat</p>
                                    <p class="text-sm text-gray-600">{{ $data->address ?: '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-phone text-green-500 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Telepon</p>
                                    <p class="text-sm text-gray-600">{{ $data->phone ?: '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="small">
                            <div class="d-flex align-items-center mb-3">
                                <i class="mdi mdi-calendar-plus me-3 text-success"></i>
                                <div>
                                    <strong>Dibuat:</strong><br>
                                    {{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-calendar-edit me-3 text-info"></i>
                                <div>
                                    <strong>Diperbarui:</strong><br>
                                    {{ \Carbon\Carbon::parse($data->updated_at)->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-lightbulb-on-outline me-2"></i>Tips Supplier
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                                Pastikan data supplier lengkap
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                                Update informasi secara berkala
                            </li>
                            <li class="mb-0">
                                <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                                Verifikasi kontak yang dapat dihubungi
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form action="{{ route('suppliers.delete', $data->id) }}" method="POST" id="deleteForm" class="d-none">
        @csrf
        @method('DELETE')
    </form>
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
                submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Memperbarui...';
            });
        });

        function handleCancel() {
            const nameInput = document.getElementById('name');
            const addressInput = document.getElementById('address');
            const phoneInput = document.getElementById('phone');
            const originalName = "{{ $data->name }}";
            const originalAddress = "{{ $data->address }}";
            const originalPhone = "{{ $data->phone }}";
            const currentName = nameInput.value.trim();
            const currentAddress = addressInput.value.trim();
            const currentPhone = phoneInput.value.trim();

            if (currentName !== originalName || currentAddress !== originalAddress || currentPhone !== originalPhone) {
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
                `Hapus Supplier "{{ $data->name }}"?`,
                'Supplier akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!',
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
