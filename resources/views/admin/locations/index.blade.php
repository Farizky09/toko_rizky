@extends('layouts.master')

@section('title', 'Manajemen Lokasi')

@section('content')
    <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <!-- Page Title & Description -->
                <div class="space-y-3">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50">
                            <span class="mdi mdi-map-marker text-xl text-indigo-600"></span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Manajemen Lokasi</h1>
                            <p class="text-gray-600 mt-1">Kelola semua lokasi dalam cabang perusahaan</p>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <span class="mdi mdi-lightbulb-on-outline text-amber-500"></span>
                        <span>Lokasi adalah area spesifik dalam cabang seperti gudang, ruangan, atau rak</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    @canany(['create_locations'])
                        <a href="{{ route('locations.create') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-indigo-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <span class="mdi mdi-plus-circle-outline text-lg"></span>
                            Tambah Lokasi
                        </a>
                    @endcanany
                </div>
            </div>
        </div>

        <!-- Stats Cards Section -->
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <!-- Total Locations Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50">
                            <span class="mdi mdi-map-marker-outline text-xl text-indigo-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Total Lokasi</p>
                        <p class="text-2xl font-semibold text-gray-900" id="totalLocations">0</p>
                    </div>
                </div>
            </div>

            {{-- <!-- Locations by Type Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50">
                            <span class="mdi mdi-format-list-bulleted-type text-xl text-blue-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Jenis Lokasi</p>
                        <p class="text-2xl font-semibold text-gray-900" id="locationTypes">0</p>
                    </div>
                </div>
            </div> --}}

            <!-- Active Branches Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50">
                            <span class="mdi mdi-store-check text-xl text-green-600"></span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Cabang Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900" id="activeBranches">0</p>
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
            <div class="rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="mdi mdi-table text-indigo-600 text-xl"></span>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Lokasi</h3>
                            <p class="text-sm text-gray-600">Semua lokasi yang terdaftar dalam sistem</p>
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
                                <th>Nama Lokasi</th>
                                <th>Cabang</th>
                                <th>Tipe</th>
                                <th>Status Cabang</th>
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
                        Lokasi digunakan untuk mengorganisir area spesifik dalam cabang seperti gudang, ruangan, atau rak
                        penyimpanan.
                        Pastikan setiap lokasi terkait dengan cabang yang tepat.
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
                ajax: {
                    url: '{{ route('locations.index') }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                            return `<div class="flex items-center">
                                    <span class="mdi mdi-map-marker-outline mr-3 text-lg text-indigo-600"></span>
                                    <div>
                                        <div class="font-medium text-gray-900">${data}</div>
                                        <div class="text-sm text-gray-500">${row.type_display || row.type}</div>
                                    </div>
                                </div>`;
                        }
                    },
                    {
                        data: 'branch.name',
                        name: 'branch.name',
                        render: function(data, type, row) {
                            if (!data) return '-';
                            return `<div class="flex items-center">
                                    <span class="mdi mdi-store-outline mr-2 text-green-600"></span>
                                    <div>
                                        <div class="font-medium text-gray-900">${data}</div>
                                        <div class="text-sm text-gray-500">${row.branch_code || ''}</div>
                                    </div>
                                </div>`;
                        }
                    },
                    {
                        data: 'type',
                        name: 'type',
                        render: function(data) {
                            const typeColors = {
                                'gudang': 'bg-orange-100 text-orange-800',
                                'toko': 'bg-blue-100 text-blue-800'
                            };

                            const colorClass = typeColors[data] || 'bg-gray-100 text-gray-800';
                            const displayName = {
                                'gudang': 'Gudang',
                                'toko': 'Toko'
                            } [data] || data;

                            return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${colorClass}">
                                    ${displayName}
                                </span>`;
                        }
                    },
                    {
                        data: 'branch.status',
                        name: 'branch.status',
                        render: function(data) {
                            if (data == 'active') {
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

            $('#totalLocations').text(totalRecords);
            // $('#locationTypes').text('5'); // Assuming 5 types for demo
            $('#activeBranches').text(totalRecords); // Assuming all branches are active

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
