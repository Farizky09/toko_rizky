<aside id="sidebar"
    class="sidebar overflow-y-scroll scrollbar-none inset-y-0 left-0 z-30 flex h-full w-[320px] flex-col border-r border-gray-200 bg-white shadow-sm transform -translate-x-full md:relative md:translate-x-0"
    style="-ms-overflow-style: none; scrollbar-width: none;" onscroll="this.style.scrollbarWidth='none'">
    <div class="flex h-16 items-center justify-center border-b border-gray-200 py-4">
        <a href="#" class="flex items-center gap-2 text-xl font-bold text-gray-800">
            TOKO RIZKy
        </a>
    </div>

    <nav class="flex-1 space-y-2 p-4">



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
        {{-- <a href="#"
            class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-gray-600 hover:bg-gray-100 hover:text-gray-800 font-medium">
            <span class="mdi mdi-cog-outline text-xl"></span>
            Pengaturan
        </a> --}}
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

@push('scripts')
    <script>
        const sidebar = document.getElementById('sidebar');
        // const menuButton = document.getElementById('menu-button');
        const overlay = document.getElementById('overlay');
        const menuItems = document.querySelectorAll('aside nav a');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Fungsi untuk menangani menu aktif
        function setActiveMenu(event) {
            // Hapus kelas aktif dari semua item menu
            menuItems.forEach(item => {
                item.classList.remove('bg-green-600', 'text-white');
                item.classList.add('text-gray-600', 'hover:bg-gray-100');
            });

            // Tambahkan kelas aktif ke item yang diklik
            const clickedItem = event.currentTarget;
            clickedItem.classList.add('bg-green-600', 'text-white');
            clickedItem.classList.remove('text-gray-600', 'hover:bg-gray-100');
        }

        // menuButton.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Tambahkan event listener ke setiap item menu
        menuItems.forEach(item => {
            item.addEventListener('click', setActiveMenu);
        });
    </script>
@endpush
