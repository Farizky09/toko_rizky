@extends('layouts.master')

@section('content')
    <form class="p-6" action="{{ route('user-management.update', $data->id) }}" id="registerForm" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 gap-x-6 gap-y-6 md:grid-cols-2">

            <div>
                <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nama Lengkap</label>
                <div class="mt-2">
                    <input type="text" id="name" name="name" value="{{ $data->name }}"
                        placeholder="cth: John Doe"
                        class="block w-full rounded-md outline-none py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-100 shadow-sm placeholder:text-gray-400 !border-none focus:!ring-1 focus:!ring-inset focus:!ring-gray-300 sm:text-sm">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                <div class="mt-2">
                    <input type="email" id="email" name="email" value="{{ $data->email }}"
                        placeholder="cth: johndoe@email.com"
                        class="block w-full rounded-md outline-none py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-100 shadow-sm placeholder:text-gray-400 !border-none focus:!ring-1 focus:!ring-inset focus:!ring-gray-300 sm:text-sm">
                </div>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Nomor Telepon</label>
                <div class="mt-2">
                    <input type="tel" id="phone" name="phone" value="{{ $data->phone }}"
                        placeholder="cth: 081234567890"
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
                            <option value="{{ $role->id }}" {{ $data->hasRole($role->name) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
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
                Simpan Perubahan
            </button>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const role = document.getElementById('roleSelect').value;

            if (!name || !email || !phone || !role) {
                event.preventDefault();

                let emptyFields = [];
                if (!name) emptyFields.push('Nama Lengkap');
                if (!email) emptyFields.push('Email');
                if (!phone) emptyFields.push('Nomor Telepon');
                if (!role) emptyFields.push('Role');

                let errorMessage = 'Silakan lengkapi semua kolom yang wajib diisi:';
                emptyFields.forEach(field => {
                    errorMessage += `\n• ${field}`;
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Data Belum Lengkap',
                    text: errorMessage,
                    confirmButtonColor: '#16a34a',
                    confirmButtonText: 'Mengerti'
                });
                return;
            }

            const phoneRegex = /^[0-9]{10,13}$/;
            if (!phoneRegex.test(phone)) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Format Nomor Telepon Salah',
                    text: 'Nomor telepon harus terdiri dari 10-13 digit angka.',
                    confirmButtonColor: '#16a34a',
                    confirmButtonText: 'Mengerti'
                });
                return;
            }
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Format Email Salah',
                    text: 'Silakan masukkan alamat email yang valid.',
                    confirmButtonColor: '#16a34a',
                    confirmButtonText: 'Mengerti'
                });
                return;
            }
        });

        document.getElementById('phone').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        const fields = ['name', 'email', 'phone', 'roleSelect'];
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', function() {
                    validateField(this);
                });
            }
        });

        function validateField(field) {
            const value = field.value.trim();

            if (!value) {
                field.classList.add('ring-red-300');
                field.classList.remove('ring-gray-100');
            } else {
                field.classList.remove('ring-red-300');
                field.classList.add('ring-gray-100');

                if (field.id === 'email') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        field.classList.add('ring-red-300');
                    }
                }

                if (field.id === 'phone') {
                    const phoneRegex = /^[0-9]{10,13}$/;
                    if (!phoneRegex.test(value)) {
                        field.classList.add('ring-red-300');
                    }
                }
            }
        }
    </script>

    <style>
        .ring-red-300 {
            --tw-ring-color: rgb(252 165 165);
        }
    </style>
@endsection
