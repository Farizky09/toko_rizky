# 🏗️ ARCHITECTURE DIAGRAM - Toko Rizky

## System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                         CLIENT LAYER                            │
│  Browser / Mobile / Desktop                                     │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                     PRESENTATION LAYER                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  🎨 View & Template Engine                                      │
│  ├── Blade Templates (.blade.php)                               │
│  ├── Reusable Components (x-button, x-card, etc)                │
│  └── Master Layout (master.blade.php)                           │
│                                                                 │
│  📦 Frontend Build Tools                                        │
│  ├── Vite (Module Bundler)                                      │
│  ├── PostCSS (CSS Processing)                                   │
│  └── Autoprefixer (Browser Compatibility)                       │
│                                                                 │
│  🎭 Styling Frameworks                                          │
│  ├── Tailwind CSS 3.1 (95% Usage) ⭐                             │
│  │   └── Utility-first approach                                 │
│  ├── Bootstrap 5.3 (5% Usage)                                   │
│  │   └── Grid system + DataTables                               │
│  └── Custom CSS Variables (Colors, Spacing)                     │
│                                                                 │
│  ⚙️ JavaScript Libraries                                        │
│  ├── Alpine.js (3.4.2) - Lightweight framework                  │
│  ├── jQuery (3.7.0) - DOM manipulation                          │
│  ├── Axios (1.11.0) - HTTP client                               │
│  ├── DataTables (1.13.6) - Server-side tables                   │
│  ├── Select2 (4.1.0) - Enhanced dropdowns                       │
│  ├── SweetAlert2 (11) - Modern dialogs                          │
│  ├── Leaflet (1.9.4) - Interactive maps                         │
│  └── Material Design Icons - Icon library                       │
│                                                                 │
│  🔤 Typography                                                  │
│  ├── Font: Inter (Google Fonts)                                 │
│  └── Fallback: Figtree                                          │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
                              │
                    HTTP/HTTPS (REST API)
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                     APPLICATION LAYER                           │
├─────────────────────────────────────────────────────────────────┤
│  Laravel 12 Framework                                           │
│                                                                 │
│  🛣️ Routing & Middleware                                        │
│  ├── routes/web.php (Web routes)                                │
│  ├── routes/api.php (API routes)                                │
│  └── Middleware (Auth, RBAC, CORS)                              │
│                                                                 │
│  🎮 Controllers                                                 │
│  ├── PermissionController                                       │
│  ├── RoleController                                             │
│  ├── UserManagementController                                   │
│  ├── ProductController                                          │
│  ├── PurchaseController                                         │
│  └── [...other controllers...]                                  │
│                                                                 │
│  🏷️ Service Layer                                               │
│  ├── Repositories (Data abstraction)                            │
│  ├── Interfaces (Contracts)                                     │
│  └── Services (Business logic)                                  │
│                                                                 │
│  🔐 Authentication & Authorization                              │
│  ├── Laravel Breeze (Auth scaffolding)                          │
│  ├── Spatie Permission (RBAC) ⭐                                │
│  │   ├── Roles (Admin, Manager, Staff)                          │
│  │   ├── Permissions (create, read, update, delete)             │
│  │   └── Many-to-many relationships                             │
│  └── Guards & Providers                                         │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                      MODEL LAYER                                │
├─────────────────────────────────────────────────────────────────┤
│  Eloquent ORM                                                   │
│                                                                 │
│  👥 Models                                                      │
│  ├── User                                                       │
│  ├── Role                                                       │
│  ├── Permission                                                 │
│  ├── Product                                                    │
│  ├── Category                                                   │
│  ├── Supplier                                                   │
│  ├── Purchase                                                   │
│  ├── GoodReceipt                                                │
│  ├── Branch                                                     │
│  ├── Location                                                   │
│  ├── UnitLarge                                                  │
│  └── UnitSmall                                                  │
│                                                                 │
│  🔗 Relationships                                               │
│  ├── One-to-Many                                                │
│  ├── Many-to-Many (Roles ↔ Permissions)                         │
│  └── Polymorphic (as needed)                                    │
│                                                                 │
│  🚀 Features                                                    │
│  ├── Timestamps (created_at, updated_at)                        │
│  ├── Soft Deletes (optional)                                    │
│  ├── Query Scopes                                               │
│  └── Accessors/Mutators                                         │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                      DATA LAYER                                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  📊 Database                                                    │
│  ├── MySQL / PostgreSQL                                         │
│  ├── Tables (users, roles, permissions, products, etc)          │
│  └── Indexes (for performance)                                  │
│                                                                 │
│  🔄 Migrations                                                  │
│  ├── Version control untuk database schema                      │
│  ├── Up/Down methods                                            │
│  └── Seeder untuk initial data                                  │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## Frontend Technology Stack - Detailed Flow

```
┌──────────────────────────────────────────────────────────────┐
│                    SOURCE FILES                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  📄 Blade Templates              📦 CSS                     │
│  └── *.blade.php                 └── app.css               │
│      ├── components/                 └── @import Tailwind │
│      ├── layouts/                                           │
│      └── admin/                  🎨 Tailwind Config        │
│                                  └── tailwind.config.js    │
│  🎭 JavaScript                                              │
│  └── *.js                        🔧 PostCSS Config         │
│      ├── Alpine.js               └── postcss.config.js     │
│      └── Custom scripts                                     │
│                                                              │
└──────────────────────────────────────────────────────────────┘
                          │
                          ▼
┌──────────────────────────────────────────────────────────────┐
│                    VITE BUILD PROCESS                        │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  1️⃣  Entry Points                                           │
│      ├── resources/css/app.css                              │
│      └── resources/js/app.js                                │
│                                                              │
│  2️⃣  Transform                                              │
│      ├── Tailwind CSS compilation                           │
│      ├── PostCSS processing                                 │
│      ├── Autoprefixer (vendor prefixes)                     │
│      └── JavaScript module resolution                       │
│                                                              │
│  3️⃣  Optimize                                               │
│      ├── Code splitting                                     │
│      ├── Tree shaking                                       │
│      ├── Minification                                       │
│      └── Asset optimization                                 │
│                                                              │
│  4️⃣  Output                                                 │
│      ├── /public/build/                                     │
│      │   ├── app.js                                         │
│      │   ├── app.css                                        │
│      │   └── manifest.json (asset mapping)                  │
│      └── Hashed filenames (cache busting)                   │
│                                                              │
└──────────────────────────────────────────────────────────────┘
                          │
                          ▼
┌──────────────────────────────────────────────────────────────┐
│                   RENDERING IN BROWSER                       │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  1️⃣  Blade Template Rendering (Server-side)                │
│      ├── Parse {{ variables }}                              │
│      ├── Process @directives (@if, @foreach, etc)           │
│      └── Include components <x-component />                 │
│                                                              │
│  2️⃣  HTML Output                                            │
│      ├── Load CSS: <link href="/build/app.css">             │
│      ├── Load JS: <script src="/build/app.js">              │
│      └── Alpine.js auto-initialization                      │
│                                                              │
│  3️⃣  CSS Applied                                            │
│      ├── Tailwind utility classes                           │
│      ├── Bootstrap grid/components                          │
│      └── Custom CSS (colors, animations)                    │
│                                                              │
│  4️⃣  JavaScript Execution                                   │
│      ├── Alpine.js reactive components                      │
│      ├── jQuery DOM manipulation                            │
│      ├── Event listeners                                    │
│      └── AJAX requests (Axios)                              │
│                                                              │
│  5️⃣  DOM Ready & Interactions                               │
│      ├── DataTables initialization                          │
│      ├── Select2 dropdown enhancement                       │
│      ├── Form validation                                    │
│      └── User interactions                                  │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

---

## Styling Architecture

```
┌─────────────────────────────────────────────┐
│      TAILWIND CSS (Primary - 95%)           │
├─────────────────────────────────────────────┤
│                                             │
│  Utility Classes                            │
│  ├── Layout (flex, grid, absolute, etc)    │
│  ├── Spacing (p, m, w, h, gap, etc)        │
│  ├── Typography (text-*, font-*, etc)      │
│  ├── Colors (bg-*, text-*, border-*, etc)  │
│  ├── Borders (rounded, border, etc)        │
│  ├── Effects (shadow, opacity, etc)        │
│  └── Responsive (sm:, md:, lg:, etc)       │
│                                             │
│  Custom Extensions                          │
│  ├── CSS Variables (--primary-600, etc)    │
│  ├── Theme colors (Blue, Emerald)          │
│  └── Font families (Inter, Figtree)        │
│                                             │
└─────────────────────────────────────────────┘
                    │
        ┌───────────┴───────────┐
        │                       │
        ▼                       ▼
┌───────────────────┐  ┌──────────────────┐
│  BOOTSTRAP 5      │  │  CUSTOM CSS      │
│  (Supporting)     │  │  (Global styles) │
├───────────────────┤  ├──────────────────┤
│                   │  │                  │
│ Grid System       │  │ CSS Variables    │
│ ├── .row          │  │ ├── Colors       │
│ └── .col-*        │  │ ├── Spacing      │
│                   │  │ └── Typography   │
│ Components        │  │                  │
│ ├── Forms         │  │ Keyframes        │
│ ├── Buttons       │  │ ├── Fade         │
│ └── Tables        │  │ └── Slide        │
│                   │  │                  │
│ DataTables        │  │ Animations       │
│ Integration       │  │ └── Transitions  │
│                   │  │                  │
└───────────────────┘  └──────────────────┘
```

---

## Data Flow - Example: User Management

```
┌─────────────────────────────────────────────────────────────┐
│  1. User Request                                            │
│  Browser → Click "Tambah Pengguna"                          │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│  2. Route Resolution                                        │
│  GET /admin/user-management/create                          │
│  └── routes/web.php → UserManagementController@create       │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│  3. Controller Action                                       │
│  UserManagementController {                                 │
│    public function create() {                               │
│      $roles = Role::all();                                  │
│      return view('admin.user_management.create', [          │
│        'roles' => $roles                                    │
│      ]);                                                    │
│    }                                                        │
│  }                                                          │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│  4. Blade Template Rendering                                │
│  resources/views/admin/user_management/create.blade.php     │
│  ├── x-page-header (component)                              │
│  ├── x-card (component)                                     │
│  │   ├── Form inputs (with Tailwind classes)                │
│  │   ├── File upload                                        │
│  │   └── Select2 dropdown (role selection)                  │
│  └── x-button (component)                                   │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│  5. HTML Output to Browser                                  │
│  ├── Tailwind CSS classes rendered                          │
│  ├── Responsive design applied                              │
│  └── JavaScript libraries loaded                            │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│  6. JavaScript Initialization                               │
│  ├── Alpine.js parses x-data directives                     │
│  ├── Select2 enhances dropdown                              │
│  ├── Form validation ready                                  │
│  └── File preview functionality                             │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│  7. User Interaction                                        │
│  ├── Fill form fields                                       │
│  ├── Upload profile picture                                 │
│  └── Select role from dropdown                              │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│  8. Form Submission                                         │
│  POST /admin/user-management                                │
│  └── Data sent via Axios/jQuery                             │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│  9. Backend Processing                                      │
│  UserManagementController@store {                           │
│    ├── Validate request                                     │
│    ├── Create user via Repository                           │
│    ├── Assign role (Spatie Permission)                      │
│    └── Save to database                                     │
│  }                                                          │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│  10. Response                                               │
│  ├── Redirect to index                                      │
│  ├── Flash success message                                  │
│  └── SweetAlert notification                                │
└─────────────────────────────────────────────────────────────┘
```

---

## Component Hierarchy

```
┌──────────────────────────────┐
│   master.blade.php           │  ← Main Layout
│  (CSS, JS, Global setup)     │
└──────────────────────────────┘
              │
    ┌─────────┼─────────┐
    │         │         │
    ▼         ▼         ▼
┌────────┐ ┌──────────┐ ┌─────────┐
│Sidebar │ │Main Area │ │ Footer  │
│        │ │          │ │(optional)
└────────┘ │┌────────┐│ └─────────┘
           ││ Page   ││
           │└────────┘│
           │  ┌────┐  │
           │  │View│  │
           │  └────┘  │
           └──────────┘
              │
    ┌─────────┼────────────────┐
    │         │                │
    ▼         ▼                ▼
┌──────────┐ ┌──────────┐ ┌──────────┐
│x-button  │ │x-card    │ │x-badge   │
│(reusable)│ │(reusable)│ │(reusable)│
└──────────┘ └──────────┘ └──────────┘
```

---

## Authorization Flow (Spatie Permission)

```
┌─────────────────────────────────┐
│     User Request                │
└─────────────────────────────────┘
            │
            ▼
┌─────────────────────────────────┐
│   Check Authentication          │
│   Is user logged in?            │
└─────────────────────────────────┘
        YES    │    NO
        │      └──→ Redirect to login
        ▼
┌─────────────────────────────────┐
│   Get User Roles                │
│   SELECT * FROM roles           │
│   WHERE user_id = ?             │
└─────────────────────────────────┘
        │
        ▼
┌─────────────────────────────────┐
│   Get Role Permissions          │
│   SELECT * FROM permissions     │
│   WHERE role_id IN (...)        │
└─────────────────────────────────┘
        │
        ▼
┌─────────────────────────────────┐
│   Check Permission              │
│   @can('create_user')           │
│   @canany(['read', 'update'])   │
└─────────────────────────────────┘
    ALLOWED  │  DENIED
      │      └──→ 403 Forbidden
      ▼
┌─────────────────────────────────┐
│   Execute Action                │
│   Show content                  │
└─────────────────────────────────┘
```

---

## File Organization by Feature

```
Feature: User Management
├── Routes
│   └── routes/web.php
│       POST /admin/user-management
│       GET /admin/user-management/{id}/edit
│
├── Controller
│   └── app/Http/Controllers/UserManagementController.php
│       - create()
│       - store()
│       - edit()
│       - update()
│       - delete()
│
├── Repository
│   └── app/Repositories/UserManagementRepository.php
│       - Database queries
│       - Data access
│
├── Model
│   └── app/Models/User.php
│       - Relationships
│       - Eloquent methods
│
├── Views
│   └── resources/views/admin/user_management/
│       ├── index.blade.php
│       ├── create.blade.php
│       ├── edit.blade.php
│       └── column/
│           └── action.blade.php
│
├── Requests (optional)
│   └── app/Http/Requests/
│       ├── StoreUserRequest.php
│       └── UpdateUserRequest.php
│
└── Interfaces
    └── app/Interfaces/UserManagementInterfaces.php
        - Contract definitions
```

---

**Version:** 1.0  
**Last Updated:** January 22, 2026
