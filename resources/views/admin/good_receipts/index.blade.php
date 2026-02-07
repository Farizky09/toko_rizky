@extends('layouts.master')

@section('title', 'Manajemen Good Receipt')

@section('content')
    <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <!-- Page Title & Description -->
                <div class="space-y-3">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">
                            <span class="mdi mdi-package-variant-closed text-xl text-green-600"></span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Manajemen Good Receipt</h1>
                            <p class="text-gray-600 mt-1">Kelola semua transaksi penerimaan barang dari pembelian</p>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <span class="mdi mdi-lightbulb-on-outline text-amber-500"></span>
                        <span>Good Receipt adalah dokumen penerimaan barang dari Purchase Order ke gudang</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    @canany(['create_good_receipts'])
                        <a href="{{ route('good-receipts.create') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-green-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <span class="mdi mdi-plus-circle-outline text-lg"></span>
                            Tambah Good Receipt
                        </a>
                    @endcanany
                </div>
            </div>
        </div>

        <!-- Stats Cards Section -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <!-- Total GR Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50">
                            <span class="mdi mdi-package-variant text-xl text-green-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Total Good Receipt</p>
                        <p class="text-2xl font-semibold text-gray-900" id="totalReceipts">0</p>
                    </div>
                </div>
            </div>

            <!-- Draft Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-50">
                            <span class="mdi mdi-file-document-outline text-xl text-gray-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Draft</p>
                        <p class="text-2xl font-semibold text-gray-900" id="draftReceipts">0</p>
                    </div>
                </div>
            </div>

            <!-- Process Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-50">
                            <span class="mdi mdi-clock-outline text-xl text-yellow-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Process</p>
                        <p class="text-2xl font-semibold text-gray-900" id="processReceipts">0</p>
                    </div>
                </div>
            </div>

            <!-- Completed Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50">
                            <span class="mdi mdi-check-circle-outline text-xl text-green-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-2xl font-semibold text-gray-900" id="completedReceipts">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-6 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200/50">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Filter Pencarian</h3>
                    <p class="text-sm text-gray-600">Saring data good receipt sesuai kebutuhan</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <!-- Date Range Filter -->
                    <div class="w-full sm:w-auto">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rentang Tanggal</label>
                        <div class="flex items-center gap-2">
                            <input type="date" id="startDate"
                                class="rounded-lg border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                            <span class="text-gray-400">s/d</span>
                            <input type="date" id="endDate"
                                class="rounded-lg border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="w-full sm:w-auto">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="statusFilter"
                            class="rounded-lg border-gray-300 text-sm focus:border-green-500 focus:ring-green-500 w-40">
                            <option value="">Semua Status</option>
                            <option value="draft">Draft</option>
                            <option value="process">Process</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="button" onclick="applyFilters()"
                            class="rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Terapkan
                        </button>
                        <button type="button" onclick="resetFilters()"
                            class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="space-y-4">
            <!-- Table Header Card -->
            <div class="rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="mdi mdi-table text-green-600 text-xl"></span>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Good Receipt</h3>
                            <p class="text-sm text-gray-600">Semua transaksi good receipt yang terdaftar</p>
                        </div>
                    </div>

                    <!-- Refresh Button -->
                    <button type="button" onclick="refreshData()"
                        class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition-all duration-200 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <span class="mdi mdi-refresh text-lg"></span>
                        Refresh Data
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200/60">
                <div class="p-4">
                    <table id="goodReceiptsTable" class="table dt-responsive nowrap m-1" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>GR Number</th>
                                <th>Tanggal</th>
                                <th>Purchase Number</th>
                                <th>Supplier</th>
                                <th>Cabang</th>
                                <th>Lokasi</th>
                                <th>Diterima Oleh</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-8 rounded-lg bg-blue-50 p-4">
            <div class="flex items-start gap-3">
                <span class="mdi mdi-help-circle-outline text-blue-500 text-xl mt-0.5"></span>
                <div class="flex-1">
                    <h4 class="font-semibold text-blue-900">Flow Good Receipt</h4>
                    <p class="text-sm text-blue-700 mt-1">
                        <strong>Draft</strong> → <strong>Process</strong> → <strong>Completed</strong><br>
                        • <strong>Draft</strong>: GR baru dibuat, bisa diedit/dihapus<br>
                        • <strong>Process</strong>: GR sedang diproses, bisa diselesaikan/dibatalkan<br>
                        • <strong>Completed</strong>: GR selesai, stok sudah ditambahkan ke inventory<br>
                        • <strong>Cancelled</strong>: GR dibatalkan
                    </p>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                showConfirmButton: true,
            });
        @endif

        let dataTable;

        $(document).ready(function() {
            dataTable = $('#goodReceiptsTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('good-receipts.index') }}',
                    type: 'GET',
                    data: function(d) {
                        d.start_date = $('#startDate').val();
                        d.end_date = $('#endDate').val();
                        d.status = $('#statusFilter').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'gr_number',
                        name: 'gr_number',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-green-100 text-green-800">
                                    ${data}
                                </span>
                            </div>`;
                        }
                    },
                    {
                        data: 'receipt_date',
                        name: 'receipt_date',
                        render: function(data) {
                            if (!data) return '-';
                            const date = new Date(data);
                            return `<div class="text-center">
                                <div class="font-medium text-gray-900">${date.toLocaleDateString('id-ID')}</div>
                            </div>`;
                        }
                    },
                    {
                        data: 'purchase_number',
                        name: 'purchase_number',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <div>
                                    <div class="font-medium text-gray-900">${data || '-'}</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier_name',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <div>
                                    <div class="font-medium text-gray-900">${data || '-'}</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <div>
                                    <div class="font-medium text-gray-900">${data || '-'}</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'location_name',
                        name: 'location_name',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <div>
                                    <div class="font-medium text-gray-900">${data || '-'}</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'received_by_name',
                        name: 'received_by_name',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <div>
                                    <div class="font-medium text-gray-900">${data || '-'}</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            let badgeClass = 'bg-gray-100 text-gray-800';
                            let statusText = data || 'unknown';
                            let icon = '';

                            switch (data) {
                                case 'draft':
                                    badgeClass = 'bg-gray-100 text-gray-800';
                                    statusText = 'Draft';
                                    icon = 'mdi-file-document-outline';
                                    break;
                                case 'process':
                                    badgeClass = 'bg-yellow-100 text-yellow-800';
                                    statusText = 'Process';
                                    icon = 'mdi-clock-outline';
                                    break;
                                case 'completed':
                                    badgeClass = 'bg-green-100 text-green-800';
                                    statusText = 'Completed';
                                    icon = 'mdi-check-circle-outline';
                                    break;
                                case 'cancelled':
                                    badgeClass = 'bg-red-100 text-red-800';
                                    statusText = 'Cancelled';
                                    icon = 'mdi-close-circle-outline';
                                    break;
                            }

                            return `<div class="text-center">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-xs font-medium ${badgeClass}">
                                    <span class="mdi ${icon}"></span>
                                    ${statusText}
                                </span>
                            </div>`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                drawCallback: function(settings) {
                    updateStats();
                },
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
                }
            });
        });

        function applyFilters() {
            dataTable.ajax.reload();
            updateStats();
        }

        function resetFilters() {
            $('#startDate').val('');
            $('#endDate').val('');
            $('#statusFilter').val('');
            dataTable.ajax.reload();
            updateStats();
        }

        function confirmDelete(id, grNumber) {
            Swal.fire({
                title: `Hapus Good Receipt?`,
                text: `Apakah Anda yakin ingin menghapus Good Receipt ${grNumber}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }

        function confirmProcess(id, grNumber) {
            Swal.fire({
                title: 'Proses Good Receipt?',
                text: `Apakah Anda yakin ingin memproses Good Receipt ${grNumber}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Proses!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`process-form-${id}`).submit();
                }
            });
        }

        function confirmComplete(id, grNumber) {
            Swal.fire({
                title: 'Selesaikan Good Receipt?',
                text: `Apakah Anda yakin ingin menyelesaikan Good Receipt ${grNumber}? Stok akan ditambahkan ke inventory.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`complete-form-${id}`).submit();
                }
            });
        }

        function confirmCancel(id, grNumber) {
            Swal.fire({
                title: 'Batalkan Good Receipt?',
                text: `Apakah Anda yakin ingin membatalkan Good Receipt ${grNumber}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6c757d',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`cancel-form-${id}`).submit();
                }
            });
        }

        function updateStats() {
            // This function would ideally fetch stats via AJAX
            // For now, we'll count from the current table data
            let draftCount = 0;
            let processCount = 0;
            let completedCount = 0;
            let totalCount = 0;

            // Get all rows in the current view
            dataTable.rows({
                search: 'applied'
            }).every(function() {
                const row = this.data();
                const status = row.status;

                totalCount++;

                switch (status) {
                    case 'draft':
                        draftCount++;
                        break;
                    case 'process':
                        processCount++;
                        break;
                    case 'completed':
                        completedCount++;
                        break;
                }
            });

            $('#totalReceipts').text(totalCount);
            $('#draftReceipts').text(draftCount);
            $('#processReceipts').text(processCount);
            $('#completedReceipts').text(completedCount);
        }

        function refreshData() {
            dataTable.ajax.reload(null, false);
            updateStats();

            Swal.fire({
                icon: 'success',
                title: 'Data berhasil diperbarui',
                showConfirmButton: false,
                timer: 1500
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Set default dates (last 30 days)
            const endDate = new Date();
            const startDate = new Date();
            startDate.setDate(startDate.getDate() - 30);

            $('#startDate').val(startDate.toISOString().split('T')[0]);
            $('#endDate').val(endDate.toISOString().split('T')[0]);

            setTimeout(updateStats, 1000);
        });
    </script>
@endpush
