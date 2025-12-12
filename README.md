# Aplikasi Toko Lengkap — POS & Ecommerce

Laravel | Tailwind | Breeze | Spatie Role | Yajra Datatable | Midtrans/Xendit

Aplikasi toko lengkap dengan modul Point of Sale (POS) dan Ecommerce Landing Page.
Mendukung multi cabang, manajemen stok lengkap (FIFO/FEFO), batch & expiry, kasir, laporan, dan integrasi payment gateway.

---

## Tech Stack

-   PHP 8.2.12
-   Laravel
-   TailwindCSS
-   Laravel Breeze
-   Spatie Permission
-   Yajra Datatable
-   NPM 10.9.2
-   Payment Gateway: Midtrans / Xendit

---

# Project Roadmap / To-Do List

Roadmap ini digunakan untuk mencatat progress selama pengembangan modul aplikasi.

---

## 1. Purchase Order (PO) Module — Fix & Refactor

Refactor Pembelian (PO → GR)

-   [ ] Ubah konsep pembelian menjadi 2 tahap: Purchase Order (PO) dan Good Receipt (GR)
-   [ ] Modify tabel `purchase_orders`
-   [ ] Buat tabel baru: `purchase_order_items`, `good_receipts`, `good_receipt_items`
-   [ ] Perbaikan di PurchasesRepository dan alur pembelian
-   [ ] Penyesuaian harga COGS berdasarkan FIFO/FEFO
-   [ ] Sinkronisasi numbering, approval, dan status PO/GR

---

## 2. Good Receipt (Receive) Module

-   [ ] Perbaikan bug receive barang
-   [ ] Validasi kuantitas (PO qty vs GR qty)
-   [ ] FEFO (expiry-based): pemilihan batch otomatis
-   [ ] Update stok saat GR selesai
-   [ ] Tambah alert jika GR melebihi PO
-   [ ] Tampilkan history batch dan expiry

---

## 3. Alert Stock Menipis

-   [ ] Tambah kolom minimal stock pada item
-   [ ] Menampilkan alert pada dashboard admin
-   [ ] Notifikasi UI saat stok menipis
-   [ ] Penandaan barang di inventory
-   [ ] Integrasi alert pada penjualan dan stock movement

---

## 4. Stock Movement Module

### 4.1 Transfer Antar Cabang

-   [ ] Form request transfer
-   [ ] Approval transfer
-   [ ] Stock out cabang A → Stock in cabang B
-   [ ] Riwayat transfer

### 4.2 Stock Adjustment

-   [ ] Penyesuaian stok (selisih, rusak, kadaluwarsa)
-   [ ] Log user dan alasan adjustment

### 4.3 Stock Opname

-   [ ] Input stok fisik
-   [ ] Hitung selisih otomatis
-   [ ] Generate adjustment otomatis

---

## 5. Penjualan (Kasir)

### Tampilan Kasir

-   [ ] UI kasir cepat
-   [ ] Scan barcode
-   [ ] Pemilihan batch otomatis (FIFO/FEFO)
-   [ ] Harga mengikuti batch restok
-   [ ] Metode pembayaran: cash, QRIS/e-wallet

### History Penjualan

-   [ ] History per kasir per hari
-   [ ] Rekap shift kasir

---

## 6. Karyawan & Shift

-   [ ] CRUD karyawan
-   [ ] Penempatan karyawan per cabang
-   [ ] Sistem shift kasir
-   [ ] Log aktivitas kasir

---

## 7. Laporan & Dashboard

-   [ ] Laporan penjualan
-   [ ] Laporan pembelian
-   [ ] Laporan stok masuk/keluar
-   [ ] Laporan expiry dan batch
-   [ ] Dashboard grafik dan summary
-   [ ] Laporan profit (COGS per batch)

---

## 8. Ecommerce Landing Page

### Frontend

-   [ ] Landing page produk
-   [ ] Detail produk
-   [ ] Keranjang (cart)
-   [ ] Checkout

### Checkout Engine

-   [ ] Harga berdasarkan batch termurah (FIFO)
-   [ ] Pilihan cabang untuk pick-up (opsional)
-   [ ] Validasi stok realtime

---

## 9. Payment Gateway

-   [ ] Integrasi Midtrans atau Xendit
-   [ ] Implementasi callback untuk update status pembayaran
-   [ ] Generate invoice otomatis
-   [ ] Riwayat order customer

---

## 10. Finalization & QA

-   [ ] Review role & permission (Spatie)
-   [ ] Pengujian end-to-end: PO → GR → Movement → Penjualan → Laporan
-   [ ] Deployment
-   [ ] Perbaikan bug dan polishing akhir

---

## Urutan Pengerjaan (Saran Mas Hakim)

1. Purchase Order → Good Receipt
2. Stock Movement
3. Penjualan (Kasir)
4. Laporan & Dashboard
5. Karyawan & Shift
6. Landing Page → Checkout → Payment Gateway
