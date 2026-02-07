<div class="flex items-center justify-center gap-2">
    <a href="{{ route('purchases.show', $data->id) }}"
        class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors duration-200"
        title="Lihat Detail">
        <span class="mdi mdi-eye-outline text-sm"></span>
        Lihat
    </a>

    @if ($data->status == 'draft')
        <a href="{{ route('purchases.edit', $data->id) }}"
            class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition-colors duration-200"
            title="Edit Pembelian">
            <span class="mdi mdi-pencil-outline text-sm"></span>
            Edit
        </a>

        <button type="button" onclick="confirmCancel({{ $data->id }}, '{{ $data->purchase_number }}')"
            class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors duration-200"
            title="Batalkan Pembelian">
            <span class="mdi mdi-close-circle-outline text-sm"></span>
            Batalkan
        </button>

        <form action="{{ route('purchases.cancel', $data->id) }}" method="POST" id="cancel-form-{{ $data->id }}"
            class="hidden">
            @csrf
            @method('PUT')
        </form>
    @endif

    @if ($data->status == 'cancelled')
        <button type="button" onclick="confirmDestroy({{ $data->id }}, '{{ $data->purchase_number }}')"
            class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors duration-200"
            title="Hapus Pembelian Permanen">
            <span class="mdi mdi-trash-can-outline text-sm"></span>
            Hapus
        </button>

        <form action="{{ route('purchases.delete', $data->id) }}" method="POST" id="destroy-form-{{ $data->id }}"
            class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif

</div>
