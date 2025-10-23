  @extends('layouts.master')

  @section('content')
      <main class="flex-1 overflow-y-auto bg-gray-50 p-6 lg:p-6">
          <div>
              <h1 class="text-3xl font-bold text-gray-800">Manajemen Ijin Penggunaan Fitur</h1>
              <p class="mt-1 text-gray-600">Halaman pengelolaan semua ijin penggunaan fitur</p>
          </div>

          <div class="mt-6 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">

              {{-- @canany(['create_permission'])
                  <a href="{{ route('permission.create') }}"
                      class="flex min-w-fit items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 ml-auto">
                      <span class="mdi mdi-plus-circle-outline text-lg"></span>
                      Tambah Permission
                  </a>
              @endcanany --}}

          </div>

          <div class="mt-4 overflow-hidden rounded-lg bg-white border border-b-[1px] p-4">
              <table id="adminTable" class="table dt-responsive nowrap m-1" style="width:100%">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Nama</th>
                          <th>Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
          </div>
      </main>
  @endsection

  @push('scripts')
      <script>
          @if (session('success'))
              Alert.success("{{ session('success') }}");
          @endif
      </script>
      <script>
          $(document).ready(function() {
              $('#adminTable').DataTable({
                  responsive: true,
                  ajax: {
                      url: '{{ route('permission.index') }}',
                      type: 'GET'
                  },
                  columns: [{
                          data: 'DT_RowIndex',
                          name: 'DT_RowIndex',
                      },
                      {
                          data: 'name',
                          name: 'name'
                      },
                      {
                          data: 'action',
                          name: 'action',
                          orderable: false,
                          searchable: false
                      }
                  ]
              });
          });
      </script>
  @endpush
