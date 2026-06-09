@extends('layouts.app')

@section('title', 'Vehicle Type')

@section('header_action')
    <div class="d-flex align-items-center gap-3">
        <div class="search-wrapper">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Type here...">
        </div>
        <a href="/vehicle-types/create" class="btn-add-vtype">
            <i class="fa-solid fa-plus me-1"></i> ADD NEW VEHICLE TYPE
        </a>
    </div>
@endsection

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body, .content-area {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f4f6fb;
    }

    /* Search */
    .search-wrapper { position: relative; display: flex; align-items: center; }
    .search-icon {
        position: absolute; left: 12px;
        color: #9ca3af; font-size: 0.85rem; pointer-events: none;
    }
    .search-input {
        padding: 9px 16px 9px 36px;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.85rem; color: #374151; background: #fff;
        width: 220px; outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-input:focus { border-color: #c026d3; box-shadow: 0 0 0 3px rgba(192,38,211,0.1); }
    .search-input::placeholder { color: #c4c9d4; }

    /* Add Button */
    .btn-add-vtype {
        background-color: #c026d3; color: #fff;
        font-weight: 700; font-size: 0.82rem;
        padding: 9px 20px; border-radius: 10px; border: none;
        text-decoration: none; display: inline-flex; align-items: center;
        box-shadow: 0 4px 14px rgba(192,38,211,0.35);
        transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
        white-space: nowrap; letter-spacing: 0.4px;
    }
    .btn-add-vtype:hover {
        background-color: #a21caf; color: #fff;
        box-shadow: 0 6px 18px rgba(192,38,211,0.5);
        transform: translateY(-1px);
    }

    /* Alert */
    .alert-success {
        border-radius: 12px; border: none;
        background: linear-gradient(135deg, #d1fae5, #ecfdf5);
        color: #065f46; font-weight: 600; font-size: 0.88rem;
        border-left: 4px solid #10b981; margin-bottom: 20px;
    }

    /* Table Card */
    .table-card {
        background: #fff; border-radius: 16px;
        padding: 28px 28px 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07); border: none;
    }
    .table-card-title {
        font-size: 1.15rem; font-weight: 400;
        color: #374151; margin-bottom: 24px;
    }
    .table-card-title span { color: #c026d3; font-weight: 800; }

    /* Table */
    .vt-table { width: 100%; border-collapse: collapse; }
    .vt-table thead tr { border-bottom: 2px solid #f3f4f6; }
    .vt-table thead th {
        font-size: 0.72rem; font-weight: 700;
        color: #c026d3; letter-spacing: 0.7px;
        text-transform: uppercase;
        padding: 0 16px 14px; text-align: left;
    }
    .vt-table thead th:not(:first-child):not(:nth-child(2)) { text-align: center; }
    .vt-table thead th:first-child { width: 70px; }

    .vt-table tbody tr {
        border-bottom: 1px solid #f9fafb;
        transition: background 0.15s;
    }
    .vt-table tbody tr:last-child { border-bottom: none; }
    .vt-table tbody tr:hover { background: #fdf4ff; }

    .vt-table tbody td {
        padding: 14px 16px; font-size: 0.88rem;
        color: #374151; text-align: left;
    }
    .vt-table tbody td:not(:first-child):not(:nth-child(2)) { text-align: center; }

    .td-no { font-weight: 600; color: #9ca3af; font-size: 0.82rem; }

    /* Vehicle type badge */
    .vtype-badge {
        display: inline-flex; align-items: center; gap: 8px;
        border-radius: 10px; padding: 6px 14px;
        font-weight: 700; font-size: 0.82rem; text-transform: capitalize;
    }
    .vtype-badge.motorcycle { background: #ecfdf5; color: #065f46; }
    .vtype-badge.motorcycle i { color: #10b981; }
    .vtype-badge.car { background: #fef2f2; color: #991b1b; }
    .vtype-badge.car i { color: #ef4444; }
    .vtype-badge.other { background: #fff7ed; color: #9a3412; }
    .vtype-badge.other i { color: #f97316; }

    /* Price cell */
    .price-cell {
        display: inline-flex; align-items: center; gap: 6px;
        background: #f9fafb; border-radius: 8px;
        padding: 5px 12px; font-weight: 700; color: #374151;
        font-size: 0.85rem;
    }
    .price-cell .currency { font-size: 0.7rem; font-weight: 600; color: #9ca3af; }

    /* Max day badge */
    .max-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: #fdf4ff; border-radius: 8px;
        padding: 5px 12px; font-weight: 700;
        color: #a21caf; font-size: 0.85rem;
    }
    .max-badge .currency { font-size: 0.7rem; font-weight: 600; color: #c026d3; }

    /* Empty state */
    .empty-state { text-align: center; padding: 48px 20px; color: #9ca3af; }
    .empty-state i { font-size: 2.5rem; color: #e5d8f0; display: block; margin-bottom: 10px; }
    .empty-state a { color: #c026d3; font-weight: 600; text-decoration: none; }

    /* Footer */
    .page-footer {
        text-align: center; font-size: 0.75rem; color: #adb5bd;
        margin-top: 28px; padding-top: 12px; border-top: 1px solid #f0f0f5;
    }
    .page-footer a { color: #c026d3; font-weight: 600; text-decoration: none; }
</style>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#c026d3'
            });
        });
    </script>
@endif

<div class="table-card">
    <h5 class="table-card-title">Vehicle Type <span>Data Table</span></h5>

    <div class="table-responsive">
        <table class="vt-table" id="vtypeTable">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th>VEHICLE TYPE</th>
                    <th>FIRST HOUR CHARGES</th>
                    <th>NEXT HOURLY CHARGES</th>
                    <th>MAX COST PER DAY</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicleTypes as $i => $vt)
                    <tr>
                        <td class="td-no">{{ $i + 1 }}</td>
                        <td>
                            @php
                                $jenis = strtolower($vt->jenis);
                                $icon = $jenis === 'motorcycle' ? 'fa-motorcycle' : ($jenis === 'car' ? 'fa-car' : 'fa-truck');
                            @endphp
                            <span class="vtype-badge {{ $jenis }}">
                                <i class="fa-solid {{ $icon }}"></i>
                                {{ ucfirst($vt->jenis) }}
                            </span>
                        </td>
                        <td>
                            <span class="price-cell">
                                <span class="currency">Rp</span>
                                {{ number_format($vt->perjam_pertama, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <span class="price-cell">
                                <span class="currency">Rp</span>
                                {{ number_format($vt->perjam_berikutnya, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <span class="max-badge">
                                <span class="currency">Rp</span>
                                {{ number_format($vt->max_perhari, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fa-solid fa-car-burst"></i>
                                <p>No vehicle type data available. <a href="/vehicle-types/create">Add New Vehicle Type</a> to get started.</p>
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
    document.getElementById('searchInput')?.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('#vtypeTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
        });
    });
</script>
@endsection