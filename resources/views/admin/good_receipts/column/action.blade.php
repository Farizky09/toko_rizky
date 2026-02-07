<div class="flex items-center justify-center gap-2">
    {{-- Tombol Lihat (selalu tampil) --}}
    <a href="{{ route('good-receipts.show', $data->id) }}"
        class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors duration-200"
        title="Lihat Detail">
        <span class="mdi mdi-eye-outline text-sm"></span>
        Lihat
    </a>

    {{-- Status: DRAFT --}}
    @if ($data->status == 'draft')
        <a href="{{ route('good-receipts.edit', $data->id) }}"
            class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition-colors duration-200"
            title="Edit Good Receipt">
            <span class="mdi mdi-pencil-outline text-sm"></span>
            Edit
        </a>

        <button type="button" onclick="confirmDelete({{ $data->id }}, '{{ $data->gr_number }}')"
            class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors duration-200"
            title="Hapus Good Receipt">
            <span class="mdi mdi-trash-can-outline text-sm"></span>
            Hapus
        </button>

        <button type="button" onclick="confirmProcess({{ $data->id }}, '{{ $data->gr_number }}')"
            class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition-colors duration-200"
            title="Proses Good Receipt">
            <span class="mdi mdi-play-circle-outline text-sm"></span>
            Proses
        </button>
    @endif

    {{-- Status: PROCESS --}}
    @if ($data->status == 'process')
        <button type="button" onclick="confirmComplete({{ $data->id }}, '{{ $data->gr_number }}')"
            class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition-colors duration-200"
            title="Selesaikan Good Receipt">
            <span class="mdi mdi-check-circle-outline text-sm"></span>
            Selesai
        </button>

        <button type="button" onclick="confirmCancel({{ $data->id }}, '{{ $data->gr_number }}')"
            class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors duration-200"
            title="Batalkan Good Receipt">
            <span class="mdi mdi-close-circle-outline text-sm"></span>
            Batalkan
        </button>
    @endif

    {{-- Status: COMPLETED --}}
    @if ($data->status == 'completed')
        {{-- <a href="{{ route('good-receipts.print', $data->id) }}" target="_blank"
            class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors duration-200"
            title="Cetak Good Receipt">
            <span class="mdi mdi-printer-outline text-sm"></span>
            Cetak
        </a> --}}
    @endif

    {{-- Status: CANCELLED --}}
    @if ($data->status == 'cancelled')
        <button type="button" onclick="confirmDelete({{ $data->id }}, '{{ $data->gr_number }}')"
            class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors duration-200"
            title="Hapus Good Receipt Permanen">
            <span class="mdi mdi-trash-can-outline text-sm"></span>
            Hapus
        </button>
    @endif
</div>

{{-- Form tersembunyi untuk aksi-aksi --}}
<div class="hidden">
    @if ($data->status == 'draft')
        <form id="delete-form-{{ $data->id }}" action="{{ route('good-receipts.delete', $data->id) }}"
            method="POST">
            @csrf
            @method('DELETE')
        </form>
        <form id="process-form-{{ $data->id }}" action="{{ route('good-receipts.process', $data->id) }}"
            method="POST">
            @csrf
            @method('PUT')
        </form>
    @endif

    @if ($data->status == 'process')
        <form id="complete-form-{{ $data->id }}" action="{{ route('good-receipts.complete', $data->id) }}"
            method="POST">
            @csrf
            @method('PUT')
        </form>
        <form id="cancel-form-{{ $data->id }}" action="{{ route('good-receipts.cancel', $data->id) }}"
            method="POST">
            @csrf
            @method('PUT')
        </form>
    @endif

    @if ($data->status == 'cancelled')
        <form id="delete-form-{{ $data->id }}" action="{{ route('good-receipts.delete', $data->id) }}"
            method="POST">
            @csrf
            @method('DELETE')
        </form>
    @endif
</div>
