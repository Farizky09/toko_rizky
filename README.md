Aplikasi toko Lengkap

aplikasi ini memiliki 2 sistem yaitu manajemen toko lengkap dan ecommerce di landing page agar customer bisa melakukan pembelian atau cehckout barang secara langsung dan pembayaran terintegrasi dengan midtrans atau xendit

role:

1. admin (all Akses)
2. Owner (laporan dan manajemen toko)
3. kasir (transaksi penjualan)
4. inventory staff (pengelolaan stock atau barang )

techstack

1. php : PHP 8.2.12 (cli) (built: Oct 24 2023 21:15:15) (ZTS Visual C++ 2019 x64)
   Copyright (c) The PHP Group
   Zend Engine v4.2.12, Copyright (c) Zend Technologies
2. Framework = Laravel
3. css = Tailwind
4. role akses = Laravel Spatie
5. Auth = Laravel Breeze
6. Datatable = Yajra
7. npm = 10.9.2

Requirements

1. restok barang dan jual barang
2. pembelian (restok) dan penjualan barang bisa satuan kecil dan satuan besar
3. multi cabang
4. FIFO atau FEFO (fokus ke exp)
5. harga ditentukan berdasarkan saat restok

Task berikut nya:

1. Fix bug di Purchases order
2. fix bug at receive
3. add alert saat stock menipis
4.

Setelah purchase order selesai -> stock movement (transfer stock, adjustment, opname)-> kasier (penjualan)-> shift dan sesuai cabang (penempatan)->Laporan -> landing page ecommerce-> sistem checkout -> integrasi payment gateway ->dashboard

penjualan -> tampilan kasir
history penjualan -> kasir saat itu (per hari)

history penjualan pembelian-> di admin dan di owner

saran mas hakim:
setelah purchase order selesai -> stock movement -> penjualan kasir -> report dan dashboard
jika sudah bisa lanjut ke
karywan atau kasir (shift dan penempatan )
