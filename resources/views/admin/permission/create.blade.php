@extends('layouts.master')

@section('content')
    <form class="p-6" action="{{ route('permission.store') }}" method="POST">
        @csrf
        <div class="gap-x-6 gap-y-6 md:grid-cols-2">

            <div>
                <label for="permission" class="block text-sm font-medium leading-6 text-gray-900">Nama Ijin</label>
                <div class="mt-2">
                    <input type="text" id="permission" name="name" placeholder="cth: create_user"
                        class="block w-full rounded-md outline-none py-2 px-3 text-gray-900 ring-1 ring-inset ring-gray-100 shadow-sm placeholder:text-gray-400 !border-none focus:!ring-1 focus:!ring-inset focus:!ring-gray-300 sm:text-sm"
                        required>
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
@endsection
