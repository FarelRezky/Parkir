<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIJA PARKING - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            background-color: #f4f6fb;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
        }

        /* ─── SIDEBAR ─────────────────────────────────── */
        .sidebar {
            width: 240px;
            min-width: 240px;
            min-height: 100vh;
            background-color: #f4f6fb;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 8px;
            margin-bottom: 28px;
        }
        .sidebar-brand .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: transparent;
        }
        .sidebar-brand .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .sidebar-brand .brand-name {
            font-size: 1rem;
            font-weight: 800;
            color: #1e2a45;
            letter-spacing: 0.3px;
        }

        /* Nav */
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            flex: 1;
        }
        .sidebar-nav .nav-section-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ca3af;
            padding: 16px 10px 8px;
        }

        .sidebar-nav .nav-item { margin-bottom: 4px; }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.18s;
            position: relative;
        }
        .sidebar-nav .nav-link:hover {
            background: #fff;
            color: #374151;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        /* Nav icon box */
        .nav-icon-box {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            flex-shrink: 0;
            background: #e9ecef;
            color: #6b7280;
            transition: all 0.18s;
        }

        /* Active state */
        .sidebar-nav .nav-link.active {
            background: #fff;
            color: #1e2a45;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .sidebar-nav .nav-link.active .nav-icon-box {
            background: linear-gradient(135deg, #c026d3, #9333ea);
            color: #fff;
            box-shadow: 0 4px 10px rgba(192, 38, 211, 0.35);
        }
        .sidebar-nav .nav-link.active .nav-label {
            color: #1e2a45;
            font-weight: 700;
        }

        /* Active bar */
        .sidebar-nav .nav-link.active::after {
            content: '';
            position: absolute;
            right: -16px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: #c026d3;
            border-radius: 3px 0 0 3px;
        }

        /* ─── TOPBAR ──────────────────────────────────── */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 0 20px;
            margin-bottom: 24px;
            border-bottom: 1.5px solid #eef0f5;
        }
        .topbar-left {
            display: flex;
            align-items: center;
        }
        .topbar-left .breadcrumb-text {
            font-size: 0.75rem;
            color: #9ca3af;
            font-weight: 500;
            margin-bottom: 2px;
        }
        .topbar-left .breadcrumb-text a {
            color: #9ca3af;
            text-decoration: none;
        }
        .topbar-left .breadcrumb-text a:hover { color: #c026d3; }
        .topbar-left .page-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1e2a45;
            margin: 0;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Sign Out button */
        .btn-signout {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            color: #6b7280;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.18s;
            white-space: nowrap;
        }
        .btn-signout:hover {
            border-color: #c026d3;
            color: #c026d3;
            background: #fdf4ff;
        }

        /* ─── MAIN CONTENT ────────────────────────────── */
        .main-content {
            flex: 1;
            padding: 0 28px 28px 20px;
            min-width: 0;
            overflow: hidden;
        }

        /* ─── GLOBAL HELPERS ──────────────────────────── */
        .btn-purple { background-color: #c026d3; color: white; border: none; }
        .btn-purple:hover { background-color: #a21caf; color: white; }

        /* ─── FOOTER ──────────────────────────────────── */
        .layout-footer {
            text-align: center;
            font-size: 0.73rem;
            color: #c4c9d4;
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #eef0f5;
        }
        .layout-footer a {
            color: #c026d3;
            font-weight: 700;
            text-decoration: none;
        }

        /* ─── RESPONSIVE ──────────────────────────────── */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.4);
            z-index: 1030;
        }
        .sidebar-backdrop.show {
            display: block;
        }
        .mobile-toggle {
            display: none;
        }
        
        /* Utility for responsive tables without breaking layout */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -260px;
                top: 0;
                bottom: 0;
                z-index: 1040;
                transition: left 0.3s ease;
                box-shadow: 2px 0 12px rgba(0,0,0,0.1);
            }
            .sidebar.show {
                left: 0;
            }
            .main-content {
                padding: 0 16px 20px 16px;
                width: 100%;
            }
            .mobile-toggle {
                display: flex !important;
                align-items: center;
                justify-content: center;
                width: 38px;
                height: 38px;
                border: none;
                background: #fff;
                border-radius: 8px;
                color: #1e2a45;
                font-size: 1.1rem;
                margin-right: 12px;
                box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            }
            .topbar {
                padding: 16px 0;
            }
            .topbar-left .page-title {
                font-size: 1.1rem;
            }
            .btn-signout {
                padding: 8px 12px;
            }
            .btn-signout span {
                display: none;
            }
            .btn-signout i {
                margin: 0;
            }
            .topbar-right {
                gap: 8px;
            }
        }
    </style>
</head>
<body>
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>
<div class="d-flex">

    {{-- ─── SIDEBAR ─────────────────────────────────── --}}
    <div class="sidebar" id="sidebar">

        {{-- Brand --}}
        <div class="sidebar-brand">
            <div class="brand-icon">
                <img src="{{ asset('parkir.png') }}"
                     alt="SIJA Parking Logo"
                     onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fa-solid fa-square-parking\' style=\'color:#fff; font-size:1.4rem;\'></i>';">
            </div>
            <span class="brand-name">SIJA PARKING</span>
        </div>

        {{-- Navigation --}}
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('locations*') ? 'active' : '' }}" href="/locations">
                    <span class="nav-icon-box"><i class="fa-solid fa-map-location-dot"></i></span>
                    <span class="nav-label">Location</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('transactions*') ? 'active' : '' }}" href="/transactions">
                    <span class="nav-icon-box"><i class="fa-solid fa-money-bill-transfer"></i></span>
                    <span class="nav-label">Transaction</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vehicle-types*') ? 'active' : '' }}" href="/vehicle-types">
                    <span class="nav-icon-box"><i class="fa-solid fa-car"></i></span>
                    <span class="nav-label">Vehicle Type</span>
                </a>
            </li>

            {{-- Reports section --}}
            <li class="nav-section-label">Reports</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('reports/location*') ? 'active' : '' }}" href="/reports/location">
                    <span class="nav-icon-box"><i class="fa-regular fa-file-lines"></i></span>
                    <span class="nav-label">Location Report</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('reports/transaction*') ? 'active' : '' }}" href="/reports/transaction">
                    <span class="nav-icon-box"><i class="fa-solid fa-file-invoice"></i></span>
                    <span class="nav-label">Transaction Report</span>
                </a>
            </li>
        </ul>
    </div>

    {{-- ─── MAIN CONTENT ────────────────────────────── --}}
    <div class="flex-grow-1 main-content">

        {{-- Topbar --}}
        <div class="topbar">
            <div class="topbar-left">
                <button class="mobile-toggle" id="mobileToggle" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div>
                    <div class="breadcrumb-text">
                        <a href="#">Pages</a> / @yield('title')
                    </div>
                    <h4 class="page-title">@yield('title')</h4>
                </div>
            </div>
            <div class="topbar-right">
                @yield('header_action')
                <a href="/logout" class="btn-signout"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i> <span>Sign Out</span>
                </a>
                <form id="logout-form" action="/logout" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </div>

        {{-- Page Content --}}
        @yield('content')

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
        document.getElementById('sidebarBackdrop').classList.toggle('show');
    }
</script>
@stack('scripts')
</body>
</html>