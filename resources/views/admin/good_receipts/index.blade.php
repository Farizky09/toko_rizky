@extends('layouts.master')

@section('title', 'Manajemen Penerimaan Barang')

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
                            <h1 class="text-2xl font-bold text-gray-900">Manajemen Penerimaan Barang</h1>
                            <p class="text-gray-600 mt-1">Kelola semua transaksi penerimaan barang dari supplier</p>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <span class="mdi mdi-lightbulb-on-outline text-amber-500"></span>
                        <span>Good Receipt adalah dokumen penerimaan barang dari pembelian ke gudang</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    @canany(['create_good_receipts'])
                        <a href="{{ route('good-receipts.create') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-green-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <span class="mdi mdi-plus-circle-outline text-lg"></span>
                            Tambah Penerimaan
                        </a>
                    @endcanany

                    <!-- Import/Export Buttons -->
                    <div class="flex items-center gap-2">
                        <button type="button"
                            class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition-all duration-200 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <span class="mdi mdi-download text-lg"></span>
                            Export
                        </button>
                    </div>
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
                        <p class="text-sm font-medium text-gray-600">Total Penerimaan</p>
                        <p class="text-2xl font-semibold text-gray-900" id="totalReceipts">0</p>
                    </div>
                </div>
            </div>

            <!-- This Month Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50">
                            <span class="mdi mdi-calendar-month text-xl text-blue-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Bulan Ini</p>
                        <p class="text-2xl font-semibold text-gray-900" id="monthlyReceipts">0</p>
                    </div>
                </div>
            </div>

            <!-- Pending Verification Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-50">
                            <span class="mdi mdi-clock-outline text-xl text-amber-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                        <p class="text-2xl font-semibold text-gray-900" id="pendingReceipts">0</p>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-50">
                            <span class="mdi mdi-update text-xl text-purple-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Terakhir Diperbarui</p>
                        <p class="text-lg font-semibold text-gray-900" id="lastUpdated">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-6 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200/50">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Filter Pencarian</h3>
                    <p class="text-sm text-gray-600">Saring data penerimaan barang sesuai kebutuhan</p>
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
                            class="rounded-lg border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">Semua Status</option>
                            <option value="draft">Draft</option>
                            <option value="received">Diterima</option>
                            <option value="verified">Terverifikasi</option>
                            <option value="partially_received">Parsial</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>

                    <!-- Branch Filter -->
                    <div class="w-full sm:w-auto">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cabang</label>
                        <select id="branchFilter"
                            class="rounded-lg border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">Semua Cabang</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
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
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Penerimaan Barang</h3>
                            <p class="text-sm text-gray-600">Semua transaksi penerimaan barang yang terdaftar</p>
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
                                <th>ID</th>
                                <th>No. GR</th>
                                <th>Tanggal Terima</th>
                                <th>No. Pembelian</th>
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
                    <h4 class="font-semibold text-blue-900">Butuh Bantuan?</h4>
                    <p class="text-sm text-blue-700 mt-1">
                        Good Receipt (GR) adalah dokumen resmi yang mencatat penerimaan barang dari supplier ke gudang.
                        Pastikan data GR sesuai dengan fisik barang yang diterima sebelum melakukan verifikasi.
                    </p>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        @if (session('success'))
            Alert.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            Alert.error("{{ session('error') }}");
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
                        d.branch_id = $('#branchFilter').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'gr_number',
                        name: 'gr_number',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
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
                            const formattedDate = date.toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric'
                            });
                            return `<div class="text-center">
                                <div class="font-medium text-gray-900">${formattedDate}</div>
                                <div class="text-xs text-gray-500">tanggal terima</div>
                            </div>`;
                        }
                    },
                    {
                        data: 'purchase_number',
                        name: 'purchase.purchase_number',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <span class="mdi mdi-file-document-outline mr-3 text-lg text-blue-600"></span>
                                <div>
                                    <div class="font-medium text-gray-900">${data || '-'}</div>
                                    <div class="text-xs text-gray-500">No. PO</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'supplier_name',
                        name: 'purchase.supplier.name',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <span class="mdi mdi-truck mr-3 text-lg text-gray-600"></span>
                                <div>
                                    <div class="font-medium text-gray-900">${data || '-'}</div>
                                    <div class="text-xs text-gray-500">Supplier</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'branch_name',
                        name: 'branch.name',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <span class="mdi mdi-store mr-3 text-lg text-purple-600"></span>
                                <div>
                                    <div class="font-medium text-gray-900">${data || '-'}</div>
                                    <div class="text-xs text-gray-500">Cabang</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'location_name',
                        name: 'location.name',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <span class="mdi mdi-map-marker mr-3 text-lg text-red-600"></span>
                                <div>
                                    <div class="font-medium text-gray-900">${data || '-'}</div>
                                    <div class="text-xs text-gray-500">Lokasi</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'received_by_name',
                        name: 'receivedBy.name',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <span class="mdi mdi-account-circle mr-3 text-lg text-amber-600"></span>
                                <div>
                                    <div class="font-medium text-gray-900">${data || '-'}</div>
                                    <div class="text-xs text-gray-500">Penerima</div>
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
                                    badgeClass = 'bg-amber-100 text-amber-800';
                                    statusText = 'Draft';
                                    icon = 'mdi-pencil';
                                    break;
                                case 'received':
                                    badgeClass = 'bg-blue-100 text-blue-800';
                                    statusText = 'Diterima';
                                    icon = 'mdi-package-variant';
                                    break;
                                case 'verified':
                                    badgeClass = 'bg-green-100 text-green-800';
                                    statusText = 'Terverifikasi';
                                    icon = 'mdi-check-circle';
                                    break;
                                case 'partially_received':
                                    badgeClass = 'bg-purple-100 text-purple-800';
                                    statusText = 'Parsial';
                                    icon = 'mdi-package-variant-closed';
                                    break;
                                case 'cancelled':
                                    badgeClass = 'bg-red-100 text-red-800';
                                    statusText = 'Dibatalkan';
                                    icon = 'mdi-close-circle';
                                    break;
                            }

                            return `<div class="text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium ${badgeClass}">
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
            $('#branchFilter').val('');
            dataTable.ajax.reload();
            updateStats();
        }

        function verifyReceipt(id, grNumber) {
            if (typeof Swal === 'undefined') {
                return alert('SweetAlert not loaded');
            }

            Swal.fire({
                title: `Verifikasi Penerimaan "${grNumber}"?`,
                text: "Konfirmasi bahwa barang sudah sesuai dan lengkap. Proses ini tidak dapat dibatalkan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Verifikasi',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(`/good-receipts/${id}/verify`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText);
                            }
                            return response.json();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`);
                        });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Berhasil!',
                        'Penerimaan barang berhasil diverifikasi.',
                        'success'
                    ).then(() => {
                        dataTable.ajax.reload();
                    });
                }
            });
        }

        function confirmDelete(id, grNumber) {
            if (typeof Swal === 'undefined') {
                return alert('SweetAlert not loaded');
            }

            Swal.fire({
                title: `Hapus Penerimaan "${grNumber}"?`,
                text: "Data penerimaan barang akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }

        function updateStats() {
            const table = dataTable;
            const totalRecords = table.page.info().recordsTotal;

            // Get current month
            const now = new Date();
            const currentMonth = now.getMonth() + 1;
            const currentYear = now.getFullYear();

            // You might want to fetch these stats via AJAX for accuracy
            // For now, we'll just show basic stats

            $('#totalReceipts').text(totalRecords.toLocaleString('id-ID'));
            $('#monthlyReceipts').text('0'); // Would need AJAX call to get actual count
            $('#pendingReceipts').text('0'); // Would need AJAX call to get actual count

            $('#lastUpdated').text(now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }));
        }

        function refreshData() {
            dataTable.ajax.reload(null, false);

            setTimeout(updateStats, 500);

            if (typeof Alert !== 'undefined') {
                Alert.info('Data berhasil diperbarui');
            } else {
                alert('Data berhasil diperbarui');
            }
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
