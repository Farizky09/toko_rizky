@extends('layouts.master')

@section('title', 'Manajemen Produk')

@section('content')
    <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <!-- Page Title & Description -->
                <div class="space-y-3">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">
                            <span class="mdi mdi-package-variant text-xl text-blue-600"></span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Manajemen Produk</h1>
                            <p class="text-gray-600 mt-1">Kelola semua produk dalam sistem inventory</p>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <span class="mdi mdi-lightbulb-on-outline text-amber-500"></span>
                        <span>Produk adalah barang yang dikelola dalam sistem inventory dengan satuan dan kategori</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    @canany(['create_products'])
                        <a href="{{ route('products.create') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-blue-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="mdi mdi-plus-circle-outline text-lg"></span>
                            Tambah Produk
                        </a>
                    @endcanany
                </div>
            </div>
        </div>

        <!-- Stats Cards Section -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <!-- Total Products Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50">
                            <span class="mdi mdi-package-variant-closed text-xl text-blue-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Total Produk</p>
                        <p class="text-2xl font-semibold text-gray-900" id="totalProducts">0</p>
                    </div>
                </div>
            </div>

            <!-- Active Products Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50">
                            <span class="mdi mdi-package-check text-xl text-green-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Produk Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900" id="activeProducts">0</p>
                    </div>
                </div>
            </div>

            <!-- Inactive Products Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-50">
                            <span class="mdi mdi-package-remove text-xl text-gray-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Produk Nonaktif</p>
                        <p class="text-2xl font-semibold text-gray-900" id="inactiveProducts">0</p>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-50">
                            <span class="mdi mdi-update text-xl text-amber-600"></span>
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
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Produk</h3>
                            <p class="text-sm text-gray-600">Semua produk yang terdaftar dalam sistem</p>
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
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Konversi</th>
                                <th>Stok Min</th>
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
                        Produk adalah barang yang dikelola dalam sistem inventory. Setiap produk memiliki satuan besar dan
                        kecil dengan konversi yang ditentukan, serta kategori untuk pengelompokan.
                    </p>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        @if (session('success'))
            Alert.success("{{ session('success') }}");
        @endif

        $(document).ready(function() {
            $('#adminTable').DataTable({
                responsive: true,
                ajax: {
                    url: '{{ route('products.index') }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    },
                    {
                        data: 'code',
                        name: 'code',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        ${data}
                                    </span>
                                </div>`;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                    <span class="mdi mdi-package-variant mr-3 text-lg text-blue-600"></span>
                                    <div>
                                        <div class="font-medium text-gray-900">${data}</div>
                                        <div class="text-sm text-gray-500">${row.description ? row.description.substring(0, 50) + '...' : 'Tidak ada deskripsi'}</div>
                                    </div>
                                </div>`;
                        }
                    },
                    {
                        data: 'category.name',
                        name: 'category.name',
                        render: function(data) {
                            if (!data) return '-';
                            return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ${data}
                                </span>`;
                        }
                    },
                    {
                        data: 'unit.name',
                        name: 'unit.name',
                        render: function(data, type, row) {
                            if (!row.unit_large_name || !row.unit_small_name) return '-';
                            return `<div class="text-sm">
                                    <div class="font-medium">${row.unit_large_name} (${row.unit_large_abbreviation})</div>
                                    <div class="text-gray-500">1 ${row.unit_large_abbreviation} = ${row.conversion} ${row.unit_small_abbreviation}</div>
                                </div>`;
                        }
                    },
                    {
                        data: 'conversion',
                        name: 'conversion',
                        render: function(data) {
                            return `<span class="font-mono font-medium">${parseFloat(data).toLocaleString('id-ID')}</span>`;
                        }
                    },
                    {
                        data: 'min_stock',
                        name: 'min_stock',
                        render: function(data) {
                            return `<span class="font-mono font-medium">${parseInt(data).toLocaleString('id-ID')}</span>`;
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            if (data === 'active') {
                                return `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="mdi mdi-check-circle text-green-500"></span>
                                        Aktif
                                    </span>`;
                            } else {
                                return `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <span class="mdi mdi-close-circle text-red-500"></span>
                                        Nonaktif
                                    </span>`;
                            }
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

            // In a real application, you would get these counts from the server
            $('#totalProducts').text(totalRecords);
            $('#activeProducts').text(totalRecords); // Assuming all are active for demo
            $('#inactiveProducts').text('0'); // Assuming none are inactive for demo

            const now = new Date();
            $('#lastUpdated').text(now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            }));
        }

        function refreshData() {
            const table = $('#adminTable').DataTable();
            table.ajax.reload(null, false);
            updateStats();
            Alert.info('Data berhasil diperbarui');
        }

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(updateStats, 1000);
        });
    </script>
@endpush
