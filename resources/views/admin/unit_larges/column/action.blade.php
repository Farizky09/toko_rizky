<div class="flex items-center justify-center gap-2">
    @canany(['update_unit_larges'])
        <a href="{{ route('unit_larges.edit', $data->id) }}"
            class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition-colors duration-200"
            title="Edit Satuan Besar">
            <span class="mdi mdi-pencil-outline"></span>
            Edit
        </a>
    @endcanany

    @canany(['delete_unit_larges'])
        <button type="button"
            onclick="Alert.confirm('Hapus Satuan Besar?', 'Satuan besar {{ $data->name }} akan dihapus permanen', () => { const form = document.getElementById('delete-form-{{ $data->id }}'); if(form) form.submit(); })"
            class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors duration-200"
            title="Hapus Satuan Besar">
            <span class="mdi mdi-trash-can-outline"></span>
            Hapus
        </button>

        <form action="{{ route('unit_larges.delete', $data->id) }}" method="POST" id="delete-form-{{ $data->id }}"
            class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endcanany
</div>
