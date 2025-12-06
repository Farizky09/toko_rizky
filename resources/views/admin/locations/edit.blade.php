@extends('layouts.master')

@section('title', 'Edit Lokasi - ' . $data->name)

@section('content')
    <div class="container-fluid px-6 py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="h3 mb-2 text-gray-900 font-weight-bold">Edit Lokasi</h1>
                <p class="text-muted">Perbarui informasi lokasi yang sudah ada</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary">
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
                            <i class="mdi mdi-pencil-box-outline me-2"></i>Form Edit Lokasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('locations.update', $data->id) }}" method="POST" id="locationForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Name Field -->
                                <div class="col-md-6 mb-4">
                                    <label for="name" class="form-label fw-semibold text-gray-700">
                                        Nama Lokasi <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $data->name) }}"
                                        placeholder="Contoh: Gudang Utama, Ruangan A, Rak B1"
                                        class="form-control form-control-lg @error('name') is-invalid @enderror" required
                                        autofocus maxlength="255">

                                    @error('name')
                                        <div class="invalid-feedback d-block">
                                            <i class="mdi mdi-alert-circle-outline me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Type Field -->
                                <div class="col-md-6 mb-4">
                                    <label for="type" class="form-label fw-semibold text-gray-700">
                                        Tipe Lokasi <span class="text-danger">*</span>
                                    </label>
                                    <select id="type" name="type"
                                        class="form-control form-control-lg @error('type') is-invalid @enderror" required>
                                        <option value="">Pilih Tipe Lokasi</option>
                                        <option value="gudang" {{ old('type', $data->type) == 'gudang' ? 'selected' : '' }}>
                                            Gudang</option>
                                        <option value="ruangan"
                                            {{ old('type', $data->type) == 'ruangan' ? 'selected' : '' }}>Ruangan</option>
                                        <option value="rak" {{ old('type', $data->type) == 'rak' ? 'selected' : '' }}>Rak
                                        </option>
                                        <option value="area" {{ old('type', $data->type) == 'area' ? 'selected' : '' }}>
                                            Area</option>
                                        <option value="lainnya"
                                            {{ old('type', $data->type) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id', $data->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->code }} - {{ $branch->name }}
                                            @if (!$branch->status)
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
                                    <i class="mdi mdi-content-save-check me-2"></i>Perbarui Lokasi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div class="col-lg-4">
                <!-- Location Info Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-information-outline me-2"></i>Informasi Lokasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="bg-light-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="mdi mdi-map-marker fs-2 text-success"></i>
                            </div>
                            <h5 class="font-weight-bold text-gray-900">{{ $data->name }}</h5>
                            <p class="text-muted small">ID: #{{ $data->id }}</p>

                            @php
                                $typeColors = [
                                    'gudang' => 'bg-orange-100 text-orange-800',
                                    'ruangan' => 'bg-blue-100 text-blue-800',
                                    'rak' => 'bg-green-100 text-green-800',
                                    'area' => 'bg-purple-100 text-purple-800',
                                    'lainnya' => 'bg-gray-100 text-gray-800',
                                ];
                                $typeDisplay = [
                                    'gudang' => 'Gudang',
                                    'ruangan' => 'Ruangan',
                                    'rak' => 'Rak',
                                    'area' => 'Area',
                                    'lainnya' => 'Lainnya',
                                ];
                                $colorClass = $typeColors[$data->type] ?? 'bg-gray-100 text-gray-800';
                                $displayName = $typeDisplay[$data->type] ?? $data->type;
                            @endphp

                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ $colorClass }}">
                                {{ $displayName }}
                            </span>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-store text-green-500 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Cabang</p>
                                    <p class="text-sm text-gray-600">{{ $data->branch->code }} -
                                        {{ $data->branch->name }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-map-marker-radius text-indigo-500 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Alamat Cabang</p>
                                    <p class="text-sm text-gray-600">{{ $data->branch->city }},
                                        {{ $data->branch->province }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-account-check text-blue-500 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Status Cabang</p>
                                    <p class="text-sm text-gray-600">
                                        @if ($data->branch->status)
                                            <span class="text-green-600">Aktif</span>
                                        @else
                                            <span class="text-red-600">Nonaktif</span>
                                        @endif
                                    </p>
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
                            <i class="mdi mdi-lightbulb-on-outline me-2"></i>Tips Lokasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                                Pilih tipe lokasi yang sesuai dengan fungsinya
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                                Pastikan cabang yang dipilih masih aktif
                            </li>
                            <li class="mb-0">
                                <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                                Gunakan nama lokasi yang jelas dan deskriptif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form action="{{ route('locations.delete', $data->id) }}" method="POST" id="deleteForm" class="d-none">
        @csrf
        @method('DELETE')
    </form>
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
                submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Memperbarui...';
            });
        });

        function handleCancel() {
            const nameInput = document.getElementById('name');
            const typeInput = document.getElementById('type');
            const branchInput = document.getElementById('branch_id');
            const originalName = "{{ $data->name }}";
            const originalType = "{{ $data->type }}";
            const originalBranch = "{{ $data->branch_id }}";
            const currentName = nameInput.value.trim();
            const currentType = typeInput.value;
            const currentBranch = branchInput.value;

            if (currentName !== originalName || currentType !== originalType || currentBranch !== originalBranch) {
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
                `Hapus Lokasi "{{ $data->name }}"?`,
                'Lokasi akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!',
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
