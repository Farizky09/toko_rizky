@extends('layouts.master')

@section('title', 'Manajemen Pembelian')

@section('content')
    <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <!-- Page Title & Description -->
                <div class="space-y-3">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">
                            <span class="mdi mdi-cart-arrow-down text-xl text-blue-600"></span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Manajemen Pembelian</h1>
                            <p class="text-gray-600 mt-1">Kelola semua transaksi pembelian dalam sistem</p>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <span class="mdi mdi-lightbulb-on-outline text-amber-500"></span>
                        <span>Pembelian adalah proses pembelian barang dari supplier ke gudang</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    @canany(['create_purchases'])
                        <a href="{{ route('purchases.create') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-blue-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="mdi mdi-plus-circle-outline text-lg"></span>
                            Tambah Pembelian
                        </a>
                    @endcanany
                </div>
            </div>
        </div>

        <!-- Stats Cards Section -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <!-- Total Purchases Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50">
                            <span class="mdi mdi-cart-outline text-xl text-blue-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Total Pembelian</p>
                        <p class="text-2xl font-semibold text-gray-900" id="totalPurchases">0</p>
                    </div>
                </div>
            </div>

            <!-- Completed Purchases Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50">
                            <span class="mdi mdi-cart-check text-xl text-green-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Pembelian Selesai</p>
                        <p class="text-2xl font-semibold text-gray-900" id="completedPurchases">0</p>
                    </div>
                </div>
            </div>

            <!-- Draft Purchases Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-50">
                            <span class="mdi mdi-cart-arrow-down text-xl text-amber-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Pembelian Draft</p>
                        <p class="text-2xl font-semibold text-gray-900" id="draftPurchases">0</p>
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

        <!-- Table Section -->
        <div class="space-y-4">
            <!-- Table Header Card -->
            <div class="rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="mdi mdi-table text-blue-600 text-xl"></span>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Pembelian</h3>
                            <p class="text-sm text-gray-600">Semua transaksi pembelian yang terdaftar dalam sistem</p>
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
                    <table id="adminTable" class="table dt-responsive nowrap m-1" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>No. Pembelian</th>
                                <th>Supplier</th>
                                <th>Cabang</th>
                                <th>Tanggal</th>
                                <th>Total Items</th>
                                <th>Total Quantity</th>
                                <th>Total Amount</th>
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
                        Pembelian digunakan untuk mencatat semua transaksi pembelian barang dari supplier.
                        Pastikan status pembelian diperbarui sesuai dengan proses aktual.
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

        $(document).ready(function() {
            $('#adminTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('purchases.index') }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'purchase_number',
                        name: 'purchase_number',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    ${data}
                                </span>
                            </div>`;
                        }
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier_name',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <span class="mdi mdi-truck mr-3 text-lg text-green-600"></span>
                                <div>
                                    <div class="font-medium text-gray-900">${data || '-'}</div>
                                    <div class="text-sm text-gray-500">Supplier</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <span class="mdi mdi-store mr-3 text-lg text-purple-600"></span>
                                <div>
                                    <div class="font-medium text-gray-900">${data || '-'}</div>
                                    <div class="text-sm text-gray-500">Cabang</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'purchase_date',
                        name: 'purchase_date',
                        render: function(data) {
                            const date = new Date(data);
                            const formattedDate = date.toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric'
                            });
                            return `<div class="text-center">
                                <div class="font-medium text-gray-900">${formattedDate}</div>
                                <div class="text-xs text-gray-500">tanggal</div>
                            </div>`;
                        }
                    },
                    {
                        data: 'total_items',
                        name: 'total_items',
                        render: function(data) {
                            return `<div class="text-center">
                                <span class="font-mono font-medium text-gray-900">${parseInt(data || 0).toLocaleString('id-ID')}</span>
                                <div class="text-xs text-gray-500">items</div>
                            </div>`;
                        }
                    },
                    {
                        data: null,
                        name: 'total_quantity',
                        render: function(data, type, row) {
                            const total = (row.total_quantity_large || 0) + (row
                                .total_quantity_small || 0);
                            return `<div class="text-center">
                                <span class="font-mono font-medium text-gray-900">${parseInt(total).toLocaleString('id-ID')}</span>
                                <div class="text-xs text-gray-500">quantity</div>
                            </div>`;
                        }
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        render: function(data) {
                            const formattedAmount = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(data || 0);

                            return `<div class="text-right">
                                <div class="font-medium text-gray-900">${formattedAmount}</div>
                                <div class="text-xs text-gray-500">total</div>
                            </div>`;
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            const statusConfig = {
                                'completed': {
                                    class: 'bg-green-100 text-green-800',
                                    icon: 'mdi-check-circle',
                                    label: 'Completed'
                                },
                                'draft': {
                                    class: 'bg-amber-100 text-amber-800',
                                    icon: 'mdi-pencil',
                                    label: 'Draft'
                                },
                                'cancelled': {
                                    class: 'bg-red-100 text-red-800',
                                    icon: 'mdi-close-circle',
                                    label: 'Cancelled'
                                }
                            };

                            const config = statusConfig[data] || statusConfig.draft;
                            return `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium ${config.class}">
                                <span class="mdi ${config.icon}"></span>
                                ${config.label}
                            </span>`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                drawCallback: function(settings) {
                    updateStats();
                }
            });
        });

        function updateStats() {
            const table = $('#adminTable').DataTable();
            const totalRecords = table.page.info().recordsTotal;

            // Hitung status dari data yang terlihat
            let completedCount = 0;
            let draftCount = 0;
            let cancelledCount = 0;

            table.rows({
                search: 'applied'
            }).every(function() {
                const rowData = this.data();
                if (rowData.status === 'completed') {
                    completedCount++;
                } else if (rowData.status === 'draft') {
                    draftCount++;
                } else if (rowData.status === 'cancelled') {
                    cancelledCount++;
                }
            });

            $('#totalPurchases').text(totalRecords.toLocaleString('id-ID'));
            $('#completedPurchases').text(completedCount.toLocaleString('id-ID'));
            $('#draftPurchases').text(draftCount.toLocaleString('id-ID'));

            const now = new Date();
            $('#lastUpdated').text(now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }));
        }

        function refreshData() {
            const table = $('#adminTable').DataTable();
            table.ajax.reload(null, false);


            setTimeout(updateStats, 500);

            Alert.info('Data berhasil diperbarui');
        }

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(updateStats, 1000);
        });
    </script>
@endpush
