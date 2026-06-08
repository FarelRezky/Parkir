@extends('layouts.app')

@section('title', 'Location Report')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body, .content-area { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f4f6fb; }

    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 20px 22px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        border-left: 4px solid #c026d3;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .stat-card .stat-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0;
    }
    .stat-card .stat-value { font-size: 1.6rem; font-weight: 800; color: #1e2a45; line-height: 1; }
    .stat-card .stat-label { font-size: 0.75rem; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; margin-top: 3px; }

    .report-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07); border: none;
        overflow: hidden;
    }
    .report-card .card-header-custom {
        padding: 18px 24px;
        border-bottom: 1.5px solid #f3f4f6;
        display: flex; justify-content: space-between; align-items: center;
        background: #fff;
    }
    .report-card .card-title {
        font-size: 1.05rem; font-weight: 800; color: #1e2a45; margin: 0;
    }

    .filter-form .form-control, .filter-form .form-select {
        border: 1.5px solid #e5e7eb; border-radius: 10px;
        font-size: 0.85rem; padding: 9px 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .filter-form .form-control:focus, .filter-form .form-select:focus {
        border-color: #c026d3; box-shadow: 0 0 0 3px rgba(192,38,211,0.1);
    }
    .btn-filter {
        background: #c026d3; color: #fff; border: none;
        border-radius: 10px; padding: 9px 20px;
        font-size: 0.82rem; font-weight: 700;
        transition: background 0.2s;
    }
    .btn-filter:hover { background: #a21caf; color: #fff; }
    .btn-reset {
        background: #f3f4f6; color: #6b7280; border: none;
        border-radius: 10px; padding: 9px 16px;
        font-size: 0.82rem; font-weight: 600;
        transition: background 0.2s;
    }
    .btn-reset:hover { background: #e5e7eb; color: #374151; }

    .table-custom { margin: 0; }
    .table-custom thead th {
        background: #f9fafb; font-size: 0.72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.5px;
        color: #6b7280; padding: 12px 16px;
        border-bottom: 1.5px solid #f0f0f5; white-space: nowrap;
    }
    .table-custom tbody td {
        padding: 13px 16px; font-size: 0.85rem;
        color: #374151; border-bottom: 1px solid #f9fafb;
        vertical-align: middle;
    }
    .table-custom tbody tr:hover { background: #fdf4ff; }
    .table-custom tbody tr:last-child td { border-bottom: none; }

    .capacity-bar {
        height: 6px; border-radius: 3px; background: #f0f0f5;
        overflow: hidden; min-width: 80px;
    }
    .capacity-bar-fill { height: 100%; border-radius: 3px; background: #c026d3; }

    .badge-motor  { background: #d1fae5; color: #065f46; font-size: 0.75rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
    .badge-mobil  { background: #fee2e2; color: #991b1b; font-size: 0.75rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
    .badge-other  { background: #fef3c7; color: #92400e; font-size: 0.75rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; }

    .pagination { justify-content: center; margin: 0; padding: 16px; }
    .page-link { color: #c026d3; border-color: #e5e7eb; border-radius: 8px !important; margin: 0 2px; font-size: 0.82rem; }
    .page-item.active .page-link { background: #c026d3; border-color: #c026d3; }

    .empty-state { text-align: center; padding: 48px 20px; color: #9ca3af; }
    .empty-state i { font-size: 2.5rem; margin-bottom: 12px; display: block; color: #e0d0e8; }
</style>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf4ff;">
                <i class="fa-solid fa-building" style="color:#c026d3;"></i>
            </div>
            <div>
                <div class="stat-value">{{ $totalLokasi }}</div>
                <div class="stat-label">Total Lokasi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#16a34a;">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="fa-solid fa-motorcycle" style="color:#16a34a;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#16a34a;">{{ $totalMaxMotor }}</div>
                <div class="stat-label">Kapasitas Motor</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#dc2626;">
            <div class="stat-icon" style="background:#fef2f2;">
                <i class="fa-solid fa-car" style="color:#dc2626;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#dc2626;">{{ $totalMaxMobil }}</div>
                <div class="stat-label">Kapasitas Mobil</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#d97706;">
            <div class="stat-icon" style="background:#fffbeb;">
                <i class="fa-solid fa-truck" style="color:#d97706;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#d97706;">{{ $totalKapasitas }}</div>
                <div class="stat-label">Total Kapasitas</div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER + TABLE --}}
<div class="report-card">
    <div class="card-header-custom">
        <h5 class="card-title"><i class="fa-solid fa-building me-2" style="color:#c026d3;"></i>Data Lokasi Parkir</h5>
        <span style="font-size:0.78rem; color:#9ca3af;">{{ $locations->total() }} lokasi ditemukan</span>
    </div>

    {{-- Filter --}}
    <div class="p-3 border-bottom" style="background:#fafafa;">
        <form method="GET" action="{{ request()->url() }}" class="filter-form">
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <input type="text" name="search" class="form-control"
                       style="max-width:260px;"
                       placeholder="Cari nama lokasi..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn-filter">
                    <i class="fa-solid fa-magnifying-glass me-1"></i>Cari
                </button>
                @if(request()->hasAny(['search']))
                <a href="{{ request()->url() }}" class="btn-reset">
                    <i class="fa-solid fa-xmark me-1"></i>Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lokasi</th>
                    <th>Kapasitas Motor</th>
                    <th>Kapasitas Mobil</th>
                    <th>Kapasitas Lainnya</th>
                    <th>Total Kapasitas</th>
                    <th>Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $i => $loc)
                <tr>
                    <td style="color:#9ca3af; font-size:0.78rem;">
                        {{ ($locations->currentPage() - 1) * $locations->perPage() + $i + 1 }}
                    </td>
                    <td>
                        <div style="font-weight:700; color:#1e2a45;">{{ $loc->location_name }}</div>
                    </td>
                    <td>
                        <span class="badge-motor">
                            <i class="fa-solid fa-motorcycle me-1"></i>{{ $loc->max_motorcycle }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-mobil">
                            <i class="fa-solid fa-car me-1"></i>{{ $loc->max_car }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-other">
                            <i class="fa-solid fa-truck me-1"></i>{{ $loc->max_other }}
                        </span>
                    </td>
                    <td>
                        @php $total = $loc->max_motorcycle + $loc->max_car + $loc->max_other; @endphp
                        <span style="font-weight:700; color:#c026d3;">{{ $total }}</span>
                    </td>
                    <td style="color:#9ca3af; font-size:0.78rem;">
                        {{ $loc->created_at ? $loc->created_at->format('d M Y') : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fa-solid fa-building"></i>
                            <div style="font-weight:600; color:#6b7280;">Tidak ada data lokasi</div>
                            <small>Coba ubah kata kunci pencarian</small>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($locations->hasPages())
    <div class="border-top">
        {{ $locations->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<div style="text-align:center; font-size:0.75rem; color:#adb5bd; margin-top:24px; padding-top:12px; border-top:1px solid #f0f0f5;">
    © 2025, made with ❤️ by <a href="#" style="color:#c026d3; font-weight:600; text-decoration:none;">Coding Lover</a> for ASAS Ganjil Web And Mobile Development - SMKN 1 Cibinong.
</div>
@endsection