# 🏪 Toko Rizky — POS & Multi-Branch Inventory Management System

**Status:** 🚧 Active Development  
**Framework:** Laravel 12 | **Frontend:** TailwindCSS + Alpine.js | **Database:** MySQL  
**Permission:** Spatie Laravel Permission | **Data Tables:** Yajra Datatables

---

## 📖 Deskripsi Proyek

**Toko Rizky** adalah aplikasi enterprise-grade untuk manajemen toko multi-cabang yang mengintegrasikan:

-   **Point of Sale (POS)** — Sistem kasir lengkap dengan tracking kasir per shift
-   **Inventory Management** — Manajemen stok dengan batch tracking, expiry date monitoring, dan FIFO/FEFO support
-   **Purchase Order & Good Receipt** — Proses pembelian terpisah (PO untuk order, GR untuk penerimaan)
-   **Stock Movement** — Transfer antar cabang, adjustment stok, dan opname (planned)
-   **Ecommerce Landing Page** — Mini toko online dengan integrasi payment gateway (planned)
-   **Reporting & Dashboard** — Laporan komprehensif untuk penjualan, pembelian, stok, dan profit

**Target User:**

-   Pemilik toko (Owner)
-   Admin toko / manajer cabang (Admin Cabang)
-   Kasir (Cashier)
-   Staff inventory (Admin Staff)
-   Super Admin (manajemen sistem)

---

## 🎯 Scope & Konsep Sistem Utama

### 1. **Multi-Branch Architecture**

Sistem mendukung multiple cabang dengan lokasi penyimpanan (gudang) yang berbeda per cabang:

-   Setiap cabang memiliki location (lokasi penyimpanan)
-   Inventory tracking per location
-   Pemisahan data operasional per cabang

### 2. **Batch & Expiry Tracking**

Setiap produk dapat diterima dalam multiple batch dengan:

-   **Batch Number** — Identifikasi unik batch
-   **Expiry Date** — Tanggal kadaluarsa
-   **Purchase Price** — Harga beli per unit (besar & kecil)
-   **Selling Price** — Harga jual per unit (bisa berbeda per batch)
-   **Quantity per Location** — Tracking stok per lokasi

### 3. **Unit Conversion (Large ↔ Small)**

Produk memiliki 2 unit satuan:

-   **Unit Besar** (e.g., dus, box, karton)
-   **Unit Kecil** (e.g., pcs, buah, butir)
-   **Conversion Factor** — Jumlah unit kecil = 1 unit besar

Contoh: Mie Instant

-   Unit Besar: Dus (isi 40 pcs)
-   Unit Kecil: Pcs
-   Conversion: 40 pcs per dus

### 4. **Proses Pembelian: PO → GR (Separated Flow)**

**Phase 1: Purchase Order (PO)**

-   Pemesanan barang ke supplier
-   Penetapan harga beli & harga jual
-   Status PO: `draft` → `completed` (setelah semua GR selesai)
-   Tracking: `total_quantity_received` (akumulasi dari semua GR)

**Phase 2: Good Receipt (GR)**

-   Penerimaan barang fisik dari supplier
-   Validasi kuantitas vs PO
-   Input batch number & expiry date
-   Penolakan barang (qty_rejected)
-   Status GR: `draft` → `process` → `completed` / `cancelled`

**Key Logic:**

-   1 PO bisa memiliki multiple GR
-   GR baru membuat batch & mengupdate batch_location (stok fisik)
-   PO bisa "partial" jika belum semua diterima (bisa pending GR lain)

### 5. **Inventory Accuracy**

-   **Batch Locations** — Tracking stok per batch per location
-   **Stock Movement** — History lengkap setiap pergerakan stok
-   **Reconciliation** — Fitur opname untuk validasi stok fisik vs sistem

---

## 🚀 Fitur Utama

### ✅ Core Features (Sudah Stabil / WIP)

#### A. **Master Data Management**

-   [x] Categories (Kategori Produk)
-   [x] Products (Produk dengan unit conversion)
-   [x] Unit Larges & Unit Smalls (Satuan)
-   [x] Suppliers (Pemasok)
-   [x] Branches (Cabang)
-   [x] Locations (Gudang/Lokasi per cabang)

#### B. **Purchase Management**

-   [x] Purchase Order (PO) — Create, Read, Update, Delete
-   [x] Purchase Items — Detail PO per produk
-   [x] Status Tracking (draft, partial, completed, cancelled)
-   [x] Purchase Number Auto-Generation
-   [x] Automatic totaling (items, qty, amount)
-   [x] Get Purchase Items JSON API — Untuk form GR

#### C. **Good Receipt (Penerimaan)**

-   [x] Good Receipt Form — Create & Edit
-   [x] Good Receipt Items — Input qty received & rejected per batch
-   [x] Batch Number & Expiry Date Input
-   [x] GR Number Auto-Generation
-   [x] Status Workflow: `draft` → `process` → `completed` / `cancelled`
-   [x] Button Actions (Process, Complete, Cancel)
-   [x] Datatable Index dengan filtering per branch

#### D. **Role & Permission Management**

-   [x] Roles CRUD (Admin, Owner, Cashier, Inventory Staff)
-   [x] Permissions CRUD (granular per module)
-   [x] Role-Permission Assignment
-   [x] User Management dengan Role Assignment

#### E. **Dashboard**

-   [x] Basic Dashboard (under construction)
-   [x] Recent Transactions Display

### 🔄 Features On Progress (WIP / Partial)

#### A. **Stock Management**

-   [ ] **Stock Movement Module**
    -   [ ] Transfer Antar Cabang (request → approval → transfer)
    -   [ ] Stock Adjustment (penyesuaian karena rusak, selisih, kadaluarsa)
    -   [ ] Stock Opname (input stok fisik & auto-generate adjustment)
    -   [ ] Stock Movement History & Logging

#### B. **FIFO/FEFO Implementation**

-   [ ] Automatic batch selection saat penjualan (FIFO/FEFO)
-   [ ] COGS calculation per batch
-   [ ] Profit reporting per batch

#### C. **Kasir (POS)**

-   [ ] UI Kasir Fast (scan barcode, add item)
-   [ ] Batch selection otomatis (FIFO/FEFO based on cheapest)
-   [ ] Payment method (Cash, QRIS, e-wallet)
-   [ ] Kasir shift management
-   [ ] Sales history per kasir per hari

#### D. **Low Stock Alert**

-   [ ] Minimum stock alert pada dashboard
-   [ ] Notification ketika stok menipis
-   [ ] Visual indicator di inventory list

### 📋 Planned Features (Future)

#### A. **Laporan & Analytics**

-   [ ] Sales Report (daily, monthly, yearly)
-   [ ] Purchase Report
-   [ ] Stock In/Out Report
-   [ ] Expiry & Batch Report
-   [ ] Profit Report (dengan COGS per batch)
-   [ ] Dashboard Charts & Visualizations

#### B. **Ecommerce Landing Page**

-   [ ] Product catalog (customer-facing)
-   [ ] Product detail page
-   [ ] Shopping cart
-   [ ] Checkout process

#### C. **Payment Gateway Integration**

-   [ ] Midtrans atau Xendit integration
-   [ ] Payment callback handling
-   [ ] Invoice generation
-   [ ] Order history (customer)

#### D. **Employee & Shift Management**

-   [ ] Employee CRUD per cabang
-   [ ] Shift scheduling
-   [ ] Cashier activity logging

---

## 🧱 Tech Stack

### **Backend**

| Teknologi | Versi  | Kegunaan        |
| --------- | ------ | --------------- |
| PHP       | 8.2.12 | Runtime         |
| Laravel   | 12.x   | Framework       |
| MySQL     | 5.7+   | Database        |
| Composer  | Latest | Package Manager |

### **Frontend**

| Teknologi   | Versi  | Kegunaan                |
| ----------- | ------ | ----------------------- |
| HTML/CSS    | Latest | Markup & Styling        |
| TailwindCSS | 3.x    | Utility CSS Framework   |
| Alpine.js   | 3.x    | Interactive Components  |
| Vite        | 7.x    | Build Tool & Dev Server |
| Axios       | 1.x    | HTTP Client             |
| LaravelVite | 2.x    | Vite Integration        |

### **Key Libraries & Packages**

| Package                         | Versi  | Kegunaan                     |
| ------------------------------- | ------ | ---------------------------- |
| spatie/laravel-permission       | 6.21   | Role & Permission Management |
| yajra/laravel-datatables-oracle | 12.0   | Data Tables (Server-side)    |
| laravel/breeze                  | 2.3    | Authentication scaffolding   |
| fakerphp/faker                  | 1.23   | Database seeding             |
| phpunit                         | 11.5.3 | Unit testing                 |

### **Integrasi Eksternal (Planned)**

-   **Midtrans** — Payment Gateway
-   **Xendit** — Alternative Payment Gateway (optional)

---

## 🔐 Role & Permission

### **User Roles**

#### 1. **Super Admin**

Akses penuh ke semua modul sistem untuk administration & configuration.

| Hak Akses             | Deskripsi                                         |
| --------------------- | ------------------------------------------------- |
| User Management       | Create, Read, Update, Delete users & roles        |
| Permission Management | Manage permissions & assign to roles              |
| Role Management       | Create, Read, Update, Delete roles                |
| Master Data           | Categories, Units, Suppliers, Branches, Locations |
| All Transactions      | Purchase, Good Receipt, Sales, Stock Movement     |
| Reports               | All reports                                       |

#### 2. **Owner**

Pemilik bisnis — akses ke dashboard, laporan, dan analytics (permissions TBD).

| Hak Akses | Deskripsi                                |
| --------- | ---------------------------------------- |
| Dashboard | View sales & inventory overview          |
| Reports   | View all financial & operational reports |
| Analytics | View charts & insights                   |

#### 3. **Admin Cabang (Branch Admin)**

Pengelola cabang — manajemen inventory & penjualan di cabang mereka (planned).

| Hak Akses     | Deskripsi                            |
| ------------- | ------------------------------------ |
| Inventory     | Create, Read, Update stock movements |
| Purchases     | View & manage purchase orders        |
| Good Receipts | Input & manage goods received        |
| Sales         | View sales data                      |
| Reports       | Branch-specific reports              |

#### 4. **Kasir (Cashier)**

Operator kasir — transaksi penjualan saja (planned).

| Hak Akses     | Deskripsi                 |
| ------------- | ------------------------- |
| POS           | Create sales transactions |
| Payment       | Process payments          |
| Sales History | View own sales history    |

#### 5. **Admin Staff (Inventory Staff)**

Staff inventory — penerimaan & manajemen stok (planned).

| Hak Akses      | Deskripsi                       |
| -------------- | ------------------------------- |
| Good Receipts  | Create & manage incoming goods  |
| Stock Movement | Create & manage stock transfers |
| Stock Opname   | Conduct & record stock count    |

### **Current Permission List**

```
User Management:    [read, create, update, delete, reset_password]
Roles:              [read, create, update, delete]
Permissions:        [read, create, update, delete]
Categories:         [read, create, update, delete]
Unit Larges:        [read, create, update, delete]
Unit Smalls:        [read, create, update, delete]
Suppliers:          [read, create, update, delete]
Branches:           [read, create, update, delete]
Locations:          [read, create, update, delete]
Products:           [read, create, update, delete]
Purchases:          [read, create, update, delete]
Good Receipts:      [read, create, update, delete]
```

**Catatan:** Permission untuk Owner, Cashier, dan Inventory Staff masih dalam tahap planning.

---

## ⚙️ Cara Kerja Sistem (High-Level Flow)

### **1. Alur Pembelian (Purchase → Good Receipt)**

```
┌─────────────────────────────────────────────────────────────┐
│                    PEMBELIAN (PURCHASE FLOW)                │
└─────────────────────────────────────────────────────────────┘

1. CREATE PURCHASE ORDER (PO)
   ├─ Input: Branch, Location, Supplier, Items
   ├─ Tentukan: Harga beli & harga jual per item
   ├─ Status: draft
   └─ Output: Purchase Order Number (PO-2025-xxxxx)

2. PO STATUS
   ├─ draft: Belum dikirim
   ├─ partial: Sudah diterima sebagian (ada GR)
   └─ completed: Semua diterima

3. CREATE GOOD RECEIPT (GR) dari PO
   ├─ Link ke PO
   ├─ Input: Batch number, Expiry date per item
   ├─ Validasi: Qty received <= Qty ordered di PO
   ├─ Status: draft
   └─ Output: GR Number (GR-2025-xxxxx)

4. GR WORKFLOW
   ├─ draft: Belum diproses
   ├─ process: Sedang diproses (menginput batch)
   ├─ completed: Selesai → CREATE BATCH + update batch_location
   └─ cancelled: Dibatalkan

5. BATCH CREATION (saat GR completed)
   ├─ Create/Update batch (per product)
   ├─ Batch details: purchase price, selling price, quantity
   └─ Create batch_location: track stok per location
```

### **2. Alur Inventory & Stock Management**

```
┌──────────────────────────────────────────────────┐
│         INVENTORY & STOCK MANAGEMENT             │
└──────────────────────────────────────────────────┘

FLOW:
Batch Created (saat GR completed)
    ↓
Batch Locations Updated
    ↓
Real-time Stock Available
    ↓
Sales / Stock Movement
    ↓
Stock Updated per Location

FIFO/FEFO IMPLEMENTATION (planned):
- Saat penjualan: automatic select batch dengan:
  - FIFO: earliest expiry date first
  - FEFO: or by cost (cheapest first)
```

### **3. Alur Penjualan (POS - Planned)**

```
┌──────────────────────────────────────────────────┐
│         PENJUALAN (KASIR / POS FLOW)             │
└──────────────────────────────────────────────────┘

1. OPEN POS
   ├─ Input: Kasir, Branch, Location
   └─ Initialize transaction

2. ADD ITEMS
   ├─ Scan barcode / Select product
   ├─ Auto-select batch (FIFO/FEFO)
   ├─ Input quantity
   └─ Deduct from stock

3. PROCESS PAYMENT
   ├─ Cash
   ├─ QRIS/e-wallet
   └─ Online payment (planned)

4. COMPLETE TRANSACTION
   ├─ Generate receipt
   ├─ Create sales record
   ├─ Update stock
   └─ Kasir shift tracking
```

### **4. Alur Stock Movement (Planned)**

```
┌──────────────────────────────────────────────────┐
│      STOCK MOVEMENT (Transfer / Adjustment)      │
└──────────────────────────────────────────────────┘

TRANSFER ANTAR CABANG:
  Branch A Location 1 → Branch B Location 1
  ├─ Create transfer request
  ├─ Approve transfer
  ├─ Execute stock out A + stock in B
  └─ Document movement

STOCK ADJUSTMENT:
  Penyesuaian karena rusak, expired, atau selisih
  ├─ Input adjustment reason
  ├─ Update stock
  └─ Log dengan user & timestamp

STOCK OPNAME:
  Validasi stok fisik vs sistem
  ├─ Input stok fisik per location
  ├─ Bandingkan vs sistem
  ├─ Auto-generate adjustment untuk selisih
  └─ Complete opname
```

---

## 🗂 Struktur Folder (High-Level)

```
toko_rizky/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/           # Authentication (Breeze scaffold)
│   │   │   ├── Backend/        # Admin controllers
│   │   │   ├── DashboardController.php
│   │   │   └── ProfileController.php
│   │   └── Requests/           # Form validation requests
│   ├── Models/                 # Eloquent models
│   │   ├── User.php
│   │   ├── Products.php
│   │   ├── Batches.php
│   │   ├── Purchases.php
│   │   ├── GoodReceipt.php
│   │   ├── Branches.php
│   │   ├── Locations.php
│   │   └── ...others
│   ├── Repositories/           # Business logic & data access
│   │   ├── PurchasesRepository.php
│   │   ├── GoodReceiptsRepository.php
│   │   └── ...others
│   ├── Interfaces/             # Repository contracts
│   │   ├── PurchasesInterfaces.php
│   │   ├── GoodReceiptsInterfaces.php
│   │   └── ...others
│   └── Providers/
│       └── AppServiceProvider.php  # Service container bindings
│
├── database/
│   ├── migrations/             # Schema definitions
│   ├── seeders/                # Sample data
│   └── factories/              # Test data generators
│
├── resources/
│   ├── views/
│   │   ├── admin/              # Admin views per module
│   │   │   ├── products/
│   │   │   ├── purchases/
│   │   │   ├── good_receipts/
│   │   │   └── ...others
│   │   ├── layouts/
│   │   └── auth/               # Login & registration views
│   ├── css/                    # TailwindCSS
│   └── js/                     # JavaScript (Alpine.js)
│
├── routes/
│   ├── web.php                 # Web routes (main)
│   ├── auth.php                # Authentication routes
│   └── console.php             # Artisan commands
│
├── config/
│   ├── app.php                 # App configuration
│   ├── database.php
│   ├── permission.php          # Spatie Permission config
│   └── ...others
│
├── storage/                    # Logs, uploads, cache
├── tests/                      # Unit & feature tests
├── vendor/                     # Composer dependencies
│
└── PUBLIC FILES
    ├── .env.example            # Environment template
    ├── composer.json           # PHP dependencies
    ├── package.json            # Node.js dependencies
    ├── vite.config.js          # Vite configuration
    ├── tailwind.config.js      # TailwindCSS configuration
    ├── phpunit.xml             # PHPUnit configuration
    └── artisan                 # Laravel CLI
```

### **Arsitektur: Repository Pattern**

Proyek menggunakan Repository Pattern untuk separation of concerns:

```
Controller
    ↓
Repository Interface
    ↓
Repository Implementation
    ↓
Eloquent Model
    ↓
Database
```

**Keuntungan:**

-   Easy testing (mock repositories)
-   Loosely coupled
-   Business logic terpisah dari HTTP logic

---

## 🛠 Cara Clone & Menjalankan Project

### **Prasyarat**

-   PHP 8.2.12+
-   Composer
-   Node.js 10.9.2+ & npm
-   MySQL 5.7+
-   Git

### **Step-by-Step Setup**

#### **1. Clone Repository**

```bash
git clone https://github.com/yourusername/toko_rizky.git
cd toko_rizky
```

#### **2. Install Dependencies**

**PHP Dependencies:**

```bash
composer install
```

**Node.js Dependencies:**

```bash
npm install
```

#### **3. Setup Environment File**

```bash
cp .env.example .env
```

Edit `.env` dan sesuaikan:

```env
APP_NAME="Toko Rizky"
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=toko_rizky
DB_USERNAME=root
DB_PASSWORD=
```

#### **4. Generate Application Key**

```bash
php artisan key:generate
```

#### **5. Database Setup**

**Buat Database (MySQL):**

```bash
mysql -u root
CREATE DATABASE toko_rizky CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

**Run Migrations:**

```bash
php artisan migrate
```

**Run Seeders (untuk role, permission, & sample data):**

```bash
php artisan db:seed
```

#### **6. Build Assets**

```bash
npm run build
```

#### **7. Clear Cache (Recommended)**

```bash
php artisan optimize:clear
```

#### **8. Run Development Server**

**Option A: Built-in Server + Vite Dev Server**

```bash
php artisan serve
# On another terminal:
npm run dev
```

**Option B: Concurrently (all in one)**

```bash
composer run dev
```

### **Access Application**

-   **Web:** http://localhost:8000
-   **Default Login:**
    -   Email: `admin@example.com`
    -   Password: `password`

---

## 📌 Konfigurasi Penting

### **Environment Variables** (`.env`)

```env
# App
APP_NAME=Toko Rizky
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Jakarta

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=toko_rizky
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_DRIVER=database

# Cache & Queue (optional)
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Mail (optional)
MAIL_DRIVER=log
```

### **Database Configuration**

-   Driver: MySQL 5.7+
-   Charset: utf8mb4
-   Collation: utf8mb4_unicode_ci
-   Foreign keys: enabled

### **Authentication**

-   Driver: Laravel Breeze
-   Guard: web
-   Model: App\Models\User

### **Authorization**

-   Package: Spatie Laravel Permission
-   Table: roles, permissions, model_has_roles, role_has_permissions
-   Seeded: Default roles (Admin, Owner, Cashier, Inventory Staff)

### **Storage** (untuk upload file - optional)

```env
FILESYSTEM_DISK=public
```

Gunakan untuk:

-   Product images
-   Receipt/invoice PDFs
-   Reports

---

## 🗺 Roadmap & TODO

### **Fase 1: Purchase & Good Receipt (WIP — ~70% Complete)**

**Concept:** Memisahkan proses pembelian menjadi 2 tahap untuk kontrol kualitas yang lebih baik.

-   Purchase Order (PO) → hanya untuk order & harga
-   Good Receipt (GR) → untuk penerimaan fisik & batch tracking

#### Completed Tasks ✅

-   [x] Tabel `purchases` (PO)
-   [x] Tabel `good_receipts`, `good_receipt_items`
-   [x] Model `GoodReceipt`, `GoodReceiptItem`
-   [x] Repository & Interface untuk GoodReceipt
-   [x] Controller untuk GoodReceipt
-   [x] Routes untuk GoodReceipt
-   [x] Views untuk GoodReceipt (index, create, edit, show)
-   [x] GR Number auto-generation
-   [x] Batch number & expiry date input
-   [x] Qty received & rejected tracking
-   [x] GR status workflow: `draft` → `process` → `completed` / `cancelled`
-   [x] Button actions (Process, Complete, Cancel) di GR index
-   [x] API endpoint: `GET /admin/purchases/items/{id}` untuk fetch PO items (JSON)

#### In Progress / WIP 🔄

-   [ ] Bug fixes untuk logika status PO & GR (19/12/25)
-   [ ] Perbaikan filter product di index (27/12/25)
-   [ ] Detail product yang lebih jelas (27/12/25)
-   [ ] Full validation qty received vs PO qty
-   [ ] Automatic PO status update based on GR completion
-   [ ] Sinkronisasi numbering & approval flow

#### Design Notes 📝

**12/12/25 & 13/12/25:**

-   Konsep diubah dari one-way menjadi 2 tahap untuk kontrol lebih baik
-   PO hanya untuk pemesanan, sudah set harga beli & jual
-   GR hanya untuk cek barang masuk & expiry
-   `qty_received` di PO = akumulasi dari semua GR
-   Format data mentah dari BE, olah di FE

**18/12/25:**

-   Delete expiry di PO (tidak perlu di PO, hanya di GR)
-   Add index & form untuk GR

**04/01/26:**

-   Pecah GR process: `draft` → `process` → `completed` / `cancelled`

**06/10/26 & 11/01/26:**

-   Fix button actions di GR index
-   Add create form untuk GR
-   Add JSON API untuk fetch purchase items
-   Testing & validation untuk purchases & GR forms

---

### **Fase 2: Stock Management (0% - Not Started)**

Manajemen pergerakan stok antar cabang, adjustment, dan opname.

#### Sub-Modul 2.1: Transfer Antar Cabang

-   [ ] Form request transfer (Branch A Location → Branch B Location)
-   [ ] Approval workflow untuk transfer
-   [ ] Execute: stock out A + stock in B
-   [ ] History & logging setiap transfer

#### Sub-Modul 2.2: Stock Adjustment

-   [ ] Adjustment form untuk selisih, rusak, expired
-   [ ] Log user & reason adjustment
-   [ ] Update batch_location quantity
-   [ ] Audit trail

#### Sub-Modul 2.3: Stock Opname

-   [ ] Input stok fisik per location per product
-   [ ] Calculate selisih otomatis (sistem vs fisik)
-   [ ] Auto-generate adjustment untuk selisih
-   [ ] Complete opname dengan validation

#### Dependencies

-   Requires stable Fase 1 (PO & GR)
-   Need batch_location table stable

---

### **Fase 3: POS & Kasir (0% - Not Started)**

Sistem kasir untuk penjualan retail dengan batch selection otomatis.

#### Kasir Features

-   [ ] UI kasir fast-entry (minimal tapi cepat)
-   [ ] Scan barcode → auto-add item
-   [ ] Batch selection otomatis (FIFO/FEFO)
-   [ ] Price per batch (dari batch.selling_price)
-   [ ] Hitung subtotal, tax, discount otomatis

#### Payment

-   [ ] Payment method: Cash
-   [ ] Payment method: QRIS/e-wallet
-   [ ] Online payment gateway (Phase 5)

#### Shift & History

-   [ ] Kasir shift management
-   [ ] Sales history per kasir per hari
-   [ ] Shift opening/closing
-   [ ] Shift totaling & reconciliation

#### Dependencies

-   Requires stable Phase 2 (Stock Movement)
-   Need batch selection logic (FIFO/FEFO)

---

### **Fase 4: Reports & Analytics (0% - Not Started)**

Laporan komprehensif & dashboard dengan visualisasi.

#### Reports

-   [ ] Sales Report (daily, monthly, yearly)
-   [ ] Purchase Report
-   [ ] Stock In/Out Report
-   [ ] Expiry & Batch Report (warning untuk expired items)
-   [ ] Profit Report (dengan COGS per batch)

#### Dashboard

-   [ ] Summary: Total sales, purchases, stock value
-   [ ] Charts: Sales trend, stock movement, top products
-   [ ] Low stock alerts
-   [ ] Expiry alerts

#### Dependencies

-   Requires stable Phase 1 (PO & GR)
-   Requires stable Phase 3 (Sales data)

---

### **Fase 5: Ecommerce & Payment Gateway (0% - Not Started)**

Mini toko online untuk customer dengan payment integration.

#### Frontend (Ecommerce)

-   [ ] Landing page / product catalog (customer-facing)
-   [ ] Product detail page (with batch price, stock availability)
-   [ ] Shopping cart
-   [ ] Checkout process

#### Checkout Engine

-   [ ] Price dari batch termurah (FIFO)
-   [ ] Branch/location selection untuk pickup
-   [ ] Real-time stock validation
-   [ ] Order confirmation

#### Payment Gateway

-   [ ] Midtrans integration (primary)
-   [ ] Xendit integration (alternative)
-   [ ] Payment callback handling → update order status
-   [ ] Invoice generation

#### Customer Features

-   [ ] Order history
-   [ ] Order tracking
-   [ ] Receipt/invoice download

#### Dependencies

-   Requires stable Phase 3 (Inventory & FIFO logic)
-   Requires stable Phase 4 (Reports for visibility)

---

### **Fase 6: Employee & Advanced Features (0% - Not Started)**

Employee management, advanced role configuration, dan fitur-fitur tambahan.

#### Employee Management

-   [ ] Employee CRUD per branch
-   [ ] Employee assignment ke branch/location
-   [ ] Role assignment per employee

#### Shift System

-   [ ] Shift scheduling
-   [ ] Shift assignment ke kasir
-   [ ] Shift tracking & logging

#### Advanced Features

-   [ ] Kasir activity logging (detailed)
-   [ ] Advanced permissions per branch/location
-   [ ] Multi-currency support (optional)
-   [ ] Loyalty program (future)

#### Dependencies

-   Requires stable Phase 3 (Kasir & sales)
-   Requires stable Phase 1-4 (all core modules)

---

### **Development Order (Recommended)**

Urutan yang disarankan untuk optimal flow:

1. **Fase 1** — Purchase Order → Good Receipt ✅ (mostly done)
2. **Fase 2** — Stock Management (transfer, adjustment, opname)
3. **Fase 3** — POS & Kasir (sales transactions)
4. **Fase 4** — Reports & Dashboard (visibility)
5. **Fase 5** — Ecommerce & Payment (online channel)
6. **Fase 6** — Employee & Advanced (team management)

---

## ⚠️ Known Issues & Technical Debt

### **Current Issues (Based on Development Notes)**

#### 1. **Good Receipt & Purchase Status Sync** (🔴 Priority: HIGH)

**Date:** 19/12/25

**Issue:** Logika status antara PO & GR belum fully synchronized

**Details:**

-   PO status seharusnya otomatis berubah dari `draft` → `partial` → `completed`
-   GR completion harus trigger PO status update
-   Potensi bug jika GR di-cancel setelah partially completed (PO qty_received tidak rollback)

**Affected Code:**

-   `app/Repositories/PurchasesRepository.php`
-   `app/Repositories/GoodReceiptsRepository.php`
-   `app/Http/Controllers/Backend/GoodReceiptsController.php`

**Fix Strategy:**

-   Implement proper state machine pattern
-   Use database transactions untuk consistency
-   Add middleware untuk validate state transitions

---

#### 2. **Product Filter & Detail** (🔴 Priority: MEDIUM)

**Date:** 27/12/25

**Issue:** Filter di product index tidak menggunakan best practice, detail product belum lengkap

**Details:**

-   Filter logic tidak clean, perlu refactor
-   Product detail page belum menampilkan semua informasi relevan
-   Missing batch history, stock per location, dll

**Affected Code:**

-   `app/Http/Controllers/Backend/ProductsController.php`
-   `resources/views/admin/products/index.blade.php`
-   `resources/views/admin/products/show.blade.php`

**Fix Strategy:**

-   Gunakan standard filter pattern (filter repository method)
-   Enhance product show page dengan batch info
-   Add stock per location table

---

#### 3. **Good Receipt Form & Purchases API** (🟡 Priority: MEDIUM)

**Date:** 06/10/25 & 11/01/26

**Issue:** GR create form & purchases items API masih perlu testing & fixes

**Details:**

-   Function untuk return JSON purchase items ada tapi belum fully tested
-   GR form mungkin ada bug saat fetch PO items
-   Perlu double-check purchases form juga

**Affected Code:**

-   `app/Http/Controllers/Backend/PurchasesController.php` → `getItemsByPurchaseId()`
-   `app/Http/Controllers/Backend/GoodReceiptsController.php`
-   `resources/views/admin/good_receipts/create.blade.php`

**Fix Strategy:**

-   Add comprehensive testing
-   Validate error handling
-   Add loading states & error messages di FE

---

#### 4. **FIFO/FEFO Not Implemented** (🟡 Priority: HIGH)

**Status:** Planned for Phase 3

**Issue:** Batch selection masih manual, COGS calculation belum otomatis

**Details:**

-   Saat penjualan, tidak ada automatic batch selection berdasarkan expiry/cost
-   Selling price per batch belum digunakan secara optimal
-   COGS calculation masih belum ada

**Impact:**

-   Tidak bisa optimize profit & minimize expired items
-   Manual process rawan human error

**Fix Timeline:**

-   Will implement di Fase 3 (POS module)
-   Requires Phase 2 (Stock Movement) stable dulu

---

#### 5. **Expiry Date Removed from PO** (✅ FIXED)

**Date:** 18/12/25

**Status:** ✅ Resolved - Expiry date sudah dihapus dari PO table, hanya ada di GR & batch

---

### **Technical Debt**

1. **Soft Deletes** — Belum implemented, consider untuk audit trail
2. **API Structure** — Belum ada RESTful JSON API (hanya form-based), planned untuk phase 2
3. **Testing** — Unit & feature tests minimal/belum lengkap (recommend add TDD)
4. **Activity Logging** — Audit trail belum comprehensive, hanya basic timestamps
5. **Error Handling** — User feedback masih basic, need custom error pages
6. **Query Optimization** — Belum ada query indexing optimization, monitor untuk large data

### **Improvements for Next Iteration**

-   [ ] Implement transactions di semua critical operations
-   [ ] Add query logging untuk detect N+1 problems
-   [ ] Create DTOs untuk cleaner data transfer
-   [ ] Add comprehensive API documentation
-   [ ] Setup CI/CD pipeline untuk automated testing
-   [ ] Enhanced error handling & validation messages

---

## 🔧 Development Commands

### **Database**

```bash
# Fresh migration with seeds
php artisan migrate:fresh --seed

# Rollback & remigrate
php artisan migrate:refresh

# Only specific seeder
php artisan db:seed --class=PermissionSeeder
```

### **Cache & Optimization**

```bash
# Clear all cache
php artisan optimize:clear

# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache
```

### **Testing** (when available)

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test tests/Feature/PurchaseTest.php

# Run with coverage
php artisan test --coverage
```

### **Code Quality**

```bash
# Format code with Pint
./vendor/bin/pint

# Check code style
./vendor/bin/pint --test
```

### **Tinker (Interactive Shell)**

```bash
php artisan tinker
```

---

## 📚 API Endpoints (Current)

### **Authentication** (Laravel Breeze)

-   `POST /register` — Register user
-   `POST /login` — Login
-   `POST /logout` — Logout
-   `GET /dashboard` — Dashboard

### **Admin Routes** (Protected by `role:admin`)

#### **Purchase Management**

```
GET    /admin/purchases              # List purchases
GET    /admin/purchases/create       # Create form
POST   /admin/purchases/store        # Store
GET    /admin/purchases/show/{id}    # View detail
GET    /admin/purchases/edit/{id}    # Edit form
PUT    /admin/purchases/update/{id}  # Update
DELETE /admin/purchases/delete/{id}  # Delete
GET    /admin/purchases/items/{id}   # Get items (JSON)
```

#### **Good Receipt Management**

```
GET    /admin/good-receipts              # List GR
GET    /admin/good-receipts/create       # Create form
POST   /admin/good-receipts/store        # Store
GET    /admin/good-receipts/show/{id}    # View detail
GET    /admin/good-receipts/edit/{id}    # Edit form
PUT    /admin/good-receipts/update/{id}  # Update
DELETE /admin/good-receipts/delete/{id}  # Delete
PUT    /admin/good-receipts/process/{id} # Process (draft→processing)
PUT    /admin/good-receipts/complete/{id}# Complete (→completed)
PUT    /admin/good-receipts/cancel/{id}  # Cancel
```

#### **Master Data** (CRUD for all)

```
/admin/categories/         # Categories
/admin/unit-larges/        # Unit Larges
/admin/unit-smalls/        # Unit Smalls
/admin/suppliers/          # Suppliers
/admin/branches/           # Branches
/admin/locations/          # Locations
/admin/products/           # Products
/admin/user-management/    # Users
/admin/role/               # Roles
/admin/permission/         # Permissions
```

**Note:** API endpoints are form-based (HTML). RESTful JSON API planned for phase 2.

---

## 📖 Dokumentasi Tambahan

### **Database Schema Highlights**

**Key Tables:**

```
users                 — User accounts
roles, permissions    — Spatie permission tables
products              — Product master
batches               — Batch tracking per product
batch_locations       — Stock per batch per location
purchases             — Purchase orders
purchase_items        — Detail items per PO
good_receipts         — Good receipt (penerimaan)
good_receipt_items    — Detail items per GR
branches              — Cabang toko
locations             — Gudang per cabang
suppliers             — Supplier master
categories            — Product categories
unit_larges, unit_smalls — Unit satuan
```

### **Model Relationships**

```
User
  ├─ hasMany Purchases
  └─ hasMany GoodReceipt (received_by)

Product
  ├─ belongsTo Category
  ├─ hasMany Batches
  ├─ belongsTo UnitLarge
  └─ belongsTo UnitSmall

Batch
  ├─ belongsTo Product
  └─ hasMany BatchLocations

Purchase
  ├─ belongsTo Supplier
  ├─ belongsTo Branch
  ├─ belongsTo Location
  ├─ belongsTo User
  └─ hasMany PurchaseItems

GoodReceipt
  ├─ belongsTo Purchase
  ├─ belongsTo Supplier
  ├─ belongsTo Branch
  ├─ belongsTo Location
  ├─ belongsTo User (receiver)
  └─ hasMany GoodReceiptItems
```

---

## 💡 Tips & Best Practices

### **Development Workflow**

1. **Always use migrations** — Jangan modify schema manual
2. **Seed sample data** — Gunakan seeders untuk testing
3. **Use transactions** — Untuk operations yang involve multiple tables
4. **Validate input** — Use Form Requests untuk validation
5. **Log important events** — Untuk audit trail

### **Database Operations**

1. **Use repository pattern** — Tidak langsung query di controller
2. **Eager load relations** — Gunakan `with()` untuk prevent N+1
3. **Soft delete consideration** — Untuk retention/compliance

### **Code Organization**

1. **Keep controllers thin** — Logic di repository, bukan controller
2. **Name routes clearly** — Sesuai dengan standard Laravel naming
3. **Comment complex logic** — Terutama untuk inventory rules

---

## ❓ FAQ

**Q: Bagaimana cara menambah role baru?**  
A:

1. Buat role via admin panel atau seeder
2. Create permissions yang diperlukan
3. Assign permissions ke role
4. Update route middleware jika perlu

**Q: Bagaimana produk support multiple unit?**  
A: Setiap product memiliki `unit_large_id` dan `unit_small_id`. Conversion factor ada di `products.conversion`. Contoh: 40 pcs (kecil) = 1 dus (besar).

**Q: Bagaimana stok diupdate saat GR completed?**  
A: Saat GR di-complete, sistem create/update batch dan batch_locations. Stok real-time tersedia langsung di batch_locations.

**Q: Apakah support multiple warehouse per location?**  
A: Saat ini location adalah warehouse per cabang. Structure yang lebih granular (rak, bin level) bisa ditambahkan di future.

**Q: Kapan FIFO/FEFO implemented?**  
A: Phase 3 (setelah POS module). Saat ini batch selection masih manual.

---

## 📄 Lisensi

**Status:** Belum ditentukan

Proyek ini adalah proprietary software untuk Toko Rizky. Untuk informasi lisensi lebih lanjut, hubungi development team.

---

## 👥 Tim Development

-   **System Architect & Lead Developer:** [Nama]
-   **Frontend Developer:** [Nama]
-   **QA & Testing:** [Nama]

---

## 📞 Support & Contact

Untuk questions, bug reports, atau feature requests, silakan buat issue di repository ini atau hubungi tim development.

---

**Last Updated:** January 11, 2026  
**Status:** 🚧 Active Development  
**Current Phase:** Good Receipt Module (Phase 1)
