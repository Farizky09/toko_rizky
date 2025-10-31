<aside id="sidebar"
    class="sidebar overflow-y-scroll scrollbar-none inset-y-0 left-0 z-30 flex h-full w-[320px] flex-col border-r border-gray-200 bg-white shadow-sm transform -translate-x-full md:relative md:translate-x-0"
    style="-ms-overflow-style: none; scrollbar-width: none;" onscroll="this.style.scrollbarWidth='none'">
    <div class="flex h-16 items-center justify-center border-b border-gray-200 py-4">
        <a href="#" class="flex items-center gap-2 text-xl font-bold text-gray-800">
            TOKO RIZKy
        </a>
    </div>

    <nav class="flex-1 space-y-2 p-4">
        <!-- Label Master Data -->
        <div class="px-4 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Master Data
        </div>

        <!-- Dropdown Product -->
        <div x-data="{ open: {{ request()->routeIs(['categories.*', 'brand.*', 'unit_smalls.*', 'unit_larges.*', 'products.*']) ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open"
                class="flex w-full items-center justify-between gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                {{ request()->routeIs(['categories.*', 'brand.*', 'unit_smalls.*', 'unit_larges.*', 'products.*']) ? 'bg-green-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-800' }}">
                <div class="flex items-center gap-3">
                    <span class="mdi mdi-package-variant-closed text-xl"></span>
                    Product
                </div>
                <span class="mdi mdi-chevron-down transition-transform" :class="{ 'rotate-180': open }"></span>
            </button>
            <div x-show="open" x-transition class="ml-4 space-y-1 border-l-2 border-gray-200 pl-4">
                <a href="{{ route('categories.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                    {{ request()->routeIs('categories.index*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-tag-outline text-lg"></span>
                    Kategori
                </a>
                {{-- <a href="{{ route('brand.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                    {{ request()->routeIs('brand.index*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-label-outline text-lg"></span>
                    Brand
                </a> --}}
                <a href="{{ route('unit_smalls.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                    {{ request()->routeIs('unit_smalls.index*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-scale-balance text-lg"></span>
                    Satuan Kecil
                </a>
                <a href="{{ route('unit_larges.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                    {{ request()->routeIs('unit_larges.index*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-scale text-lg"></span>
                    Satuan Besar
                </a>
                <a href="{{ route('products.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                    {{ request()->routeIs('products.index*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-package-variant-closed text-lg"></span>
                    Product
                </a>
            </div>
        </div>

        <!-- Dropdown People -->
        <div x-data="{ open: {{ request()->routeIs(['suppliers.*', 'branches.*', 'stock-staff.*', 'customer.*', 'locations.*']) ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open"
                class="flex w-full items-center justify-between gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                {{ request()->routeIs(['suppliers.*', 'branches.*', 'stock-staff.*', 'customer.*']) ? 'bg-green-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-800' }}">
                <div class="flex items-center gap-3">
                    <span class="mdi mdi-account-group text-xl"></span>
                    People
                </div>
                <span class="mdi mdi-chevron-down transition-transform" :class="{ 'rotate-180': open }"></span>
            </button>
            <div x-show="open" x-transition class="ml-4 space-y-1 border-l-2 border-gray-200 pl-4">
                <a href="{{ route('suppliers.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                    {{ request()->routeIs('suppliers.index*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-truck-outline text-lg"></span>
                    Supplier
                </a>
                <a href="{{ route('branches.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                    {{ request()->routeIs('branches.index*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-cash-register text-lg"></span>
                    Cabang
                </a>
                <a href="{{ route('locations.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                    {{ request()->routeIs('locations.index*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-cash-register text-lg"></span>
                    Lokasi
                </a>
                {{-- <a href="{{ route('stock-staff.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                    {{ request()->routeIs('stock-staff.index*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-account-box-multiple text-lg"></span>
                    Stock Staff
                </a>
                <a href="{{ route('customer.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                    {{ request()->routeIs('customer.index*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-account-outline text-lg"></span>
                    Customer
                </a> --}}
            </div>
        </div>

        @canany(['read_permission', 'read_role', 'read_user_management'])
            <!-- Label Manajemen Sistem -->
            <div class="px-4 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Manajemen
            </div>
            <div x-data="{ open: {{ request()->routeIs(['permission.*', 'role.*', 'user_management.*']) ? 'true' : 'false' }} }" class="space-y-1">
                <!-- Tombol Dropdown -->
                <button @click="open = !open"
                    class="flex w-full items-center justify-between gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
            {{ request()->routeIs(['permission.*', 'role.*', 'user_management.*']) ? 'bg-green-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-800' }}">
                    <div class="flex items-center gap-3">
                        <span class="mdi mdi-shield-account-outline text-xl"></span>
                        Manajemen Akses
                    </div>
                    <span class="mdi mdi-chevron-down transition-transform" :class="{ 'rotate-180': open }"></span>
                </button>
                <!-- Submenu -->
                <div x-show="open" x-transition class="ml-4 space-y-1 border-l-2 border-gray-200 pl-4">
                    @can('read_permission')
                        <a href="{{ route('permission.index') }}"
                            class="flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                {{ request()->routeIs('permission.index*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                            <span class="mdi mdi-key-outline text-lg"></span>
                            Permission
                        </a>
                    @endcan
                    @can('read_role')
                        <a href="{{ route('role.index') }}"
                            class="flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                {{ request()->routeIs('role.index*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                            <span class="mdi mdi-account-group-outline text-lg"></span>
                            Role
                        </a>
                    @endcan
                    @can('read_user_management')
                        <a href="{{ route('user_management.index') }}"
                            class="flex items-center gap-3 rounded-lg px-4 py-2.5 font-medium transition-colors
                {{ request()->routeIs('user_management.index*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                            <span class="mdi mdi-account-cog-outline text-lg"></span>
                            Management User
                        </a>
                    @endcan
                </div>
            </div>
        @endcanany
    </nav>

    <div class="mt-auto border-t border-gray-200 p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex w-full items-center gap-3 rounded-lg px-4 py-2.5 text-red-500 hover:bg-red-50 font-medium">
                <span class="mdi mdi-logout text-xl"></span>
                Keluar
            </button>
        </form>
    </div>
</aside>
<div id="overlay" class="fixed inset-0 z-20 bg-black bg-opacity-50 hidden md:hidden"></div>
