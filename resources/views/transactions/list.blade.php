@extends('layouts.app')

@section('title', 'All Transactions')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body, .content-area { font-family: 'Plus Jakarta Sans', sans-serif; }

    .list-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px 28px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        border: none;
    }
    .list-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #1e2a45;
        margin-bottom: 20px;
    }
    .list-title span { color: #c026d3; }

    /* Search & Filter Bar */
    .toolbar-row {
        display: flex; align-items: center; gap: 12px;
        flex-wrap: wrap; margin-bottom: 18px;
    }
    .search-wrap {
        position: relative; flex: 1; min-width: 200px;
    }
    .search-wrap i {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-size: 0.82rem; pointer-events: none;
    }
    .search-input {
        width: 100%; padding: 9px 12px 9px 34px;
        border: 1.5px solid #e5e7eb; border-radius: 10px;
        font-size: 0.82rem; font-family: 'Plus Jakarta Sans', sans-serif;
        color: #1e2a45; background: #f9fafb;
        transition: border-color 0.2s, box-shadow 0.2s; outline: none;
    }
    .search-input:focus {
        border-color: #c026d3; box-shadow: 0 0 0 3px rgba(192,38,211,0.1); background: #fff;
    }
    .filter-select {
        padding: 9px 12px; border: 1.5px solid #e5e7eb; border-radius: 10px;
        font-size: 0.8rem; font-family: 'Plus Jakarta Sans', sans-serif;
        color: #374151; background: #f9fafb; cursor: pointer;
        transition: border-color 0.2s; outline: none; min-width: 140px;
    }
    .filter-select:focus { border-color: #c026d3; }
    .result-count {
        font-size: 0.75rem; font-weight: 600; color: #9ca3af;
        white-space: nowrap; padding: 7px 14px;
        background: #f9fafb; border-radius: 8px;
    }
    .result-count span { color: #c026d3; font-weight: 800; }

    /* Table */
    .tx-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 6px;
        font-size: 0.82rem;
    }
    .tx-table thead tr th {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #c026d3;
        padding: 10px 14px;
        background: #fdf4ff;
        border: none;
        white-space: nowrap;
    }
    .tx-table thead tr th:first-child { border-radius: 10px 0 0 10px; }
    .tx-table thead tr th:last-child  { border-radius: 0 10px 10px 0; }

    .tx-table tbody tr td {
        padding: 12px 14px;
        background: #f9fafb;
        border: none;
        color: #374151;
        font-weight: 500;
        vertical-align: middle;
        white-space: nowrap;
    }
    .tx-table tbody tr td:first-child { border-radius: 10px 0 0 10px; }
    .tx-table tbody tr td:last-child  { border-radius: 0 10px 10px 0; }
    .tx-table tbody tr:hover td { background: #fdf4ff; }
    .tx-row-hidden { display: none !important; }

    .td-no    { color: #9ca3af; font-weight: 600; text-align: center; width: 40px; }
    .td-tiket { color: #c026d3; font-weight: 700; }
    .td-plat  {
        font-size: 0.75rem; font-weight: 700;
        background: #6b7280; color: #fff;
        border-radius: 5px; padding: 3px 8px;
        display: inline-block;
    }

    /* Badge status */
    .badge-aktif {
        display: inline-flex; align-items: center; gap: 5px;
        background: #dcfce7; color: #16a34a;
        font-size: 0.72rem; font-weight: 700;
        padding: 4px 10px; border-radius: 20px;
        white-space: nowrap;
    }
    .badge-aktif::before {
        content: ''; width: 7px; height: 7px;
        background: #16a34a; border-radius: 50%; display: inline-block;
        animation: pulse-dot 1.4s infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.5; transform: scale(0.8); }
    }
    .badge-selesai {
        display: inline-flex; align-items: center; gap: 5px;
        background: #ede9fe; color: #7c3aed;
        font-size: 0.72rem; font-weight: 700;
        padding: 4px 10px; border-radius: 20px;
        white-space: nowrap;
    }
    .badge-selesai i { font-size: 0.65rem; }

    /* PDF icon button */
    .pdf-btn {
        color: #ef4444;
        font-size: 1.4rem;
        line-height: 1;
        text-decoration: none;
        transition: color 0.15s, transform 0.15s;
        display: inline-block;
    }
    .pdf-btn:hover { color: #b91c1c; transform: scale(1.18); }

    .td-cost { font-weight: 700; color: #1e2a45; }
    .td-cost.empty { color: #9ca3af; font-weight: 400; }

    /* Empty state */
    .empty-state {
        text-align: center; padding: 40px 0;
        color: #9ca3af; font-size: 0.85rem;
    }
    .empty-state i { font-size: 2.5rem; color: #e0d0e8; display: block; margin-bottom: 10px; }

    /* Scrollable wrapper */
    .table-wrapper {
        overflow-x: auto;
        scrollbar-width: thin;
        scrollbar-color: #c026d3 #f0e8f5;
    }
    .table-wrapper::-webkit-scrollbar { height: 4px; }
    .table-wrapper::-webkit-scrollbar-thumb { background: #c026d3; border-radius: 4px; }

    /* No results */
    .no-results-row td {
        text-align: center; padding: 30px; color: #9ca3af;
        font-size: 0.85rem; background: transparent !important;
    }
    .no-results-row { display: none; }
    .no-results-row.show { display: table-row !important; }
</style>

<div class="list-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="list-title mb-0">Transaction <span>Data Table</span></h5>
        <a href="{{ route('transactions.index') }}" class="btn btn-sm"
           style="background:#1e2a45;color:#fff;border-radius:8px;font-size:0.78rem;font-weight:700;padding:7px 16px;">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>
    </div>

    {{-- Search & Filter --}}
    <div class="toolbar-row">
        <div class="search-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" class="search-input" id="searchInput"
                   placeholder="Cari tiket, plat, lokasi..."
                   oninput="filterTable()">
        </div>
        <select class="filter-select" id="statusFilter" onchange="filterTable()">
            <option value="">Semua Status</option>
            <option value="aktif">Aktif</option>
            <option value="selesai">Selesai</option>
        </select>
        <select class="filter-select" id="vehicleFilter" onchange="filterTable()">
            <option value="">Semua Kendaraan</option>
            @if(isset($vehicleTypes))
                @foreach($vehicleTypes as $vt)
                <option value="{{ strtolower($vt->jenis) }}">{{ ucfirst($vt->jenis) }}</option>
                @endforeach
            @else
                @foreach($transactions->pluck('vehicleType.jenis')->filter()->unique() as $jenis)
                <option value="{{ strtolower($jenis) }}">{{ ucfirst($jenis) }}</option>
                @endforeach
            @endif
        </select>
        <div class="result-count">
            Menampilkan <span id="visibleCount">{{ $transactions->count() }}</span> transaksi
        </div>
    </div>

    <div class="table-wrapper">
        <table class="tx-table">
            <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th>TICKET NUMBER</th>
                    <th>POLICE NUM</th>
                    <th>LOCATION</th>
                    <th>VEHICLE TYPE</th>
                    <th>TIME IN</th>
                    <th>TIME OUT</th>
                    <th class="text-center">DURATION</th>
                    <th>TOTAL COST</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center">PDF</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($transactions as $index => $t)
                <tr class="tx-data-row"
                    data-tiket="{{ strtolower($t->no_tiket) }}"
                    data-polisi="{{ strtolower($t->no_polisi ?? '') }}"
                    data-lokasi="{{ strtolower($t->location->location_name ?? '') }}"
                    data-jenis="{{ strtolower($t->vehicleType->jenis ?? '') }}"
                    data-status="{{ $t->keluar ? 'selesai' : 'aktif' }}">

                    <td class="td-no">{{ $index + 1 }}</td>

                    <td class="td-tiket">{{ $t->no_tiket }}</td>

                    <td>
                        @if($t->no_polisi)
                            <span class="td-plat">{{ $t->no_polisi }}</span>
                        @else
                            <span style="color:#9ca3af;">-</span>
                        @endif
                    </td>

                    <td>{{ $t->location->location_name ?? '-' }}</td>

                    <td>{{ ucfirst($t->vehicleType->jenis ?? '-') }}</td>

                    <td style="color:#6b7280;">
                        <i class="fa-regular fa-clock me-1" style="color:#c026d3;"></i>
                        {{ \Carbon\Carbon::parse($t->masuk)->format('Y-m-d H:i') }}
                    </td>

                    <td style="color:#6b7280;">
                        @if($t->keluar)
                            <i class="fa-regular fa-clock me-1" style="color:#7c3aed;"></i>
                            {{ \Carbon\Carbon::parse($t->keluar)->format('Y-m-d H:i') }}
                        @else
                            <span style="color:#9ca3af;font-style:italic;">Masih Parkir</span>
                        @endif
                    </td>

                    <td class="text-center">
                        @if($t->total_jam)
                            <span style="font-weight:600;color:#374151;">{{ $t->total_jam }} mnt</span>
                        @else
                            <span style="color:#9ca3af;">-</span>
                        @endif
                    </td>

                    <td class="{{ $t->total_bayar ? 'td-cost' : 'td-cost empty' }}">
                        {{ $t->total_bayar ? 'Rp '.number_format($t->total_bayar,0,',','.') : '-' }}
                    </td>

                    <td class="text-center">
                        @if($t->keluar)
                            <span class="badge-selesai">
                                <i class="fa-solid fa-check"></i> Selesai
                            </span>
                        @else
                            <span class="badge-aktif">Aktif</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <a href="{{ route('transactions.ticket.view', $t->id) }}"
       target="_blank"
       class="pdf-btn"
       title="Lihat / Cetak Tiket PDF"
       style="background-image: none !important; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none;">
       
        {{-- SVG Ikon PDF Warna Biru Gelap (Persis Gambar 2) --}}
        <svg width="22" height="22" viewBox="0 0 384 512" fill="#3B4B5B" xmlns="http://www.w3.org/2000/svg">
            <path d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM112 256H144c26.5 0 48 21.5 48 48s-21.5 48-48 48H112v48c0 8.8-7.2 16-16 16s-16-7.2-16-16V272c0-8.8 7.2-16 16-16zm32 64c8.8 0 16-7.2 16-16s-7.2-16-16-16H112v32H144zm144-64H320c8.8 0 16 7.2 16 16s-7.2 16-16 16-16 7.2-16 16v32c0 8.8-7.2 16-16 16s-16-7.2-16-16V272c0-8.8 7.2-16 16-16zm32 64c-8.8 0-16 7.2-16 16s7.2 16 16 16 16-7.2 16-16-7.2-16-16-16zm-144-64H240c26.5 0 48 21.5 48 48v32c0 26.5-21.5 48-48 48H176v-16c0-8.8 7.2-16 16-16H208c8.8 0 16-7.2 16-16V304c0-8.8-7.2-16-16-16H176c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/>
        </svg>

        {{-- Teks "PDF" di sebelah ikon --}}
        <span style="color: #3B4B5B; font-weight: 700; font-family: 'Segoe UI', Arial, sans-serif; font-size: 15px; letter-spacing: 0.5px;">PDF</span>

    </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11">
                        <div class="empty-state">
                            <i class="fa-solid fa-ticket"></i>
                            Belum ada transaksi
                        </div>
                    </td>
                </tr>
                @endforelse

                {{-- No results row (shown when filter returns nothing) --}}
                <tr class="no-results-row" id="noResultsRow">
                    <td colspan="11">
                        <i class="fa-solid fa-magnifying-glass me-2" style="color:#e0d0e8;"></i>
                        Tidak ada data yang cocok
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function filterTable() {
        const search  = document.getElementById('searchInput').value.toLowerCase().trim();
        const status  = document.getElementById('statusFilter').value;
        const vehicle = document.getElementById('vehicleFilter').value.toLowerCase();
        const rows    = document.querySelectorAll('.tx-data-row');
        const noResultsRow = document.getElementById('noResultsRow');

        let visible = 0;
        rows.forEach((row) => {
            const tiket  = row.dataset.tiket  || '';
            const polisi = row.dataset.polisi || '';
            const lokasi = row.dataset.lokasi || '';
            const jenis  = row.dataset.jenis  || '';
            const stat   = row.dataset.status || '';

            const matchSearch  = !search  || tiket.includes(search) || polisi.includes(search) || lokasi.includes(search);
            const matchStatus  = !status  || stat === status;
            const matchVehicle = !vehicle || jenis.includes(vehicle);

            const show = matchSearch && matchStatus && matchVehicle;
            row.classList.toggle('tx-row-hidden', !show);

            if (show) {
                visible++;
                const tdNo = row.querySelector('.td-no');
                if (tdNo) tdNo.textContent = visible;
            }
        });

        document.getElementById('visibleCount').textContent = visible;
        noResultsRow.classList.toggle('show', visible === 0 && rows.length > 0);
    }
</script>
@endsection