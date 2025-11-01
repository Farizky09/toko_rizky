<div class="flex items-center justify-center gap-2">
    <!-- View Button -->
    <a href="{{ route('purchases.show', $data->id) }}"
        class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors duration-200"
        title="Lihat Detail">
        <span class="mdi mdi-eye-outline text-sm"></span>
        Lihat
    </a>

    <!-- Edit Button -->
    <a href="{{ route('purchases.edit', $data->id) }}"
        class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition-colors duration-200"
        title="Edit Pembelian">
        <span class="mdi mdi-pencil-outline text-sm"></span>
        Edit
    </a>

    <!-- Delete Button -->
    <button type="button" onclick="confirmDelete({{ $data->id }}, '{{ $data->purchase_number }}')"
        class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors duration-200"
        title="Hapus Pembelian">
        <span class="mdi mdi-trash-can-outline text-sm"></span>
        Hapus
    </button>

    <!-- Hidden Delete Form -->
    <form action="{{ route('purchases.delete', $data->id) }}" method="POST" id="delete-form-{{ $data->id }}"
        class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
    function confirmDelete(id, purchaseNumber) {
        Swal.fire({
            title: `Hapus Pembelian "${purchaseNumber}"?`,
            text: "Pembelian akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
