<div class="flex items-center justify-center gap-2 flex-wrap">
    <!-- Detail Quick (Modal) Button -->
    <button type="button"
        class="detail-modal-btn inline-flex items-center gap-1 rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-semibold text-blue-700 transition-all duration-200 hover:bg-blue-100 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
        data-id="{{ $data->id }}" title="Lihat Quick Detail (Modal)">
        <span class="mdi mdi-information-outline text-base"></span>
        Quick
    </button>

    <!-- Detail Lengkap (Page) Button -->
    <a href="{{ route('products.show', $data->id) }}"
        class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1.5 text-xs font-semibold text-indigo-700 transition-all duration-200 hover:bg-indigo-100 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
        title="Lihat Detail Lengkap">
        <span class="mdi mdi-eye-outline text-base"></span>
        Detail
    </a>

    <!-- Edit Button -->
    @canany(['update_products'])
        <a href="{{ route('products.edit', $data->id) }}"
            class="inline-flex items-center gap-1 rounded-lg bg-yellow-50 px-2.5 py-1.5 text-xs font-semibold text-yellow-700 transition-all duration-200 hover:bg-yellow-100 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-1"
            title="Edit Produk">
            <span class="mdi mdi-pencil-outline text-base"></span>
            Edit
        </a>
    @endcanany

    <!-- Delete Button -->
    @canany(['delete_products'])
        <button type="button"
            onclick="Alert.confirm('Hapus Produk?', 'Produk {{ $data->name }} akan dihapus permanen', () => { const form = document.getElementById('delete-form-{{ $data->id }}'); if(form) form.submit(); })"
            class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-semibold text-red-700 transition-all duration-200 hover:bg-red-100 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
            title="Hapus Produk">
            <span class="mdi mdi-trash-can-outline text-base"></span>
            Hapus
        </button>

        <form action="{{ route('products.delete', $data->id) }}" method="POST" id="delete-form-{{ $data->id }}"
            class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endcanany
</div>
