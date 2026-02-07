@extends('layouts.master')

@section('title', 'Detail Produk - ' . $data->name)

@section('content')
    <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <!-- Breadcrumb -->
        <nav class="mb-6 flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <span class="mdi mdi-home mr-2"></span>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="mdi mdi-chevron-right text-gray-400"></span>
                        <a href="{{ route('products.index') }}"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                            Produk
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <span class="mdi mdi-chevron-right text-gray-400"></span>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detail Produk</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <!-- Page Title & Description -->
                <div class="space-y-3">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">
                            <span class="mdi mdi-package-variant text-xl text-blue-600"></span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $data->name }}</h1>
                            <p class="text-gray-600 mt-1">Detail lengkap informasi produk</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    @canany(['update_products'])
                        <a href="{{ route('products.edit', $data->id) }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-yellow-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-yellow-600 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                            <span class="mdi mdi-pencil-outline text-lg"></span>
                            Edit Produk
                        </a>
                    @endcanany

                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <span class="mdi mdi-arrow-left text-lg"></span>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Information Card -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/60">
                    <div class="mb-4 flex items-center gap-3 border-b pb-4">
                        <span class="mdi mdi-information-outline text-blue-600 text-xl"></span>
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Dasar</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide block mb-2">Kode
                                    Produk</label>
                                <p class="text-base font-semibold text-gray-900">{{ $data->code }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide block mb-2">Nama
                                    Produk</label>
                                <p class="text-base font-semibold text-gray-900">{{ $data->name }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wide block mb-2">Kategori</label>
                                <p class="text-base font-semibold text-gray-900">
                                    <span
                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        <span class="mdi mdi-tag"></span>
                                        {{ $data->category->name ?? '-' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label
                                    class="text-xs font-medium text-gray-500 uppercase tracking-wide block mb-2">Status</label>
                                <p class="text-base font-semibold">
                                    @if ($data->status === 'active')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <span class="mdi mdi-check-circle text-green-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <span class="mdi mdi-close-circle text-red-500"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide block mb-2">Stok
                                    Minimal</label>
                                <p class="text-base font-semibold text-gray-900">
                                    {{ number_format($data->min_stock, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide block mb-2">Dibuat
                                    Pada</label>
                                <p class="text-base font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y, H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Unit Information -->
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/60">
                    <div class="mb-4 flex items-center gap-3 border-b pb-4">
                        <span class="mdi mdi-ruler text-blue-600 text-xl"></span>
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Satuan</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-5 border border-blue-200">
                            <label
                                class="text-xs font-medium text-blue-700 uppercase tracking-wide block mb-3 flex items-center gap-2">
                                <span class="mdi mdi-package-variant-closed"></span>
                                Satuan Besar
                            </label>
                            <p class="text-2xl font-bold text-blue-900">{{ $data->unitLarge->name ?? '-' }}</p>
                            <p class="text-sm text-blue-700 mt-1">({{ $data->unitLarge->abbreviation ?? '-' }})</p>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-5 border border-green-200">
                            <label
                                class="text-xs font-medium text-green-700 uppercase tracking-wide block mb-3 flex items-center gap-2">
                                <span class="mdi mdi-package"></span>
                                Satuan Kecil
                            </label>
                            <p class="text-2xl font-bold text-green-900">{{ $data->unitSmall->name ?? '-' }}</p>
                            <p class="text-sm text-green-700 mt-1">({{ $data->unitSmall->abbreviation ?? '-' }})</p>
                        </div>
                        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-5 border border-amber-200">
                            <label
                                class="text-xs font-medium text-amber-700 uppercase tracking-wide block mb-3 flex items-center gap-2">
                                <span class="mdi mdi-swap-horizontal"></span>
                                Konversi
                            </label>
                            <p class="text-2xl font-bold text-amber-900">
                                {{ number_format($data->conversion, 0, ',', '.') }}</p>
                            <p class="text-sm text-amber-700 mt-1">per satuan besar</p>
                        </div>
                    </div>
                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <span class="mdi mdi-information text-blue-600 text-xl mt-0.5"></span>
                            <div>
                                <p class="text-sm text-blue-900 font-medium">
                                    1 {{ $data->unitLarge->name ?? '-' }}
                                    ({{ $data->unitLarge->abbreviation ?? '-' }})
                                    =
                                    {{ number_format($data->conversion, 0, ',', '.') }}
                                    {{ $data->unitSmall->name ?? '-' }}
                                    ({{ $data->unitSmall->abbreviation ?? '-' }})
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/60">
                    <div class="mb-4 flex items-center gap-3 border-b pb-4">
                        <span class="mdi mdi-text-box-outline text-blue-600 text-xl"></span>
                        <h3 class="text-lg font-semibold text-gray-900">Deskripsi</h3>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-700 leading-relaxed">
                            {{ $data->description ?? 'Tidak ada deskripsi' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Stock Card -->
                <div class="rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-lg text-white">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="mdi mdi-chart-box-outline text-3xl"></span>
                        <h3 class="text-lg font-semibold">Stok Saat Ini</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-white/10 backdrop-blur rounded-lg p-4">
                            <p class="text-xs font-medium opacity-90 mb-2">Satuan Besar</p>
                            <p class="text-3xl font-bold">
                                {{ number_format($data->stock_large ?? 0, 0, ',', '.') }}
                            </p>
                            <p class="text-sm opacity-80 mt-1">{{ $data->unitLarge->abbreviation ?? '-' }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur rounded-lg p-4">
                            <p class="text-xs font-medium opacity-90 mb-2">Satuan Kecil</p>
                            <p class="text-3xl font-bold">
                                {{ number_format($data->stock_small ?? 0, 0, ',', '.') }}
                            </p>
                            <p class="text-sm opacity-80 mt-1">{{ $data->unitSmall->abbreviation ?? '-' }}</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur rounded-lg p-4 border border-white/30">
                            <p class="text-xs font-medium opacity-90 mb-2">Total (Satuan Kecil)</p>
                            <p class="text-2xl font-bold">
                                {{ number_format($data->stock_large * $data->conversion + $data->stock_small, 0, ',', '.') }}
                            </p>
                            <p class="text-sm opacity-80 mt-1">{{ $data->unitSmall->abbreviation ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stock Status Warning -->
                @php
                    $totalStockSmall = $data->stock_large * $data->conversion + $data->stock_small;
                @endphp

                @if ($totalStockSmall <= $data->min_stock)
                    <div class="rounded-xl bg-red-50 border-2 border-red-200 p-6">
                        <div class="flex items-start gap-3">
                            <span class="mdi mdi-alert-circle text-red-600 text-2xl"></span>
                            <div>
                                <h4 class="font-semibold text-red-900 mb-2">Peringatan Stok Rendah!</h4>
                                <p class="text-sm text-red-700">
                                    Stok produk ini sudah mencapai atau di bawah batas minimal
                                    ({{ number_format($data->min_stock, 0, ',', '.') }}
                                    {{ $data->unitSmall->abbreviation ?? '' }}).
                                    Segera lakukan pemesanan ulang.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif ($totalStockSmall <= $data->min_stock * 1.5)
                    <div class="rounded-xl bg-amber-50 border-2 border-amber-200 p-6">
                        <div class="flex items-start gap-3">
                            <span class="mdi mdi-alert text-amber-600 text-2xl"></span>
                            <div>
                                <h4 class="font-semibold text-amber-900 mb-2">Perhatian Stok</h4>
                                <p class="text-sm text-amber-700">
                                    Stok produk ini mendekati batas minimal. Pertimbangkan untuk melakukan pemesanan
                                    ulang segera.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-xl bg-green-50 border-2 border-green-200 p-6">
                        <div class="flex items-start gap-3">
                            <span class="mdi mdi-check-circle text-green-600 text-2xl"></span>
                            <div>
                                <h4 class="font-semibold text-green-900 mb-2">Stok Aman</h4>
                                <p class="text-sm text-green-700">
                                    Stok produk ini masih dalam kondisi baik dan di atas batas minimal.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/60">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="mdi mdi-lightning-bolt text-yellow-500"></span>
                        Aksi Cepat
                    </h3>
                    <div class="space-y-3">
                        @canany(['update_products'])
                            <a href="{{ route('products.edit', $data->id) }}"
                                class="flex items-center gap-3 rounded-lg bg-yellow-50 px-4 py-3 text-sm font-medium text-yellow-700 transition-all hover:bg-yellow-100">
                                <span class="mdi mdi-pencil-outline text-lg"></span>
                                Edit Produk
                            </a>
                        @endcanany
                        <a href="{{ route('products.index') }}"
                            class="flex items-center gap-3 rounded-lg bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all hover:bg-gray-100">
                            <span class="mdi mdi-format-list-bulleted text-lg"></span>
                            Daftar Produk
                        </a>
                        @canany(['delete_products'])
                            <button type="button"
                                onclick="Alert.confirm('Hapus Produk?', 'Produk {{ $data->name }} akan dihapus permanen', () => { document.getElementById('delete-form').submit(); })"
                                class="flex w-full items-center gap-3 rounded-lg bg-red-50 px-4 py-3 text-sm font-medium text-red-700 transition-all hover:bg-red-100">
                                <span class="mdi mdi-trash-can-outline text-lg"></span>
                                Hapus Produk
                            </button>
                            <form action="{{ route('products.delete', $data->id) }}" method="POST" id="delete-form"
                                class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endcanany
                    </div>
                </div>

                <!-- Metadata -->
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200/60">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="mdi mdi-clock-outline text-gray-500"></span>
                        Informasi Tambahan
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-3">
                            <span class="mdi mdi-calendar-plus text-gray-400"></span>
                            <div>
                                <p class="text-gray-500">Dibuat pada</p>
                                <p class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="mdi mdi-calendar-edit text-gray-400"></span>
                            <div>
                                <p class="text-gray-500">Terakhir diperbarui</p>
                                <p class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($data->updated_at)->translatedFormat('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
