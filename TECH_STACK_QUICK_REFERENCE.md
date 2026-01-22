# 🎯 QUICK REFERENCE - Technology Stack

## 🏠 PROJECT: TOKO RIZKY

**Sistem Manajemen Inventori & Akses Terpadu**

---

## ⚡ QUICK SUMMARY

### **Backend**

```
PHP 8.2+ → Laravel 12 → Database (MySQL/PostgreSQL)
```

### **Frontend**

```
Tailwind CSS (Primary) + Bootstrap 5 (Supporting)
→ Blade Templates → Alpine.js + jQuery
```

### **Key Libraries**

```
✅ Spatie Permission (RBAC)
✅ Yajra DataTables (Server-side processing)
✅ SweetAlert2 (Dialogs)
✅ Select2 (Dropdowns)
✅ Leaflet (Maps)
```

---

## 🎨 CSS FRAMEWORK COMPARISON

| Aspect               | Tailwind      | Bootstrap         |
| -------------------- | ------------- | ----------------- |
| **Type**             | Utility-first | Component-based   |
| **Usage in Project** | 95%           | 5%                |
| **Primary Use**      | All styling   | Grid + DataTables |
| **Version**          | 3.1.0         | 5.3.0             |

**Hybrid Approach:**

```html
<!-- Tailwind for styling -->
<div class="px-4 py-2 bg-blue-600 text-white rounded-lg">
    <!-- Bootstrap for DataTable grid layout -->
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <!-- Content -->
        </div>
    </div>
</div>
```

---

## 📦 WHAT'S INSTALLED?

### **Styling & UI**

- ✅ **Tailwind CSS** - Utility classes
- ✅ **Bootstrap 5** - Grid & components
- ✅ **PostCSS** - CSS processing
- ✅ **Autoprefixer** - Browser compatibility

### **JavaScript & Interactivity**

- ✅ **Alpine.js** - Lightweight framework
- ✅ **jQuery** - DOM manipulation
- ✅ **Axios** - HTTP client
- ✅ **Vite** - Module bundler

### **Data & Tables**

- ✅ **DataTables** - Server-side tables
- ✅ **Select2** - Enhanced selects
- ✅ **Yajra DataTables** - Laravel integration

### **UX/Dialogs**

- ✅ **SweetAlert2** - Modern dialogs
- ✅ **Material Design Icons** - Icon library

### **Maps & Location**

- ✅ **Leaflet** - Interactive maps
- ✅ **Turf.js** - Geographic analysis

### **Backend**

- ✅ **Laravel 12** - Web framework
- ✅ **Spatie Permission** - RBAC system
- ✅ **Laravel Breeze** - Auth scaffolding

---

## 🎨 STYLING RULES

### **Color Usage**

**Primary Color (Blue):**

```tailwind
bg-blue-600          /* Main color */
hover:bg-blue-700    /* Hover state */
text-blue-600        /* Text color */
ring-blue-500        /* Focus ring */
```

**Secondary Color (Emerald):**

```tailwind
bg-emerald-600       /* Action buttons */
text-emerald-700     /* Success text */
bg-emerald-100       /* Light badge */
```

**Status Colors:**

```tailwind
bg-red-600           /* Danger/Delete */
bg-yellow-500        /* Warning */
bg-green-600         /* Success */
bg-gray-600          /* Neutral */
```

### **Spacing**

```tailwind
p-4                  /* Padding all */
px-4                 /* Padding horizontal */
py-2.5               /* Padding vertical */
mb-6                 /* Margin bottom */
gap-4                /* Grid/flex gap */
```

### **Typography**

```tailwind
text-sm font-semibold     /* Labels */
text-base font-medium     /* Body text */
text-lg font-bold         /* Headings */
text-xs text-gray-500     /* Captions */
```

### **Components**

```tailwind
rounded-lg                   /* Border radius */
shadow-sm                    /* Subtle shadow */
border border-gray-300       /* Borders */
transition-all duration-200  /* Animations */
```

---

## 📐 BREAKPOINTS

```tailwind
sm: 640px   /* Small devices */
md: 768px   /* Tablets */
lg: 1024px  /* Desktop */
xl: 1280px  /* Large screens */
```

**Usage:**

```html
<div class="w-full md:w-1/2 lg:w-1/3">Responsive columns</div>
```

---

## 🧩 COMPONENT EXAMPLES

### **Button Component**

```blade
<x-button variant="primary" icon="plus" size="md">
  Tambah Data
</x-button>

<!-- Variants: primary, secondary, danger, ghost, outline -->
<!-- Sizes: sm, md, lg, xl -->
```

### **Badge Component**

```blade
<x-badge variant="success" size="md">
  Active
</x-badge>

<!-- Variants: default, primary, secondary, success, warning, danger -->
```

### **Card Component**

```blade
<x-card title="Information" subtitle="Details">
  <p>Card content here</p>
</x-card>
```

### **Page Header Component**

```blade
<x-page-header
  title="Manajemen Pengguna"
  description="Kelola akun pengguna"
  :breadcrumbs="[...]">
  <x-slot name="actions">
    <!-- Action buttons -->
  </x-slot>
</x-page-header>
```

---

## 🔧 CONFIGURATION FILES

### **Tailwind**

```
Location: tailwind.config.js
```

### **Vite**

```
Location: vite.config.js
Entry: resources/css/app.css, resources/js/app.js
```

### **PostCSS**

```
Location: postcss.config.js
Processors: tailwindcss, autoprefixer
```

### **Laravel**

```
Location: config/
Key files:
  - auth.php (Authentication)
  - permission.php (Spatie permission)
  - database.php (Database connection)
```

---

## 🚀 COMMON COMMANDS

### **Development**

```bash
# Start all services
composer run dev

# Only frontend
npm run dev

# Only backend
php artisan serve

# Build for production
npm run build
```

### **Database**

```bash
# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed

# Create migration
php artisan make:migration create_table_name
```

### **Cache**

```bash
# Clear all caches
php artisan optimize:clear

# Clear config cache
php artisan config:clear
```

---

## 🎯 STYLING WORKFLOW

### **Step 1: Design Component**

Create Blade component in `/resources/views/components/`

### **Step 2: Add Tailwind Classes**

```blade
<div class="flex items-center gap-4 p-6 bg-white rounded-lg shadow-md border border-gray-200">
  <!-- Content -->
</div>
```

### **Step 3: Make Responsive**

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
  <!-- Content -->
</div>
```

### **Step 4: Add States**

```blade
<button class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800
              disabled:opacity-50 disabled:cursor-not-allowed
              focus:ring-2 focus:ring-blue-500">
  Click me
</button>
```

---

## 🔍 FOLDER STRUCTURE OVERVIEW

```
toko_rizky/
├── app/
│   ├── Models/              ← Database models
│   ├── Controllers/         ← Route handlers
│   ├── Repositories/        ← Data layer
│   ├── Interfaces/          ← Contracts
│   └── Providers/           ← Service providers
│
├── resources/
│   ├── views/
│   │   ├── components/      ← Reusable components
│   │   ├── layouts/         ← Master layout
│   │   └── admin/           ← Admin pages
│   ├── css/
│   │   └── app.css          ← Tailwind imports
│   └── js/
│       └── app.js           ← Alpine.js
│
├── config/                  ← Configuration
├── database/                ← Migrations & seeders
├── routes/                  ← Route definitions
│
├── tailwind.config.js       ← Tailwind config
├── vite.config.js           ← Vite config
├── postcss.config.js        ← PostCSS config
├── package.json             ← NPM dependencies
├── composer.json            ← PHP dependencies
└── techStack.md             ← This documentation
```

---

## 📊 TECHNOLOGY DISTRIBUTION

```
Backend Logic:        30%  (PHP/Laravel)
Frontend Styling:     45%  (Tailwind/Bootstrap)
JavaScript:           15%  (Alpine/jQuery)
Database:             10%  (Eloquent/MySQL)
```

---

## ✅ BEST PRACTICES

### **DO:**

```html
✅
<button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
    ✅ <x-button variant="primary">Button</x-button> ✅ Use grid/flex for
    layouts ✅ Mobile-first responsive design ✅ Semantic HTML elements
</button>
```

### **DON'T:**

```html
❌
<button style="padding: 1rem; background: blue;">
    ❌ Inline event handlers (use Alpine.js) ❌ Multiple style attributes ❌
    Hardcoded colors (use variables) ❌ Non-semantic divs for everything
</button>
```

---

## 🔗 USEFUL LINKS

| Resource              | URL                                       |
| --------------------- | ----------------------------------------- |
| **Tailwind CSS**      | https://tailwindcss.com/docs              |
| **Bootstrap**         | https://getbootstrap.com/docs/5.3/        |
| **Laravel**           | https://laravel.com/docs/12.x             |
| **DataTables**        | https://datatables.net/                   |
| **Alpine.js**         | https://alpinejs.dev/                     |
| **Material Icons**    | https://fonts.google.com/icons            |
| **Spatie Permission** | https://spatie.be/docs/laravel-permission |

---

**Version:** 1.0  
**Last Updated:** January 22, 2026  
**Status:** ✅ Complete
