@extends('layouts.master')

@section('title', 'Detail Pembelian - ' . $purchase->purchase_number)

@section('content')
    <div class="container-fluid px-6 py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="h3 mb-2 text-gray-900 font-weight-bold">Detail Pembelian</h1>
                <p class="text-muted">Informasi lengkap pembelian</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">
                    <i class="mdi mdi-arrow-left me-2"></i>Kembali ke Daftar
                </a>

                <!-- PERBAIKAN: Tombol Edit hanya muncul jika status 'draft' -->
                @if ($purchase->status == 'draft')
                    <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-warning">
                        <i class="mdi mdi-pencil-outline me-2"></i>Edit
                    </a>
                @endif

                <button type="button" class="btn btn-outline-primary" onclick="printPurchase()">
                    <i class="mdi mdi-printer-outline me-2"></i>Print
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Purchase Details Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-cart-arrow-down me-2"></i>Informasi Pembelian
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%" class="text-muted">No. Pembelian</td>
                                        <td width="60%">
                                            <span
                                                class="badge bg-blue-100 text-blue-800 fs-6">{{ $purchase->purchase_number }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Tanggal Pembelian</td>
                                        <td class="fw-semibold">
                                            {{ \Carbon\Carbon::parse($purchase->purchase_date)->translatedFormat('d F Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Cabang</td>
                                        <td class="fw-semibold">{{ $purchase->branch_name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Lokasi</td>
                                        <td class="fw-semibold">{{ $purchase->location_name ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%" class="text-muted">Supplier</td>
                                        <td width="60%" class="fw-semibold">{{ $purchase->supplier_name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Status</td>
                                        <td>

                                            @php
                                                $statusConfig = [
                                                    'draft' => [
                                                        'class' => 'bg-amber-100 text-amber-800',
                                                        'icon' => 'mdi-pencil',
                                                        'label' => 'Draft',
                                                    ],
                                                    'completed' => [
                                                        'class' => 'bg-green-100 text-green-800',
                                                        'icon' => 'mdi-check-circle',
                                                        'label' => 'Completed',
                                                    ],
                                                    'cancelled' => [
                                                        'class' => 'bg-red-100 text-red-800',
                                                        'icon' => 'mdi-close-circle',
                                                        'label' => 'Cancelled',
                                                    ],
                                                ];
                                                $config = $statusConfig[$purchase->status] ?? $statusConfig['draft'];
                                            @endphp
                                            <span class="badge {{ $config['class'] }}">
                                                <i class="mdi {{ $config['icon'] }} me-1"></i>{{ $config['label'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Dibuat Oleh</td>
                                        <td class="fw-semibold">{{ $purchase->user_name ?? 'System' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Terakhir Diupdate</td>
                                        <td class="fw-semibold">
                                            {{ \Carbon\Carbon::parse($purchase->updated_at)->translatedFormat('d F Y H:i') }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if ($purchase->notes)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <label class="form-label text-muted">Catatan</label>
                                    <div class="border rounded p-3 bg-light">
                                        {{ $purchase->notes }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Items Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-format-list-bulleted me-2"></i>Daftar Item
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">

                                @if ($purchase->status == 'completed')
                                    <!-- Tampilan untuk PO 'Completed' (Menampilkan Qty Terima) -->
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">Produk</th>
                                            <th width="10%" class="text-center">Qty Dipesan<br>(Besar/Kecil)</th>
                                            <th width="10%" class="text-center text-success">Qty
                                                Diterima<br>(Besar/Kecil)</th>
                                            <th width="15%" class="text-end">Harga Beli<br>(Besar/Kecil)</th>
                                            <th width="15%" class="text-end">Subtotal Dipesan</th>
                                            <th width="20%">Catatan Item</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($purchase->purchasesItems ?? []) as $index => $item)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>

                                                <td>
                                                    {{-- @dump($item); --}}

                                                    <div class="fw-semibold">{{ $item->product_name ?? 'Produk Dihapus' }}
                                                    </div>
                                                    <small class="text-muted">Kode:
                                                        {{ $item->product_code ?? '-' }}</small>
                                                </td>
                                                <td class="text-center">{{ (float) $item->qty_large }} /
                                                    {{ (float) $item->qty_small }}</td>
                                                <td class="text-center text-success fw-bold">
                                                    {{ (float) $item->qty_received_large }} /
                                                    {{ (float) $item->qty_received_small }}</td>
                                                <td class="text-end">@currency($item->purchase_price_large) / @currency($item->purchase_price_small)</td>
                                                <td class="text-end fw-bold">@currency($item->subtotal ?? 0)</td>
                                                <td class="text-muted"><small>{{ $item->item_notes ?? '-' }}</small></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">Tidak ada item.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                @else
                                    <!-- Tampilan untuk PO 'Draft' / 'Cancelled' (Qty Dipesan Saja) -->
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">Produk</th>
                                            <th width="10%" class="text-center">Qty Besar</th>
                                            <th width="10%" class="text-center">Qty Kecil</th>
                                            <th width="15%" class="text-end">Harga Beli Besar</th>
                                            <th width="15%" class="text-end">Harga Beli Kecil</th>
                                            <th width="15%" class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($purchase->purchasesItems ?? []) as $index => $item)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    <!-- PERBAIKAN: Menggunakan $item->product->name -->
                                                    <div class="fw-semibold">{{ $item->product_name ?? 'Produk Dihapus' }}
                                                    </div>
                                                    <small class="text-muted">Kode:
                                                        {{ $item->product_code ?? '-' }}</small>
                                                </td>
                                                <td class="text-center">{{ (float) $item->qty_large }}</td>
                                                <td class="text-center">{{ (float) $item->qty_small }}</td>
                                                <td class="text-end">@currency($item->purchase_price_large ?? 0)</td>
                                                <td class="text-end">@currency($item->purchase_price_small ?? 0)</td>
                                                <td class="text-end fw-bold text-success">@currency($item->subtotal ?? 0)</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">Tidak ada item.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                @endif
                                <!-- Akhir dari tabel dinamis -->

                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="2" class="text-end fw-bold">Total Dipesan:</td>
                                        <td class="text-center fw-bold">{{ (float) $purchase->total_quantity_large }}</td>
                                        <td class="text-center fw-bold">{{ (float) $purchase->total_quantity_small }}</td>
                                        <td colspan="2" class="text-end fw-bold">Subtotal:</td>
                                        <td class="text-end fw-bold text-success">@currency($purchase->subtotal)</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-end fw-bold">Pajak:</td>
                                        <td class="text-end fw-bold">@currency($purchase->tax)</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-end fw-bold">Diskon:</td>
                                        <td class="text-end fw-bold text-danger">- @currency($purchase->discount)</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td colspan="6" class="text-end fw-bold fs-5">Total Amount:</td>
                                        <td class="text-end fw-bold fs-5">@currency($purchase->total_amount)</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div class="col-lg-4">
                <!-- Summary Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-chart-bar me-2"></i>Ringkasan PO (Dipesan)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h3 class="text-success fw-bold">@currency($purchase->total_amount)</h3>
                            <p class="text-muted">Total Nilai PO</p>
                        </div>
                        <div class="space-y-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total Items</span>
                                <span class="badge bg-blue-100 text-blue-800">{{ $purchase->total_items }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total Quantity</span>
                                <span
                                    class="badge bg-green-100 text-green-800">{{ (float) $purchase->total_quantity_large + (float) $purchase->total_quantity_small }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Quantity Besar</span>
                                <span class="fw-semibold">{{ (float) $purchase->total_quantity_large }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Quantity Kecil</span>
                                <span class="fw-semibold">{{ (float) $purchase->total_quantity_small }}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="space-y-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-semibold">@currency($purchase->subtotal)</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Pajak</span>
                                <span class="fw-semibold">@currency($purchase->tax)</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Diskon</span>
                                <span class="fw-semibold text-danger">- @currency($purchase->discount)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-timeline-outline me-2"></i>Timeline
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Pembelian Dibuat (PO)</h6>
                                    <p class="text-muted small mb-0">
                                        {{ \Carbon\Carbon::parse($purchase->created_at)->translatedFormat('d F Y H:i') }}
                                    </p>
                                    <p class="small mb-0">Oleh: {{ $purchase->user_name ?? 'System' }}</p>
                                </div>
                            </div>

                            @if ($purchase->created_at != $purchase->updated_at && $purchase->status == 'draft')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">PO Terakhir Diupdate</h6>
                                        <p class="text-muted small mb-0">
                                            {{ \Carbon\Carbon::parse($purchase->updated_at)->translatedFormat('d F Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if ($purchase->status == 'completed')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Barang Diterima (GR)</h6>
                                        <p class="text-muted small mb-0">
                                            {{ \Carbon\Carbon::parse($purchase->updated_at)->translatedFormat('d F Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if ($purchase->status == 'cancelled')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-danger"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Pembelian Dibatalkan</h6>
                                        <p class="text-muted small mb-0">
                                            {{ \Carbon\Carbon::parse($purchase->updated_at)->translatedFormat('d F Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Card -->
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="card-title mb-0 text-gray-900 font-weight-bold">
                            <i class="mdi mdi-cog-outline me-2"></i>Aksi
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- PERBAIKAN: Tombol aksi dinamis berdasarkan status -->
                        <div class="d-grid gap-2">

                            @if ($purchase->status == 'draft')
                                <!-- Aksi untuk 'Draft' -->
                                <a href="{{ route('purchases.receive-form', $purchase->id) }}" class="btn btn-success">
                                    <i class="mdi mdi-truck-check-outline me-2"></i>Terima Barang
                                </a>
                                <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-warning">
                                    <i class="mdi mdi-pencil-outline me-2"></i>Edit Pembelian
                                </a>
                                <button type="button" class="btn btn-outline-danger"
                                    onclick="confirmCancel({{ $purchase->id }}, '{{ $purchase->purchase_number }}')">
                                    <i class="mdi mdi-close-circle-outline me-2"></i>Batalkan Pembelian
                                </button>
                            @endif

                            @if ($purchase->status == 'cancelled')
                                <!-- Aksi untuk 'Cancelled' -->
                                <button type="button" class="btn btn-danger"
                                    onclick="confirmDestroy({{ $purchase->id }}, '{{ $purchase->purchase_number }}')">
                                    <i class="mdi mdi-trash-can-outline me-2"></i>Hapus Permanen
                                </button>
                            @endif

                            @if ($purchase->status == 'completed')
                                <p class="text-muted text-center"><i class="mdi mdi-check-circle me-1"></i>Pembelian sudah
                                    selesai diproses.</p>
                            @endif

                            <!-- Aksi yang selalu ada -->
                            <button type="button" class="btn btn-outline-primary" onclick="printPurchase()">
                                <i class="mdi mdi-printer-outline me-2"></i>Print Pembelian
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PERBAIKAN: Form tersembunyi untuk Aksi dinamis -->
    @if ($purchase->status == 'draft')
        <form action="{{ route('purchases.cancel', $purchase->id) }}" method="POST"
            id="cancel-form-{{ $purchase->id }}" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endif
    @if ($purchase->status == 'cancelled')
        <form action="{{ route('purchases.delete', $purchase->id) }}" method="POST"
            id="destroy-form-{{ $purchase->id }}" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endif

    <!-- HAPUS Form 'deleteForm' yang lama -->
@endsection

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 20px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -20px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            /* background: #6c757d; */
            /* Dihapus agar warna dinamis */
        }

        .timeline-content {
            padding-left: 10px;
        }

        /* Warna marker dinamis */
        .timeline-marker.bg-success {
            background-color: #198754;
        }

        .timeline-marker.bg-info {
            background-color: #0dcaf0;
        }

        .timeline-marker.bg-danger {
            background-color: #dc3545;
        }
    </style>
@endpush

@push('scripts')
    <script>
        /**
         * PERBAIKAN: Script SweetAlert disesuaikan dengan aksi baru
         */

        // (Fungsi printPurchase() Anda bisa ditambahkan di sini jika ada)
        function printPurchase() {
            alert('Fungsi print belum dibuat.');
            // window.print(); // atau logika print custom
        }

        /**
         * Konfirmasi untuk membatalkan (mengubah status ke 'cancelled')
         */
        function confirmCancel(id, purchaseNumber) {
            Swal.fire({
                title: `Batalkan Pembelian "${purchaseNumber}"?`,
                text: "PO akan diubah statusnya menjadi 'Cancelled'.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tutup'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancel-form-' + id).submit();
                }
            });
        }

        /**
         * Konfirmasi untuk menghapus permanen (hard delete)
         */
        function confirmDestroy(id, purchaseNumber) {
            Swal.fire({
                title: `HAPUS PERMANEN "${purchaseNumber}"?`,
                text: "Data pembelian yang 'Cancelled' ini akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus Permanen!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('destroy-form-'
                        t + id).submit();
                }
            });
        }

        // Script notifikasi 'success' Anda
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
@endpush
