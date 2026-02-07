@extends('layouts.master')

@section('content')
    <form class="p-6" action="{{ route('role.update', $data->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="gap-x-6 gap-y-6 md:grid-cols-2">

            <div>
                <label for="Role" class="block text-sm font-medium leading-6 text-gray-900">Nama Role</label>
                <div class="mt-2">
                    <input type="text" id="Role" name="name" value="{{ $data->name }}"
                        class="block w-full rounded-md outline-none py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-100 shadow-sm placeholder:text-gray-400 !border-none focus:!ring-1 focus:!ring-inset focus:!ring-gray-300 sm:text-sm">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Permissions</label>
                <div class="grid gap-4 grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                    @foreach ($permissions as $permission)
                        @php
                            $selectedPermissions = old('permissions', $data->permissions->pluck('name')->toArray());
                            $isChecked = in_array($permission->name, $selectedPermissions);
                        @endphp
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="permissions[]" id="permission_{{ $permission->id }}"
                                value="{{ $permission->name }}" {{ $isChecked ? 'checked' : '' }}
                                class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                            <label for="permission_{{ $permission->id }}" class="text-sm text-gray-700">
                                {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                            </label>
                        </div>
                    @endforeach
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
@endsection
