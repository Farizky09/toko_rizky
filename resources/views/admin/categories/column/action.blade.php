  <div class="flex items-center gap-2">
      @canany(['update_categories'])
          <a href="{{ route('categories.edit', $data->id) }}"
              class="flex col-span-6 items-center gap-3 px-3 py-2 text-sm rounded-lg bg-yellow-200 text-gray-700 hover:bg-gray-100"
              role="menuitem" tabindex="-1">
              <span class="mdi mdi-pencil-outline text-yellow-600 "></span>
              Edit
          </a>
      @endcanany

      @canany(['delete_categories'])
          <form action="{{ route('categories.delete', $data->id) }}" method="POST" class="col-span-6 delete-form">
              @csrf
              @method('DELETE')
              <button type="button"
                  onclick="Alert.confirm('Yakin ingin menghapus hak akses ini?', 'Hapus Hak Akses', () => this.closest('form').submit())"
                  class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg bg-red-200 text-red-700 hover:bg-red-50"
                  role="menuitem" tabindex="-1">
                  <span class="mdi mdi-trash-can-outline"></span>
                  Hapus
              </button>
          </form>
      @endcanany

  </div>
