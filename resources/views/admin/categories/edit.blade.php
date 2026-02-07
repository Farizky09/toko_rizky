@extends('layouts.master')

@section('title', 'Edit Kategori - ' . $data->name)

@section('content')
    <div class="container-fluid px-6 py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="h3 mb-2 text-gray-900 font-weight-bold">Edit Kategori</h1>
                <p class="text-muted">Perbarui informasi kategori yang sudah ada</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
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
                            <i class="mdi mdi-pencil-box-outline me-2"></i>Form Edit Kategori
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('categories.update', $data->id) }}" method="POST" id="categoryForm">
                            @csrf
                            @method('PUT')

                            <!-- Category Name Field -->
                            <div class="mb-4">
                                <label for="categoryName" class="form-label fw-semibold text-gray-700">
                                    Nama Kategori <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="categoryName" name="name"
                                    value="{{ old('name', $data->name) }}"
                                    placeholder="Contoh: Elektronik, Pakaian, Makanan"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror" required
                                    autofocus maxlength="255">

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
                                    <i class="mdi mdi-content-save-check me-2"></i>Perbarui Kategori
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div class="col-lg-4">
                <!-- Category Info Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-information-outline me-2"></i>Informasi Kategori
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="bg-light-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="mdi mdi-folder-outline fs-2 text-success"></i>
                            </div>
                            <h5 class="font-weight-bold text-gray-900">{{ $data->name }}</h5>
                            <p class="text-muted small">ID: #{{ $data->id }}</p>
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

                <!-- Best Practices Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-lightbulb-on-outline me-2"></i>Tips Kategori
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                                Gunakan nama yang jelas dan deskriptif
                            </li>
                            <li class="mb-2">
                                <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                                Hindari nama yang terlalu panjang
                            </li>
                            <li class="mb-0">
                                <i class="mdi mdi-check-circle-outline text-success me-2"></i>
                                Konsisten dengan penamaan kategori lain
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form action="{{ route('categories.delete', $data->id) }}" method="POST" id="deleteForm" class="d-none">
        @csrf
        @method('DELETE')
    </form>
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
                submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Memperbarui...';
            });
        });

        function handleCancel() {
            const categoryInput = document.getElementById('categoryName');
            const originalValue = "{{ $data->name }}";
            const currentValue = categoryInput.value.trim();

            if (currentValue !== originalValue) {
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
                `Hapus Kategori "${categoryName}"?`,
                'Kategori akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!',
                function() {
                    document.getElementById('deleteForm').submit();
                }
            );
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
