<aside id="sidebar"
    class="sidebar overflow-y-scroll scrollbar-none inset-y-0 left-0 z-30 flex h-full w-[320px] flex-col border-r border-gray-200 bg-white shadow-sm transform -translate-x-full md:relative md:translate-x-0"
    style="-ms-overflow-style: none; scrollbar-width: none;" onscroll="this.style.scrollbarWidth='none'">

    <!-- Logo & Header -->
    <div class="flex h-16 items-center justify-center border-b border-gray-200 py-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-xl font-bold text-gray-800">
            <span class="mdi mdi-storefront text-green-600 text-2xl"></span>
            TOKO RIZKy
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 space-y-1 p-4">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 rounded-lg px-4 py-3 font-medium transition-colors
                {{ request()->routeIs('dashboard') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-800' }}">
            <span class="mdi mdi-view-dashboard text-xl"></span>
            Dashboard
        </a>

        <!-- Master Data Section -->
        <div class="px-4 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Master Data
        </div>

        <!-- Produk Dropdown -->
        <div x-data="{ open: {{ request()->routeIs(['products.*', 'categories.*', 'units.*']) ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open"
                class="flex w-full items-center justify-between gap-3 rounded-lg px-4 py-3 font-medium transition-colors
                    {{ request()->routeIs(['products.*', 'categories.*', 'units.*']) ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-800' }}">
                <div class="flex items-center gap-3">
                    <span class="mdi mdi-package-variant text-xl"></span>
                    Produk
                </div>
                <span class="mdi mdi-chevron-down transition-transform duration-200"
                    :class="{ 'rotate-180': open }"></span>
            </button>

            <div x-show="open" x-collapse class="ml-4 space-y-1 border-l-2 border-gray-200 pl-4">
                <a href="{{ route('products.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition-colors
                        {{ request()->routeIs('products.*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-format-list-bulleted text-lg"></span>
                    Daftar Produk
                </a>
                <a href="{{ route('categories.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition-colors
                        {{ request()->routeIs('categories.*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-tag-multiple text-lg"></span>
                    Kategori
                </a>
                <div x-data="{ unitOpen: {{ request()->routeIs(['unit-smalls.*', 'unit-larges.*']) ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="unitOpen = !unitOpen"
                        class="flex w-full items-center justify-between gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition-colors
                            {{ request()->routeIs(['unit-smalls.*', 'unit-larges.*']) ? 'text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                        <div class="flex items-center gap-3">
                            <span class="mdi mdi-scale text-lg"></span>
                            Satuan
                        </div>
                        <span class="mdi mdi-chevron-down text-xs transition-transform duration-200"
                            :class="{ 'rotate-180': unitOpen }"></span>
                    </button>
                    <div x-show="unitOpen" x-collapse class="ml-4 space-y-1 border-l-2 border-gray-200 pl-4">
                        <a href="{{ route('unit-smalls.index') }}"
                            class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium transition-colors
                                {{ request()->routeIs('unit-smalls.*') ? 'text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                            <span class="mdi mdi-circle-small text-lg"></span>
                            Satuan Kecil
                        </a>
                        <a href="{{ route('unit-larges.index') }}"
                            class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium transition-colors
                                {{ request()->routeIs('unit-larges.*') ? 'text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                            <span class="mdi mdi-circle-medium text-lg"></span>
                            Satuan Besar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pemasok & Lokasi Dropdown -->
        <div x-data="{ open: {{ request()->routeIs(['suppliers.*', 'branches.*', 'locations.*']) ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open"
                class="flex w-full items-center justify-between gap-3 rounded-lg px-4 py-3 font-medium transition-colors
                    {{ request()->routeIs(['suppliers.*', 'branches.*', 'locations.*']) ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-800' }}">
                <div class="flex items-center gap-3">
                    <span class="mdi mdi-account-group text-xl"></span>
                    Pemasok & Lokasi
                </div>
                <span class="mdi mdi-chevron-down transition-transform duration-200"
                    :class="{ 'rotate-180': open }"></span>
            </button>

            <div x-show="open" x-collapse class="ml-4 space-y-1 border-l-2 border-gray-200 pl-4">
                <a href="{{ route('suppliers.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition-colors
                        {{ request()->routeIs('suppliers.*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-truck-delivery text-lg"></span>
                    Pemasok
                </a>
                <a href="{{ route('branches.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition-colors
                        {{ request()->routeIs('branches.*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-store text-lg"></span>
                    Cabang
                </a>
                <a href="{{ route('locations.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition-colors
                        {{ request()->routeIs('locations.*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                    <span class="mdi mdi-map-marker text-lg"></span>
                    Lokasi
                </a>
            </div>
        </div>

        <!-- Transaksi Section -->
        <div class="px-4 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Transaksi
        </div>

        <!-- Pembelian -->
        <a href="{{ route('purchases.index') }}"
            class="flex items-center gap-3 rounded-lg px-4 py-3 font-medium transition-colors
                {{ request()->routeIs('purchases.*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-800' }}">
            <span class="mdi mdi-cart-arrow-down text-xl"></span>
            Pembelian
        </a>
        <a href="{{ route('good-receipts.index') }}"
            class="flex items-center gap-3 rounded-lg px-4 py-3 font-medium transition-colors
                {{ request()->routeIs('good-receipts.*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-800' }}">
            <span class="mdi mdi-cart-arrow-down text-xl"></span>
            Penerimaan Barang
        </a>

        <!-- Manajemen Sistem Section -->
        @canany(['read_permission', 'read_role', 'read_user_management'])
            <div class="px-4 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Manajemen Sistem
            </div>

            <div x-data="{ open: {{ request()->routeIs(['permission.*', 'role.*', 'user-management.*']) ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open"
                    class="flex w-full items-center justify-between gap-3 rounded-lg px-4 py-3 font-medium transition-colors
                        {{ request()->routeIs(['permission.*', 'role.*', 'user-management.*']) ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-800' }}">
                    <div class="flex items-center gap-3">
                        <span class="mdi mdi-shield-account text-xl"></span>
                        Manajemen Akses
                    </div>
                    <span class="mdi mdi-chevron-down transition-transform duration-200"
                        :class="{ 'rotate-180': open }"></span>
                </button>

                <div x-show="open" x-collapse class="ml-4 space-y-1 border-l-2 border-gray-200 pl-4">
                    @can('read_permission')
                        <a href="{{ route('permission.index') }}"
                            class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition-colors
                                {{ request()->routeIs('permission.*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                            <span class="mdi mdi-key-chain text-lg"></span>
                            Izin
                        </a>
                    @endcan
                    @can('read_role')
                        <a href="{{ route('role.index') }}"
                            class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition-colors
                                {{ request()->routeIs('role.*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                            <span class="mdi mdi-account-group text-lg"></span>
                            Peran
                        </a>
                    @endcan
                    @can('read_user_management')
                        <a href="{{ route('user-management.index') }}"
                            class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition-colors
                                {{ request()->routeIs('user-management.*') ? 'bg-gray-100 text-green-700' : 'text-gray-500 hover:text-gray-800' }}">
                            <span class="mdi mdi-account-cog text-lg"></span>
                            Pengguna
                        </a>
                    @endcan
                </div>
            </div>
        @endcanany

        <!-- Laporan Section -->
        <div class="px-4 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Laporan
        </div>

        {{-- <a href="{{ route('reports.index') }}"
            class="flex items-center gap-3 rounded-lg px-4 py-3 font-medium transition-colors
                {{ request()->routeIs('reports.*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-800' }}">
            <span class="mdi mdi-chart-bar text-xl"></span>
            Laporan
        </a> --}}
    </nav>

    <!-- User Profile & Logout -->
    <div class="mt-auto border-t border-gray-200 p-4">
        <div class="flex items-center gap-3 px-4 py-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                <span class="mdi mdi-account text-green-600"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                    {{ auth()->user()->name ?? 'Administrator' }}
                </p>
                <p class="text-xs text-gray-500 truncate">
                    {{ auth()->user()->email ?? 'admin@example.com' }}
                </p>
            </div>
        </div>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit"
                class="flex w-full items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                <span class="mdi mdi-logout text-lg"></span>
                Keluar
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Overlay -->
<div id="overlay" class="fixed inset-0 z-20 bg-black bg-opacity-50 hidden md:hidden" onclick="toggleSidebar()">
</div>

@push('scripts')
    <script>
        // Toggle sidebar for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            if (!sidebar.contains(event.target) && !overlay.classList.contains('hidden')) {
                toggleSidebar();
            }
        });
    </script>
@endpush
