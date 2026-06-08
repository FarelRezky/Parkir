@extends('layouts.app')

@section('title', 'Transaction Report')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body, .content-area { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f4f6fb; }

    .stat-card {
        background: #fff; border-radius: 14px; padding: 20px 22px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        border-left: 4px solid #c026d3;
        display: flex; align-items: center; gap: 16px;
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
        box-shadow: 0 4px 20px rgba(0,0,0,0.07); border: none; overflow: hidden;
    }
    .report-card .card-header-custom {
        padding: 18px 24px; border-bottom: 1.5px solid #f3f4f6;
        display: flex; justify-content: space-between; align-items: center; background: #fff;
    }
    .report-card .card-title { font-size: 1.05rem; font-weight: 800; color: #1e2a45; margin: 0; }

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
        font-size: 0.82rem; font-weight: 700; transition: background 0.2s;
    }
    .btn-filter:hover { background: #a21caf; color: #fff; }
    .btn-reset {
        background: #f3f4f6; color: #6b7280; border: none;
        border-radius: 10px; padding: 9px 16px;
        font-size: 0.82rem; font-weight: 600; transition: background 0.2s;
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
        padding: 12px 16px; font-size: 0.84rem;
        color: #374151; border-bottom: 1px solid #f9fafb; vertical-align: middle;
    }
    .table-custom tbody tr:hover { background: #fdf4ff; }
    .table-custom tbody tr:last-child td { border-bottom: none; }

    .badge-aktif   { background:#d1fae5; color:#065f46; font-size:0.72rem; font-weight:700; padding:3px 10px; border-radius:20px; }
    .badge-selesai { background:#ede9fe; color:#5b21b6; font-size:0.72rem; font-weight:700; padding:3px 10px; border-radius:20px; }
    .badge-motor   { background:#d1fae5; color:#065f46; font-size:0.72rem; font-weight:600; padding:2px 8px; border-radius:6px; }
    .badge-mobil   { background:#fee2e2; color:#991b1b; font-size:0.72rem; font-weight:600; padding:2px 8px; border-radius:6px; }
    .badge-other   { background:#fef3c7; color:#92400e; font-size:0.72rem; font-weight:600; padding:2px 8px; border-radius:6px; }

    .ticket-no { font-size:0.8rem; font-weight:700; color:#c026d3; }
    .plate-badge { background:#374151; color:#fff; font-size:0.72rem; font-weight:700; padding:2px 9px; border-radius:5px; }

    .pagination { justify-content: center; margin: 0; padding: 16px; }
    .page-link { color: #c026d3; border-color: #e5e7eb; border-radius: 8px !important; margin: 0 2px; font-size: 0.82rem; }
    .page-item.active .page-link { background: #c026d3; border-color: #c026d3; }

    .empty-state { text-align: center; padding: 48px 20px; color: #9ca3af; }
    .empty-state i { font-size: 2.5rem; margin-bottom: 12px; display: block; color: #e0d0e8; }

    .pdf-link { color: #ef4444; font-size: 1.2rem; transition: transform 0.15s; display: inline-block; }
    .pdf-link:hover { transform: scale(1.15); }
</style>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf4ff;">
                <i class="fa-solid fa-ticket" style="color:#c026d3;"></i>
            </div>
            <div>
                <div class="stat-value">{{ $totalMasuk }}</div>
                <div class="stat-label">Total Masuk</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#16a34a;">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="fa-solid fa-arrow-right-from-bracket" style="color:#16a34a;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#16a34a;">{{ $totalKeluar }}</div>
                <div class="stat-label">Sudah Keluar</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#f59e0b;">
            <div class="stat-icon" style="background:#fffbeb;">
                <i class="fa-solid fa-car" style="color:#f59e0b;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#f59e0b;">{{ $totalAktif }}</div>
                <div class="stat-label">Masih Parkir</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#2563eb;">
            <div class="stat-icon" style="background:#eff6ff;">
                <i class="fa-solid fa-money-bill-wave" style="color:#2563eb;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#2563eb; font-size:1.1rem;">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </div>
                <div class="stat-label">Total Pendapatan</div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER + TABLE --}}
<div class="report-card">
    <div class="card-header-custom">
        <h5 class="card-title"><i class="fa-solid fa-receipt me-2" style="color:#c026d3;"></i>Data Transaksi Parkir</h5>
        <span style="font-size:0.78rem; color:#9ca3af;">{{ $transactions->total() }} transaksi ditemukan</span>
    </div>

    {{-- Filter --}}
    <div class="p-3 border-bottom" style="background:#fafafa;">
        <form method="GET" action="{{ request()->url() }}" class="filter-form">
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <input type="text" name="search" class="form-control"
                       style="max-width:220px;"
                       placeholder="No tiket / plat..."
                       value="{{ request('search') }}">
                <input type="date" name="date_from" class="form-control"
                       style="max-width:160px;"
                       value="{{ request('date_from') }}"
                       title="Dari tanggal">
                <input type="date" name="date_to" class="form-control"
                       style="max-width:160px;"
                       value="{{ request('date_to') }}"
                       title="Sampai tanggal">
                <select name="status" class="form-select" style="max-width:160px;">
                    <option value="">Semua Status</option>
                    <option value="aktif"   {{ request('status') === 'aktif'   ? 'selected' : '' }}>Masih Parkir</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Sudah Keluar</option>
                </select>
                <button type="submit" class="btn-filter">
                    <i class="fa-solid fa-magnifying-glass me-1"></i>Cari
                </button>
                @if(request()->hasAny(['search', 'date_from', 'date_to', 'status']))
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
                    <th>Tiket</th>
                    <th>No Polisi</th>
                    <th>Lokasi</th>
                    <th>Jenis</th>
                    <th>Waktu Masuk</th>
                    <th>Waktu Keluar</th>
                    <th>Durasi</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $i => $t)
                <tr>
                    <td style="color:#9ca3af; font-size:0.75rem;">
                        {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $i + 1 }}
                    </td>
                    <td>
                        <span class="ticket-no">#{{ $t->no_tiket }}</span>
                    </td>
                    <td>
                        <span class="plate-badge">{{ $t->no_polisi ?? '-' }}</span>
                    </td>
                    <td style="font-weight:600; color:#1e2a45;">
                        {{ $t->location?->location_name ?? '-' }}
                    </td>
                    <td>
                        @php $jenis = strtolower($t->vehicleType?->jenis ?? ''); @endphp
                        @if(str_contains($jenis, 'motor'))
                            <span class="badge-motor"><i class="fa-solid fa-motorcycle me-1"></i>{{ $t->vehicleType?->jenis ?? '-' }}</span>
                        @elseif(str_contains($jenis, 'mobil') || str_contains($jenis, 'car'))
                            <span class="badge-mobil"><i class="fa-solid fa-car me-1"></i>{{ $t->vehicleType?->jenis ?? '-' }}</span>
                        @else
                            <span class="badge-other"><i class="fa-solid fa-truck me-1"></i>{{ $t->vehicleType?->jenis ?? '-' }}</span>
                        @endif
                    </td>
                    <td style="font-size:0.78rem; color:#6b7280;">
                        {{ $t->masuk ? \Carbon\Carbon::parse($t->masuk)->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td style="font-size:0.78rem; color:#6b7280;">
                        {{ $t->keluar ? \Carbon\Carbon::parse($t->keluar)->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td style="font-size:0.78rem; font-weight:600;">
                        {{ $t->total_jam ? $t->total_jam . ' Jam' : '-' }}
                    </td>
                    <td style="font-weight:700; color:#1e2a45;">
                        @if($t->total_bayar)
                            Rp {{ number_format($t->total_bayar, 0, ',', '.') }}
                        @else
                            <span style="color:#9ca3af;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($t->keluar)
                            <span class="badge-selesai"><i class="fa-solid fa-check me-1"></i>Selesai</span>
                        @else
                            <span class="badge-aktif"><i class="fa-solid fa-circle me-1" style="font-size:0.5rem;"></i>Aktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="/transactions/ticket/{{ $t->id }}" target="_blank" class="pdf-link" title="Lihat Tiket">
                            <i class="fa-solid fa-file-pdf"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11">
                        <div class="empty-state">
                            <i class="fa-solid fa-receipt"></i>
                            <div style="font-weight:600; color:#6b7280;">Tidak ada data transaksi</div>
                            <small>Coba ubah filter pencarian</small>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($transactions->hasPages())
    <div class="border-top">
        {{ $transactions->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<div style="text-align:center; font-size:0.75rem; color:#adb5bd; margin-top:24px; padding-top:12px; border-top:1px solid #f0f0f5;">
    © 2025, made with ❤️ by <a href="#" style="color:#c026d3; font-weight:600; text-decoration:none;">Coding Lover</a> for ASAS Ganjil Web And Mobile Development - SMKN 1 Cibinong.
</div>
@endsection