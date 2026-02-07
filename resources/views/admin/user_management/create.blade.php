@extends('layouts.master')

@section('content')
    <form class="p-6" action="{{ route('user-management.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-x-6 gap-y-6 md:grid-cols-2">

            <div>
                <label for="userManagement" class="block text-sm font-medium leading-6 text-gray-900">Nama Lengkap</label>
                <div class="mt-2">
                    <input type="text" id="userManagement" name="name" placeholder="cth: John Doe"
                        class="block w-full rounded-md outline-none py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-100 shadow-sm placeholder:text-gray-400 !border-none focus:!ring-1 focus:!ring-inset focus:!ring-gray-300 sm:text-sm">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                <div class="mt-2">
                    <input type="email" id="email" name="email" placeholder="cth: johndoe@email.com"
                        class="block w-full rounded-md outline-none py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-100 shadow-sm placeholder:text-gray-400 !border-none focus:!ring-1 focus:!ring-inset focus:!ring-gray-300 sm:text-sm">
                </div>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Nomor Telepon</label>
                <div class="mt-2">
                    <input type="tel" id="phone" name="phone" placeholder="cth: 081234567890"
                        class="block w-full rounded-md outline-none py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-100 shadow-sm placeholder:text-gray-400 !border-none focus:!ring-1 focus:!ring-inset focus:!ring-gray-300 sm:text-sm"
                        pattern="[0-9]{10,13}" title="Masukkan 10-13 digit angka">
                </div>
                <p class="mt-1 text-xs text-gray-500">Format: 10-13 digit angka (contoh: 081234567890)</p>
            </div>

            <div>
                <label for="roleSelect" class="block text-sm font-medium leading-6 text-gray-900">Pilih Role</label>
                <div class="mt-2">
                    <select id="roleSelect" name="role"
                        class="block w-full rounded-md outline-none py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-100 shadow-sm focus:!ring-1 focus:!ring-inset focus:!ring-gray-300 sm:text-sm">
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                <div class="mt-2 relative">
                    <input type="password" id="password" name="password" placeholder="Masukkan password"
                        class="block w-full rounded-md outline-none py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-100 shadow-sm placeholder:text-gray-400 !border-none focus:!ring-1 focus:!ring-inset focus:!ring-gray-300 sm:text-sm">
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900">Konfirmasi
                    Password</label>
                <div class="mt-2">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="Konfirmasi password"
                        class="block w-full rounded-md outline-none py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-100 shadow-sm placeholder:text-gray-400 !border-none focus:!ring-1 focus:!ring-inset focus:!ring-gray-300 sm:text-sm">
                </div>
            </div>
        </div>

        <div class="mt-8 flex items-center justify-end gap-x-4 border-t border-gray-900/10 pt-6">
            <button type="button" onclick="window.history.back()"
                class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-100 shadow-sm hover:bg-gray-50">
                Batal
            </button>
            <button type="submit"
                class="rounded-md bg-green-600 px-5 py-2.5 text-sm font-semibold text-white ring-1 ring-inset ring-gray-100 shadow-sm hover:bg-green-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                Simpan Data
            </button>
        </div>
    </form>
    <script>
        document.getElementById('phone').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
@endsection
