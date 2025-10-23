<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>

    <style>
        body {
            background: #f8fafc;
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: #fff;
            position: fixed !important;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background: #495057;
            color: #fff;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
        }

        .dataTables_paginate .pagination .active a {
            background-color: #16a34a !important;
            border-color: #16a34a !important;
            color: white !important;
        }

        .dataTables_paginate .pagination .page-link {
            /* background-color: #16a34a !important; */
            /* border-color: #16a34a !important; */
            color: black !important;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="container-fluid">
        <div class="flex">
            @include('components.sidebar')
            <main class="w-full md:ml-[320px]">
                @yield('content')
            </main>
        </div>
    </div>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/sweet-alert.js') }}"></script>
    @stack('scripts')
</body>

</html>
