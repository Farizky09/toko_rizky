# 📚 Technology Stack - Toko Rizky

Dokumentasi lengkap tentang semua teknologi, framework, dan library yang digunakan dalam project Toko Rizky.

---

## 🏗️ **BACKEND STACK**

### **Framework & Language**

| Technology         | Version | Fungsi                                 |
| ------------------ | ------- | -------------------------------------- |
| **PHP**            | ^8.2    | Server-side language                   |
| **Laravel**        | ^12.0   | Web framework untuk REST API & routing |
| **Laravel Tinker** | ^2.10.1 | REPL untuk debugging                   |

**Lokasi:** Semua file di `/app`, `/routes`, `/config`, `/bootstrap`

### **Database & ORM**

| Technology           | Purpose                                    |
| -------------------- | ------------------------------------------ |
| **Laravel Eloquent** | ORM (Object-Relational Mapping)            |
| **Migration**        | Version control untuk database schema      |
| **Seeder**           | Database initialization dengan data sample |

**Lokasi:** `/database/migrations`, `/database/seeders`

### **Authentication & Authorization**

| Library                       | Version | Fungsi                           |
| ----------------------------- | ------- | -------------------------------- |
| **Spatie Laravel Permission** | ^6.21   | Role-based access control (RBAC) |
| **Laravel Breeze**            | ^2.3    | Starter kit untuk authentication |

**Implementasi:**

```php
// Roles & Permissions
- Izin/Permission (create_user, read_user, update_user, delete_user)
- Peran/Role (Admin, Manager, Staff)
- Many-to-many relationships
```

**Lokasi:** `/app/Models/User.php`, `/app/Interfaces/PermissionInterfaces.php`

### **Data Table Library**

| Library                     | Version | Fungsi                      |
| --------------------------- | ------- | --------------------------- |
| **Yajra DataTables Oracle** | 12.0    | Server-side data processing |

**Fitur:**

- Server-side pagination
- Sorting & filtering
- CSV/Excel export
- Responsive tables

**Lokasi:** DataTables configuration di view files

---

## 🎨 **FRONTEND STACK**

### **CSS Framework - Hybrid Approach**

#### **1. Tailwind CSS** ⭐ (Primary)

```json
{
    "tailwindcss": "^3.1.0",
    "@tailwindcss/forms": "^0.5.2",
    "@tailwindcss/vite": "^4.0.0"
}
```

**Penggunaan:**

- Utility-first CSS framework
- All component styling
- Responsive design (mobile-first)
- Custom CSS variables untuk consistency

**Configuration:**

```javascript
// tailwind.config.js
{
  content: ['./resources/views/**/*.blade.php'],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },
  plugins: [forms],
}
```

**Color Palette (Tailwind):**

```css
/* Primary (Blue) */
from-blue-50 to blue-900

/* Secondary (Emerald)  */
from-emerald-50 to emerald-900

/* Status Colors */
bg-green-600, bg-red-600, bg-yellow-500

/* Neutral (Gray/Slate) */
from-gray-50 to slate-900
```

**Lokasi:** Digunakan di semua `.blade.php` files

---

#### **2. Bootstrap 5** (Supporting)

```
Bootstrap 5.3.0 (via CDN)
```

**Penggunaan:**

- Grid system (`row`, `col-sm-12`)
- DataTables integration
- Form validation states
- Responsive utilities

**Lokasi:** [resources/views/layouts/master.blade.php](resources/views/layouts/master.blade.php) line 25

**Kombinasi Tailwind + Bootstrap:**

```html
<!-- Bootstrap Grid untuk DataTables -->
<div class="row">
    <div class="col-sm-12 col-md-6">
        <!-- Buttons dengan Tailwind -->
        <button class="px-4 py-2.5 bg-blue-600...">Excel</button>
    </div>
</div>
```

---

### **CSS Processing**

#### **PostCSS**

```
postcss: ^8.4.31
autoprefixer: ^10.4.2
```

**Pipeline:**

```
.blade.php → Tailwind → PostCSS → Autoprefixer → CSS
```

**Configuration:** [postcss.config.js](postcss.config.js)

---

### **Build Tool - Vite**

```
vite: ^7.0.7
laravel-vite-plugin: ^2.0.0
```

**Fungsi:**

- Module bundling
- Hot Module Replacement (HMR)
- Asset optimization
- Development server

**Configuration:** [vite.config.js](vite.config.js)

**Entrypoints:**

```javascript
input: ["resources/css/app.css", "resources/js/app.js"];
```

---

## 🧩 **FRONTEND LIBRARIES & PLUGINS**

### **Template Engine**

| Library              | Fungsi                    |
| -------------------- | ------------------------- |
| **Blade**            | Laravel templating engine |
| **Blade Components** | Reusable UI components    |

**Custom Components:**

- `x-button` - Button component
- `x-badge` - Badge/status indicator
- `x-card` - Card container
- `x-page-header` - Page header dengan breadcrumb

**Lokasi:** `/resources/views/components/`

---

### **JavaScript Libraries**

#### **1. Alpine.js**

```
alpinejs: ^3.4.2
```

**Penggunaan:**

- Lightweight JavaScript framework
- Dropdown menus (x-data, @click)
- Modal interactions
- Form validation

**Contoh:**

```blade
<div x-data="{ open: false }">
  <button @click="open = !open">Toggle</button>
</div>
```

**Lokasi:** Sidebar dropdown menus, form interactions

---

#### **2. jQuery**

```
jQuery: ^3.7.0 (via CDN)
```

**Penggunaan:**

- DataTables initialization
- Form handling
- AJAX requests
- DOM manipulation

**Lokasi:** Scripts di halaman DataTables

---

#### **3. DataTables (Yajra)**

```
DataTables: 1.13.6 (via CDN)
- dataTables.bootstrap5.min.js
- dataTables.responsive.min.js
- dataTables.buttons.min.js
```

**Fitur:**

```javascript
// Server-side processing
serverSide: true
processing: true

// Export formats
buttons: ['Excel', 'PDF', 'Print']

// Custom rendering
render: function(data) { ... }

// Responsive
responsive: true
```

**Lokasi:** Role, Permission, User Management index pages

---

#### **4. Select2**

```
Select2: 4.1.0-rc.0 (via CDN)
select2-bootstrap-5-theme: 1.3.0
```

**Penggunaan:**

- Enhanced dropdown/select
- Search functionality
- Bootstrap 5 theme integration

**Lokasi:** Role selection di User Management create/edit

---

#### **5. SweetAlert2**

```
SweetAlert2: ^11 (via CDN)
```

**Penggunaan:**

- Modal dialogs
- Confirmations
- Alert notifications
- Success/error messages

**Contoh:**

```javascript
Swal.fire({
    title: "Konfirmasi Hapus",
    text: "Yakin ingin menghapus?",
    icon: "warning",
    confirmButtonColor: "#dc2626",
});
```

**Lokasi:** Delete confirmations, form validation

---

#### **6. Axios**

```
axios: ^1.11.0
```

**Fungsi:**

- HTTP client untuk API requests
- AJAX calls
- Error handling

---

#### **7. Leaflet**

```
Leaflet: 1.9.4 (via CDN)
@turf/turf: 6 (geographic analysis)
```

**Penggunaan:**

- Map rendering
- Geographic features
- Location management

**Lokasi:** Locations feature (jika diaktifkan)

---

### **Icon Library**

```
Material Design Icons (@mdi/font): Latest
CDN: https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css
```

**Penggunaan:**

```html
<span class="mdi mdi-pencil text-base"></span>
<span class="mdi mdi-trash-can"></span>
<span class="mdi mdi-check-circle"></span>
```

**Classes:**

- `mdi` - Base class
- `mdi-[icon-name]` - Icon name
- `text-base`, `text-lg`, `text-xl` - Size
- `text-[color]` - Color

---

### **Typography & Fonts**

#### **Font Families**

```
1. Inter (Google Fonts) - Default
   - Weights: 300, 400, 500, 600, 700, 800
   - Used for: All text content
   - CDN: https://fonts.googleapis.com/...

2. Figtree (Tailwind default)
   - Fallback font

3. JetBrains Mono
   - For code/monospace content
```

**Configuration:**

```javascript
// tailwind.config.js
fontFamily: {
  sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
}
```

---

## 🎯 **STYLING ARCHITECTURE**

### **CSS Methodology: Utility-First (Tailwind)**

**Approach:**

```html
<!-- ✅ RECOMMENDED -->
<button class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
    Click me
</button>

<!-- ❌ AVOID (Component classes) -->
<button class="btn btn-primary">Click me</button>
```

**Spacing Scale:**

```
0 → 0px
1 → 0.25rem (4px)
2 → 0.5rem (8px)
3 → 0.75rem (12px)
4 → 1rem (16px)
6 → 1.5rem (24px)
8 → 2rem (32px)
```

**Responsive Breakpoints:**

```
sm: 640px
md: 768px
lg: 1024px
xl: 1280px
2xl: 1536px
```

**Custom CSS Variables:**

```css
:root {
    --primary-600: #2563eb;
    --secondary-600: #059669;
    --dark-800: #1e293b;
}
```

---

### **Component System**

**Blade Components (Reusable):**

```
/resources/views/components/
├── button.blade.php          (Props: variant, size, icon, disabled)
├── badge.blade.php           (Props: variant, size, rounded)
├── card.blade.php            (Props: title, subtitle, padding)
├── page-header.blade.php     (Props: title, description, breadcrumbs)
└── sidebar.blade.php         (Navigation)
```

**Usage:**

```blade
<x-button variant="primary" icon="plus" size="md">
  Tambah Data
</x-button>

<x-card title="Informasi" subtitle="Detail data">
  <!-- Content -->
</x-card>
```

---

## 📦 **DEPENDENCY MANAGEMENT**

### **NPM Dependencies (Frontend)**

**Development:**

```json
{
    "@tailwindcss/forms": "^0.5.2",
    "@tailwindcss/vite": "^4.0.0",
    "alpinejs": "^3.4.2",
    "autoprefixer": "^10.4.2",
    "axios": "^1.11.0",
    "concurrently": "^9.0.1",
    "laravel-vite-plugin": "^2.0.0",
    "postcss": "^8.4.31",
    "tailwindcss": "^3.1.0",
    "vite": "^7.0.7"
}
```

**External (via CDN):**

- Bootstrap 5.3.0
- DataTables 1.13.6
- jQuery 3.7.0
- Select2 4.1.0
- SweetAlert2 11
- Leaflet 1.9.4
- Material Design Icons

---

### **Composer Dependencies (Backend)**

**Core:**

```json
{
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "spatie/laravel-permission": "^6.21",
    "yajra/laravel-datatables-oracle": "12.0"
}
```

**Development:**

```json
{
    "laravel/pint": "^1.24",
    "phpunit/phpunit": "^11.5.3",
    "mockery/mockery": "^1.6"
}
```

---

## ⚙️ **BUILD & DEVELOPMENT**

### **Scripts**

**Package.json:**

```bash
npm run dev      # Start Vite dev server
npm run build    # Production build
```

**Composer:**

```bash
composer run dev     # Run full dev environment
composer run test    # Run tests
composer run setup   # Initial setup
```

---

### **Development Environment**

**Tools:**

```
- Vite (HMR enabled)
- Laravel Pail (log monitoring)
- Laravel Breeze (scaffolding)
- Concurrently (multiple processes)
```

**Concurrent processes:**

```bash
php artisan serve        # Server (port 8000)
php artisan queue:listen # Queue worker
php artisan pail         # Log viewer
npm run dev              # Vite dev server
```

---

## 🎨 **DESIGN SYSTEM**

### **Color System**

**Primary (Blue):**

```
50 → #eff6ff (lightest)
100 → #dbeafe
200 → #bfdbfe
300 → #93c5fd
400 → #60a5fa
500 → #3b82f6
600 → #2563eb (primary)
700 → #1d4ed8
800 → #1e40af
900 → #1e3a8a (darkest)
```

**Secondary (Emerald):**

```
50 → #ecfdf5
...
600 → #059669 (secondary)
...
900 → #064e3b
```

**Status Colors:**

```
Success  → #10b981 (Emerald)
Warning  → #f59e0b (Amber)
Error    → #ef4444 (Red)
Info     → #3b82f6 (Blue)
```

---

### **Typography Scale**

```
text-xs   → 0.75rem (12px)
text-sm   → 0.875rem (14px)
text-base → 1rem (16px)
text-lg   → 1.125rem (18px)
text-xl   → 1.25rem (20px)
text-2xl  → 1.5rem (24px)
text-3xl  → 1.875rem (30px)
text-4xl  → 2.25rem (36px)
```

**Font Weights:**

```
font-light     → 300
font-normal    → 400
font-medium    → 500
font-semibold  → 600
font-bold      → 700
```

---

### **Border Radius**

```
rounded      → 0.375rem (6px)
rounded-md   → 0.5rem (8px)
rounded-lg   → 0.75rem (12px)
rounded-xl   → 1rem (16px)
rounded-full → 9999px
```

---

### **Shadows**

```
shadow-sm  → 0 1px 2px 0 rgb(0 0 0 / 0.05)
shadow-md  → 0 4px 6px -1px rgb(0 0 0 / 0.1)
shadow-lg  → 0 10px 15px -3px rgb(0 0 0 / 0.1)
shadow-xl  → 0 20px 25px -5px rgb(0 0 0 / 0.1)
```

---

## 📂 **PROJECT STRUCTURE**

### **Frontend Architecture**

```
resources/
├── css/
│   └── app.css              (Tailwind imports)
├── js/
│   └── app.js               (Alpine.js, Global scripts)
└── views/
    ├── components/          (Reusable Blade components)
    │   ├── button.blade.php
    │   ├── badge.blade.php
    │   ├── card.blade.php
    │   ├── page-header.blade.php
    │   └── sidebar.blade.php
    ├── layouts/
    │   └── master.blade.php (Main layout + styles)
    └── admin/
        ├── permission/      (Permission CRUD)
        ├── role/            (Role CRUD)
        └── user_management/ (User CRUD)
```

### **Backend Architecture**

```
app/
├── Models/              (Eloquent models)
├── Controllers/         (Route handlers)
├── Repositories/        (Data abstraction)
├── Interfaces/          (Contracts)
├── Providers/           (Service providers)
└── View/
    └── Components/      (Blade components)

config/
├── auth.php             (Authentication)
├── permission.php       (Permission config)
└── ...

routes/
├── web.php              (Web routes)
└── api.php              (API routes - if used)

database/
├── migrations/          (Schema versions)
└── seeders/             (Initial data)
```

---

## 🔍 **STYLING DECISION MATRIX**

| Situation         | Solution                            | Why                            |
| ----------------- | ----------------------------------- | ------------------------------ |
| Responsive design | Tailwind breakpoints                | Mobile-first approach          |
| Component styling | Blade components + Tailwind classes | Reusability & consistency      |
| DataTable styling | Bootstrap grid + Tailwind buttons   | Legacy compatibility           |
| Form validation   | Bootstrap + Tailwind                | Bootstrap provides states      |
| Icons             | Material Design Icons               | Extensive library              |
| Animations        | Tailwind transitions                | Built-in utilities             |
| Colors            | CSS variables + Tailwind palette    | Both flexibility & consistency |

---

## 📋 **FEATURE CHECKLIST**

### **Implemented:**

- ✅ Responsive design (mobile-first)
- ✅ Role-based access control
- ✅ Server-side DataTables
- ✅ Form validation
- ✅ Search & filtering
- ✅ Export functionality (Excel, PDF, Print)
- ✅ Modern UI components
- ✅ Dark/Light theme ready
- ✅ Accessibility features (focus states, ARIA labels)
- ✅ Icons integration

### **Ready to Implement:**

- 🔵 Dark mode toggle
- 🔵 Multi-language support
- 🔵 Advanced analytics
- 🔵 Real-time notifications
- 🔵 Progressive Web App (PWA)

---

## 🚀 **PERFORMANCE OPTIMIZATION**

### **Current Optimizations:**

```
✅ Tailwind CSS purging (only used classes)
✅ Vite code splitting
✅ Asset minification
✅ CDN for external libraries
✅ Lazy loading images
✅ Server-side pagination
```

### **Recommendations:**

```
1. Enable gzip compression
2. Use HTTP/2 push for critical assets
3. Implement service workers for PWA
4. Cache API responses
5. Optimize database queries with eager loading
```

---

## 📚 **REFERENCES**

- **Tailwind CSS:** https://tailwindcss.com/docs
- **Bootstrap 5:** https://getbootstrap.com/docs/5.3/
- **DataTables:** https://datatables.net/
- **Alpine.js:** https://alpinejs.dev/
- **Laravel:** https://laravel.com/docs/12.x
- **Blade:** https://laravel.com/docs/12.x/blade
- **Spatie Permission:** https://spatie.be/docs/laravel-permission/v6/

---

## 📝 **DEVELOPMENT GUIDELINES**

### **CSS Writing:**

```bash
# ✅ DO: Use Tailwind utilities
<div class="flex items-center justify-between p-6 bg-white rounded-lg shadow-md">

# ❌ DON'T: Mix inline styles with Tailwind
<div style="display: flex" class="flex items-center">

# ❌ DON'T: Create custom CSS for common patterns
<style>.custom-btn { ... }</style>
```

### **Component Creation:**

```bash
# ✅ DO: Create reusable Blade components
<x-button variant="primary" icon="plus">Tambah</x-button>

# ❌ DON'T: Repeat HTML in multiple files
<button class="...">Tambah</button>
```

### **JavaScript:**

```bash
# ✅ DO: Use Alpine.js for simple interactions
<div x-data="{ open: false }">

# ❌ DON'T: Add inline event handlers
<button onclick="handleClick()">
```

---

**Last Updated:** January 22, 2026  
**Version:** 1.0  
**Maintainer:** Toko Rizky Development Team
