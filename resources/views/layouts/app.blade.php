<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIJA PARKING - @yield('title')</title>
    <!-- Gunakan Bootstrap dari CDN untuk kemudahan -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { background-color: #fff; min-height: 100vh; border-right: 1px solid #dee2e6; }
        .sidebar-brand { font-weight: bold; font-size: 1.2rem; padding: 20px; color: #333; }
        .nav-link { color: #555; padding: 10px 20px; font-weight: 500; }
        .nav-link.active { background-color: #f8f9fa; color: #c026d3; border-right: 4px solid #c026d3; }
        .nav-link i { margin-right: 10px; width: 20px; text-align: center; }
        .btn-purple { background-color: #c026d3; color: white; border: none; }
        .btn-purple:hover { background-color: #a21caf; color: white; }
        .content-header { padding: 20px 0; border-bottom: 1px solid #eee; margin-bottom: 20px; }
        .card { border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" style="width: 250px;">
            <div class="sidebar-brand">
                <i class="fa-solid fa-square-parking"></i> SIJA PARKING
            </div>
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('locations*') ? 'active' : '' }}" href="/locations">
                        <i class="fa-solid fa-map-location-dot"></i> Location
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('transactions*') ? 'active' : '' }}" href="/transactions">
                        <i class="fa-solid fa-money-bill-transfer"></i> Transaction
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('vehicle-types*') ? 'active' : '' }}" href="/vehicle-types">
                        <i class="fa-solid fa-car"></i> Vehicle Type
                    </a>
                </li>
                <li class="nav-item mt-4 px-3 text-muted" style="font-size: 0.8rem; font-weight: bold;">REPORTS</li>
                <li class="nav-item"><a class="nav-link" href="#"><i class="fa-regular fa-file-lines"></i> Location Report</a></li>
                <li class="nav-item"><a class="nav-link" href="#"><i class="fa-regular fa-file-lines"></i> Transaction Report</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <span class="text-muted">Pages / @yield('title')</span>
                    <h4 class="mb-0 fw-bold">@yield('title')</h4>
                </div>
                <div>
                    @yield('header_action')
                    <button class="btn btn-light ms-2"><i class="fa-solid fa-right-from-bracket"></i> Sign Out</button>
                </div>
            </div>

            @yield('content')

            <div class="text-center text-muted mt-5" style="font-size: 0.9rem;">
                © 2025, made with ❤️ by Coding Lover for ASAS Ganjil Web And Mobile Development - SMKN 1 Cibinong.
            </div>
        </div>
    </div>
</body>
</html>