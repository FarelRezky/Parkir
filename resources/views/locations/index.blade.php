@extends('layouts.app')

@section('title', 'Location')

@section('header_action')
    <div class="d-flex align-items-center gap-3">
        <div class="search-wrapper">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Type here...">
        </div>
        <a href="/locations/create" class="btn-add-location">
            <i class="fa-solid fa-plus me-1"></i> ADD NEW LOCATION
        </a>
    </div>
@endsection

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body, .content-area {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f4f6fb;
    }

    /* Search */
    .search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .search-icon {
        position: absolute;
        left: 12px;
        color: #9ca3af;
        font-size: 0.85rem;
        pointer-events: none;
    }
    .search-input {
        padding: 9px 16px 9px 36px;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.85rem;
        color: #374151;
        background: #fff;
        width: 220px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-input:focus {
        border-color: #c026d3;
        box-shadow: 0 0 0 3px rgba(192, 38, 211, 0.1);
    }
    .search-input::placeholder { color: #c4c9d4; }

    /* Add Button */
    .btn-add-location {
        background-color: #c026d3;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.82rem;
        padding: 9px 20px;
        border-radius: 10px;
        border: none;
        letter-spacing: 0.4px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 4px 14px rgba(192, 38, 211, 0.35);
        transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
        white-space: nowrap;
    }
    .btn-add-location:hover {
        background-color: #a21caf;
        color: #fff;
        box-shadow: 0 6px 18px rgba(192, 38, 211, 0.5);
        transform: translateY(-1px);
    }

    /* Alert */
    .alert-success {
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, #d1fae5, #ecfdf5);
        color: #065f46;
        font-weight: 600;
        font-size: 0.88rem;
        border-left: 4px solid #10b981;
        margin-bottom: 20px;
    }

    /* Table Card */
    .table-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 28px 28px 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        border: none;
    }
    .table-card-title {
        font-size: 1.15rem;
        font-weight: 400;
        color: #374151;
        margin-bottom: 24px;
    }
    .table-card-title span {
        color: #c026d3;
        font-weight: 800;
    }

    /* Table */
    .loc-table {
        width: 100%;
        border-collapse: collapse;
    }
    .loc-table thead tr {
        border-bottom: 2px solid #f3f4f6;
    }
    .loc-table thead th {
        font-size: 0.72rem;
        font-weight: 700;
        color: #c026d3;
        letter-spacing: 0.7px;
        text-transform: uppercase;
        padding: 0 16px 14px;
        text-align: left;
    }
    .loc-table thead th:first-child { width: 80px; }
    .loc-table thead th:not(:first-child):not(:nth-child(2)) {
        text-align: center;
    }

    .loc-table tbody tr {
        border-bottom: 1px solid #f9fafb;
        transition: background 0.15s;
    }
    .loc-table tbody tr:last-child { border-bottom: none; }
    .loc-table tbody tr:hover { background: #fdf4ff; }

    .loc-table tbody td {
        padding: 14px 16px;
        font-size: 0.88rem;
        color: #374151;
        text-align: left;
    }
    .loc-table tbody td:not(:first-child):not(:nth-child(2)) {
        text-align: center;
    }

    .td-no {
        font-weight: 600;
        color: #9ca3af;
        font-size: 0.82rem;
    }
    .td-name {
        font-weight: 700;
        color: #1e2a45;
    }
    .td-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f3f4f6;
        border-radius: 8px;
        padding: 5px 14px;
        font-weight: 700;
        font-size: 0.85rem;
        color: #374151;
        min-width: 52px;
        justify-content: center;
    }
    .td-badge.motorcycle { background: #ecfdf5; color: #065f46; }
    .td-badge.motorcycle i { color: #10b981; }
    .td-badge.car { background: #fef2f2; color: #991b1b; }
    .td-badge.car i { color: #ef4444; }
    .td-badge.other { background: #fff7ed; color: #9a3412; }
    .td-badge.other i { color: #f97316; }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: #9ca3af;
    }
    .empty-state i {
        font-size: 2.5rem;
        color: #e5d8f0;
        display: block;
        margin-bottom: 10px;
    }
    .empty-state p {
        font-size: 0.88rem;
        margin: 0;
    }
    .empty-state a {
        color: #c026d3;
        font-weight: 600;
        text-decoration: none;
    }

    /* Footer */
    .page-footer {
        text-align: center;
        font-size: 0.75rem;
        color: #adb5bd;
        margin-top: 28px;
        padding-top: 12px;
        border-top: 1px solid #f0f0f5;
    }
    .page-footer a {
        color: #c026d3;
        font-weight: 600;
        text-decoration: none;
    }
</style>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="table-card">
    <h5 class="table-card-title">Location <span>Data Table</span></h5>

    <div class="table-responsive">
        <table class="loc-table" id="locationTable">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th>LOCATION NAME</th>
                    <th>MAX MOTORCYCLE</th>
                    <th>MAX CAR</th>
                    <th>MAX TRUCK/BUS/OTHER</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $i => $location)
                    <tr>
                        <td class="td-no">{{ $i + 1 }}</td>
                        <td class="td-name">{{ $location->location_name }}</td>
                        <td>
                            <span class="td-badge motorcycle">
                                <i class="fa-solid fa-motorcycle"></i>
                                {{ $location->max_motorcycle }}
                            </span>
                        </td>
                        <td>
                            <span class="td-badge car">
                                <i class="fa-solid fa-car"></i>
                                {{ $location->max_car }}
                            </span>
                        </td>
                        <td>
                            <span class="td-badge other">
                                <i class="fa-solid fa-truck"></i>
                                {{ $location->max_other }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fa-solid fa-building-circle-xmark"></i>
                                <p>No location data available. <a href="/locations/create">Add New Location</a> to get started.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="page-footer">
    © 2025, made with ❤️ by <a href="#">Coding Lover</a> for ASAS Ganjil Web And Mobile Development - SMKN 1 Cibinong.
</div>

<script>
    // Live search filter
    document.getElementById('searchInput')?.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#locationTable tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
</script>
@endsection