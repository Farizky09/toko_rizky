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

            <!-- Filter Section -->
            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200/60">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
                    <!-- Filter Lokasi -->
                    <div>
                        <label for="filter_location" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <select id="filter_location" name="filter_location"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">Semua Lokasi</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Kategori -->
                    <div>
                        <label for="filter_category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select id="filter_category" name="filter_category"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <label for="filter_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="filter_status" name="filter_status"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>

                    <!-- Filter Satuan Kecil -->
                    <div>
                        <label for="filter_unit_small" class="block text-sm font-medium text-gray-700 mb-1">Satuan
                            Kecil</label>
                        <select id="filter_unit_small" name="filter_unit_small"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">Semua Satuan Kecil</option>
                            @foreach ($unitSmalls as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Satuan Besar -->
                    <div>
                        <label for="filter_unit_large" class="block text-sm font-medium text-gray-700 mb-1">Satuan
                            Besar</label>
                        <select id="filter_unit_large" name="filter_unit_large"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">Semua Satuan Besar</option>
                            @foreach ($unitLarges as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
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
                                <th>Satuan</th>
                                <th>Konversi</th>
                                <th>Stok</th>
                                <th>Total Stock (dalam satuan kecil)</th>
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

    <!-- Modal Detail Produk -->
    <div id="productDetailModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content rounded-xl shadow-xl">
                <!-- Header -->
                <div class="modal-header border-b bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h5 class="modal-title font-semibold text-gray-900 flex items-center gap-2">
                        <span class="mdi mdi-information-outline text-blue-600 text-xl"></span>
                        Detail Produk
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-gray-400 hover:text-gray-600">&times;</span>
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body p-6">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="space-y-3">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kode
                                        Produk</label>
                                    <p class="mt-1 text-sm font-semibold text-gray-900" id="detailCode">-</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nama
                                        Produk</label>
                                    <p class="mt-1 text-sm font-semibold text-gray-900" id="detailName">-</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label
                                        class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kategori</label>
                                    <p class="mt-1 text-sm font-semibold text-gray-900" id="detailCategory">-</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Satuan
                                        Besar</label>
                                    <p class="mt-1 text-sm font-semibold text-gray-900" id="detailUnitLarge">-</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Satuan
                                        Kecil</label>
                                    <p class="mt-1 text-sm font-semibold text-gray-900" id="detailUnitSmall">-</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label
                                        class="text-xs font-medium text-gray-500 uppercase tracking-wide">Konversi</label>
                                    <p class="mt-1 text-sm font-semibold text-gray-900" id="detailConversion">-</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Stok
                                        Minimal</label>
                                    <p class="mt-1 text-sm font-semibold text-gray-900" id="detailMinStock">-</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</label>
                                    <p class="mt-1">
                                        <span id="detailStatus"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            -
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Deskripsi</label>
                            <p class="mt-1 text-sm text-gray-900" id="detailDescription">-</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-t bg-gray-50">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <span class="mdi mdi-close"></span> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
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
            // Initialize Select2
            $('select').select2();

            const table = $('#adminTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('products.index') }}',
                    type: 'GET',
                    data: function(d) {
                        d.location_id = $('#filter_location').val();
                        d.category_id = $('#filter_category').val();
                        d.status = $('#filter_status').val();
                        d.unit_small_id = $('#filter_unit_small').val();
                        d.unit_large_id = $('#filter_unit_large').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'code',
                        name: 'code',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    ${data || '-'}
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
                                    <div class="font-medium text-gray-900">${data || '-'}</div>
                                    <div class="text-sm text-gray-500">${row.description ? (row.description.length > 50 ? row.description.substring(0, 50) + '...' : row.description) : 'Tidak ada deskripsi'}</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'unitLarge_name',
                        name: 'unitLarge.name',
                        render: function(data, type, row) {
                            if (!data || data === '-') return '-';

                            const unitLargeAbbr = row.unitLarge_abbreviation || '-';
                            const unitSmallName = row.unitSmall_name || '-';
                            const unitSmallAbbr = row.unitSmall_abbreviation || '-';

                            return `<div class="text-sm">
                                <div class="font-medium text-gray-900">${data} (${unitLargeAbbr})</div>
                                <div class="text-gray-500">${unitSmallName} (${unitSmallAbbr})</div>
                            </div>`;
                        }
                    },
                    {
                        data: 'conversion',
                        name: 'conversion',
                        render: function(data) {
                            return `<div class="text-center">
                                <span class="font-mono font-medium text-gray-900">${parseFloat(data || 0).toLocaleString('id-ID')}</span>
                                <div class="text-xs text-gray-500">konversi</div>
                            </div>`;
                        }
                    },
                    {
                        data: 'stock_large',
                        name: 'stock_large',
                        render: function(data, type, row) {
                            const stockLarge = data || 0;
                            const stockSmall = row.stock_small || 0;
                            const unitLargeAbbr = row.unitLarge_abbreviation || '';
                            const unitSmallAbbr = row.unitSmall_abbreviation || '';

                            return `<div class="text-center">
                                <span class="font-mono font-medium text-gray-900">
                                    ${parseInt(stockLarge).toLocaleString('id-ID')}${unitLargeAbbr} / ${parseInt(stockSmall).toLocaleString('id-ID')}${unitSmallAbbr}
                                </span>
                                <div class="text-xs text-gray-500">stok tersedia</div>
                            </div>`;
                        }
                    },
                    {
                        data: 'total_stock_small',
                        name: 'total_stock_small'
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

            // Event listeners for filters
            $('#filter_location, #filter_category, #filter_status, #filter_unit_small, #filter_unit_large').change(
                function() {
                    table.ajax.reload();
                });

            // Detail Modal button click event
            $('#adminTable tbody').on('click', '.detail-modal-btn', function() {
                var productId = $(this).data('id');
                showProductDetail(productId);
            });
        });

        function showProductDetail(productId) {
            // Show loading state
            $('#detailCode').text('Loading...');
            $('#detailName').text('Loading...');
            $('#detailCategory').text('Loading...');
            $('#detailUnitLarge').text('Loading...');
            $('#detailUnitSmall').text('Loading...');
            $('#detailConversion').text('Loading...');
            $('#detailMinStock').text('Loading...');
            $('#detailStatus').text('Loading...');
            $('#detailDescription').text('Loading...');

            // Show modal
            $('#productDetailModal').modal('show');

            $.ajax({
                url: '{{ route('products.show', ':id') }}'.replace(':id', productId),
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;

                        $('#detailCode').text(data.code || '-');
                        $('#detailName').text(data.name || '-');
                        $('#detailCategory').text(data.category_name || '-');
                        $('#detailUnitLarge').text((data.unitLarge_name || '-') + ' (' + (data
                            .unitLarge_abbreviation || '-') + ')');
                        $('#detailUnitSmall').text((data.unitSmall_name || '-') + ' (' + (data
                            .unitSmall_abbreviation || '-') + ')');
                        $('#detailConversion').text(parseFloat(data.conversion || 0).toLocaleString('id-ID'));
                        $('#detailMinStock').text(parseInt(data.min_stock || 0).toLocaleString('id-ID'));

                        // Update status dengan styling
                        const statusElement = $('#detailStatus');
                        statusElement.removeClass(
                            'bg-gray-100 text-gray-800 bg-green-100 text-green-800 bg-red-100 text-red-800');
                        if (data.status === 'active') {
                            statusElement.addClass('bg-green-100 text-green-800').html(
                                '<span class="mdi mdi-check-circle text-green-500 mr-1"></span>Aktif'
                            );
                        } else {
                            statusElement.addClass('bg-red-100 text-red-800').html(
                                '<span class="mdi mdi-close-circle text-red-500 mr-1"></span>Nonaktif'
                            );
                        }

                        $('#detailDescription').text(data.description || 'Tidak ada deskripsi');
                    } else {
                        Alert.error(response.message || 'Gagal memuat detail produk.');
                        $('#productDetailModal').modal('hide');
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    Alert.error('Gagal memuat detail produk.');
                    $('#productDetailModal').modal('hide');
                }
            });
        }

        function updateStats() {
            const table = $('#adminTable').DataTable();
            const totalRecords = table.page.info().recordsTotal;

            let activeCount = 0;
            let inactiveCount = 0;

            table.rows({
                search: 'applied'
            }).every(function() {
                const rowData = this.data();
                if (rowData.status === 'active') {
                    activeCount++;
                } else {
                    inactiveCount++;
                }
            });

            $('#totalProducts').text(totalRecords.toLocaleString('id-ID'));
            $('#activeProducts').text(activeCount.toLocaleString('id-ID'));
            $('#inactiveProducts').text(inactiveCount.toLocaleString('id-ID'));

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
