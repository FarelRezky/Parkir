@extends('layouts.app')

@section('title', 'Transaction')

@section('header_action')
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-vehicle-type" id="btn-motorcycle" onclick="selectVehicleType('motorcycle', this)">
            <i class="fa-solid fa-motorcycle me-1"></i> MOTORCYCLE
        </button>
        <button class="btn btn-vehicle-type" id="btn-car" onclick="selectVehicleType('car', this)">
            <i class="fa-solid fa-car me-1"></i> CAR
        </button>
        <button class="btn btn-vehicle-type me-1" id="btn-other" onclick="selectVehicleType('other', this)">
            <i class="fa-solid fa-truck me-1"></i> OTHER
        </button>
        <button class="btn btn-enter-vehicle" onclick="submitEnterForm()">
            <i class="fa-solid fa-plus me-1"></i> ENTER VEHICLE
        </button>
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
    .btn-vehicle-type {
        background-color: #1e2a45; color: #ffffff; font-weight: 700;
        font-size: 0.78rem; padding: 8px 16px; border-radius: 8px; border: none;
        letter-spacing: 0.5px; transition: background 0.2s, transform 0.1s;
    }
    .btn-vehicle-type:hover { background-color: #2d3e60; color: #fff; transform: translateY(-1px); }
    .btn-vehicle-type.active-type {
        background-color: #c026d3; color: #fff;
        box-shadow: 0 4px 14px rgba(192, 38, 211, 0.4);
    }
    .btn-enter-vehicle {
        background-color: #c026d3; color: #ffffff; font-weight: 700;
        font-size: 0.78rem; padding: 8px 18px; border-radius: 8px; border: none;
        letter-spacing: 0.5px; box-shadow: 0 4px 14px rgba(192, 38, 211, 0.4);
        transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
    }
    .btn-enter-vehicle:hover {
        background-color: #a21caf; color: #fff;
        box-shadow: 0 6px 18px rgba(192, 38, 211, 0.55); transform: translateY(-1px);
    }

    /* ── Clock Card ── */
    .clock-card {
        position: relative; border-radius: 16px; color: #fff; padding: 18px 16px;
        text-align: center; height: 100%; box-shadow: 0 8px 24px rgba(30, 60, 114, 0.35);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        background-image: url('/assets/img/curved-images/curved-11.jpg');
        background-size: cover; background-position: center; background-repeat: no-repeat; overflow: hidden;
    }
    .clock-card::before { content: ''; position: absolute; inset: 0; background: rgba(0,0,0,0.42); z-index: 0; }
    .clock-card > * { position: relative; z-index: 1; }
    .clock-card .clock-logo {
        width: 52px; height: 52px; object-fit: contain; border-radius: 10px; margin-bottom: 8px;
        filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4));
    }
    .clock-card .day-name  { font-size: 1.05rem; font-weight: 600; margin-bottom: 2px; opacity: 0.95; }
    .clock-card .date-full { font-size: 0.75rem; opacity: 0.85; letter-spacing: 0.3px; }
    .clock-card .clock-time {
        font-size: 1.22rem; font-weight: 800; margin-top: 12px;
        letter-spacing: 2px; font-variant-numeric: tabular-nums; white-space: nowrap;
    }
    .clock-card .clock-label { font-size: 0.58rem; letter-spacing: 0.15em; opacity: 0.7; margin-top: 4px; }
    .clock-card .clock-tz   { font-size: 0.6rem; opacity: 0.65; margin-top: 3px; letter-spacing: 0.08em; }

    /* ── Location Card ── */
    .location-card {
        border: 2px solid #e5e7eb; border-radius: 16px; padding: 16px 14px 12px;
        text-align: center; background: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
        cursor: pointer; user-select: none;
        flex: 0 0 calc((100% - 28px) / 3); 
        box-sizing: border-box;
    }
    .location-card:hover {
        box-shadow: 0 8px 24px rgba(192,38,211,0.15); transform: translateY(-2px); border-color: #c026d3;
    }
    .location-card.selected {
        border-color: #c026d3; box-shadow: 0 8px 28px rgba(192,38,211,0.28);
        background: #fdf4ff; transform: translateY(-3px); animation: pulse-border 0.6s ease-out;
    }
    .location-card .selected-badge {
        display: none; font-size: 0.62rem; font-weight: 700;
        letter-spacing: 0.5px; color: #c026d3; margin-bottom: 6px; text-transform: uppercase;
    }
    .location-card.selected .selected-badge { display: block; }

    .location-icon-wrap {
        width: 56px; height: 56px; border-radius: 14px;
        background: linear-gradient(135deg, #f472b6 0%, #c026d3 55%, #9333ea 100%);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 10px;
        box-shadow: 0 6px 18px rgba(192,38,211,0.35);
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .location-card:hover .location-icon-wrap {
        box-shadow: 0 8px 22px rgba(192,38,211,0.5); transform: scale(1.05);
    }
    .location-icon-wrap i { color: #fff; font-size: 1.4rem; }
    .location-name { font-size: 0.9rem; font-weight: 700; color: #1e2a45; margin-bottom: 8px; }

    .location-capacity-icons { display: flex; justify-content: center; gap: 12px; margin-bottom: 3px; }
    .location-capacity-icons .fa-motorcycle { color: #16a34a; font-size: 1rem; }
    .location-capacity-icons .fa-car        { color: #dc2626; font-size: 1rem; }
    .location-capacity-icons .fa-truck      { color: #dc2626; font-size: 1rem; }
    .location-capacity-nums {
        display: flex; justify-content: center; gap: 20px;
        font-size: 0.78rem; font-weight: 700;
    }
    .location-capacity-nums .num-moto  { color: #16a34a; }
    .location-capacity-nums .num-car   { color: #dc2626; }
    .location-capacity-nums .num-other { color: #dc2626; }

    .location-hint { font-size: 0.72rem; color: #9ca3af; text-align: center; margin-top: 6px; }
    .location-hint.has-selection { color: #c026d3; font-weight: 600; }
    .locations-grid { display: flex; flex-wrap: wrap; gap: 14px; padding-bottom: 4px; }

    /* ── Form Card ── */
    .form-card {
        background: #fff; border-radius: 16px; padding: 24px 28px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07); border: none;
    }
    .form-card .form-title { font-size: 1.15rem; font-weight: 800; color: #c026d3; margin-bottom: 0; }
    .form-card .form-title span { font-weight: 400; color: #9ca3af; }
    .form-card .form-label {
        font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.5px; color: #9ca3af; margin-bottom: 6px;
    }
    .form-card .form-control {
        background: #f9fafb; border: 1.5px solid #e5e7eb; border-radius: 10px;
        font-size: 1.35rem; font-weight: 600; color: #1e2a45; padding: 12px 16px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-card .form-control:focus {
        border-color: #c026d3; box-shadow: 0 0 0 3px rgba(192,38,211,0.12); background: #fff;
    }
    .form-card .form-control::placeholder { font-size: 1rem; font-weight: 400; color: #c4c9d4; }

    .btn-exit-vehicle {
        background-color: #1e2a45; color: #fff; font-weight: 700; font-size: 0.82rem;
        padding: 10px 20px; border-radius: 10px; border: none; letter-spacing: 0.4px;
        display: flex; align-items: center; gap: 8px;
        transition: background 0.2s, transform 0.1s; white-space: nowrap;
    }
    .btn-exit-vehicle:hover { background-color: #2d3e60; color: #fff; transform: translateY(-1px); }

    /* ── Tickets Card ── */
    .tickets-card {
        background: #fff; border-radius: 16px; padding: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07); height: 100%; border: none;
    }
    .tickets-card .tickets-header {
        display: flex; justify-content: space-between; align-items: center;
        padding-bottom: 12px; border-bottom: 1.5px solid #f3f4f6; margin-bottom: 16px;
    }
    .tickets-card .tickets-title { font-size: 1rem; font-weight: 800; color: #1e2a45; }
    
    .btn-view-all {
        font-size: 0.75rem; font-weight: 700; color: #c026d3;
        border: 1.5px solid #c026d3; border-radius: 8px; padding: 4px 14px;
        background: transparent; transition: background 0.2s, color 0.2s; text-decoration: none; cursor: pointer;
    }
    .btn-view-all:hover { background: #c026d3; color: #fff; }

    .ticket-item {
        display: flex; justify-content: space-between; align-items: center;
        background: #f9fafb; border: 1.5px solid #f0f0f5; border-radius: 12px;
        padding: 12px 14px; margin-bottom: 10px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .ticket-item:hover { border-color: #c026d3; box-shadow: 0 4px 12px rgba(192,38,211,0.1); }
    .ticket-item .ticket-time  { font-size: 0.73rem; color: #9ca3af; display: block; margin-bottom: 3px; }
    .ticket-item .ticket-no   {
        font-size: 0.88rem; font-weight: 700; color: #c026d3; display: block;
        margin-bottom: 4px; cursor: pointer;
    }
    .ticket-item .ticket-plate {
        font-size: 0.72rem; font-weight: 600; background: #6b7280; color: #fff;
        border-radius: 5px; padding: 2px 8px; display: inline-block;
    }

    /* ─ Area Kanan: Status + Harga & PDF ─ */
    .ticket-right { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }
    .ticket-status { font-size: 0.75rem; color: #9ca3af; font-style: italic; font-weight: 500; }
    .ticket-status.selesai { color: #16a34a; font-style: normal; font-weight: 700; }
    .ticket-bottom-actions { display: flex; flex-direction: row; align-items: center; gap: 12px; }
    .ticket-price { font-size: 0.95rem; font-weight: 600; color: #4B5563; white-space: nowrap; }

    .pdf-btn {
        color: #ef4444; font-size: 1.5rem; line-height: 1;
        text-decoration: none; transition: color 0.15s, transform 0.15s; display: inline-block;
    }
    .pdf-btn:hover { color: #b91c1c; transform: scale(1.18); }

    .top-row-wrapper { display: flex; gap: 16px; align-items: stretch; margin-bottom: 20px; }
    .clock-col       { flex: 0 0 200px; }
    .locations-col   { flex: 1; overflow: hidden; }

    @keyframes pulse-border {
        0%   { box-shadow: 0 0 0 0   rgba(192,38,211,0.4); }
        70%  { box-shadow: 0 0 0 8px rgba(192,38,211,0);   }
        100% { box-shadow: 0 0 0 0   rgba(192,38,211,0);   }
    }

    .location-toast {
        position: fixed; bottom: 28px; left: 50%;
        transform: translateX(-50%) translateY(80px);
        background: #1e2a45; color: #fff; padding: 10px 22px;
        border-radius: 30px; font-size: 0.82rem; font-weight: 600;
        z-index: 9999; opacity: 0;
        transition: transform 0.35s cubic-bezier(.34,1.56,.64,1), opacity 0.3s;
        white-space: nowrap; box-shadow: 0 8px 24px rgba(0,0,0,0.2); pointer-events: none;
    }
    .location-toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
    .location-toast .toast-building { color: #e879f9; }

    .page-footer {
        text-align: center; font-size: 0.75rem; color: #adb5bd;
        margin-top: 24px; padding-top: 12px; border-top: 1px solid #f0f0f5;
    }
    .page-footer a { color: #c026d3; font-weight: 600; text-decoration: none; }

    /* ── Popup Modal Styles ── */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(17, 24, 39, 0.6); backdrop-filter: blur(4px);
        z-index: 9999; display: flex; align-items: center; justify-content: center;
        opacity: 0; visibility: hidden; transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .modal-overlay.show { opacity: 1; visibility: visible; }
    
    .custom-modal {
        background: #fff; border-radius: 16px; padding: 24px 28px;
        width: 95%; max-width: 1000px; max-height: 85vh;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        transform: translateY(-20px); transition: transform 0.3s ease;
        display: flex; flex-direction: column;
    }
    .modal-overlay.show .custom-modal { transform: translateY(0); }

    .modal-header-row {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px; border-bottom: 1.5px solid #f3f4f6; padding-bottom: 12px;
    }
    .list-title { font-size: 1.1rem; font-weight: 800; color: #1e2a45; margin: 0; }
    .list-title span { color: #c026d3; }
    
    .btn-close-modal {
        background: #f3f4f6; border: none; font-size: 1.1rem; color: #6b7280;
        width: 32px; height: 32px; border-radius: 8px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background 0.2s, color 0.2s;
    }
    .btn-close-modal:hover { background: #fee2e2; color: #ef4444; }
    
    .modal-body-content { overflow-y: auto; flex: 1; padding-right: 5px; }

    /* View All List Styles inside Modal */
    .toolbar-row {
        display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 18px;
    }
    .search-wrap { position: relative; flex: 1; min-width: 200px; }
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
    .search-input:focus { border-color: #c026d3; box-shadow: 0 0 0 3px rgba(192,38,211,0.1); background: #fff; }
    .filter-select {
        padding: 9px 12px; border: 1.5px solid #e5e7eb; border-radius: 10px;
        font-size: 0.8rem; font-family: 'Plus Jakarta Sans', sans-serif;
        color: #374151; background: #f9fafb; cursor: pointer;
        transition: border-color 0.2s; outline: none; min-width: 140px;
    }
    .filter-select:focus { border-color: #c026d3; }
    .result-count {
        font-size: 0.75rem; font-weight: 600; color: #9ca3af; white-space: nowrap;
        padding: 7px 14px; background: #f9fafb; border-radius: 8px;
    }
    .result-count span { color: #c026d3; font-weight: 800; }

    .tx-table { width: 100%; border-collapse: separate; border-spacing: 0 6px; font-size: 0.82rem; }
    .tx-table thead tr th {
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.5px; color: #c026d3; padding: 10px 14px;
        background: #fdf4ff; border: none; white-space: nowrap;
    }
    .tx-table thead tr th:first-child { border-radius: 10px 0 0 10px; }
    .tx-table thead tr th:last-child  { border-radius: 0 10px 10px 0; }
    .tx-table tbody tr td {
        padding: 12px 14px; background: #f9fafb; border: none;
        color: #374151; font-weight: 500; vertical-align: middle; white-space: nowrap;
    }
    .tx-table tbody tr td:first-child { border-radius: 10px 0 0 10px; }
    .tx-table tbody tr td:last-child  { border-radius: 0 10px 10px 0; }
    .tx-table tbody tr:hover td { background: #fdf4ff; }
    .tx-row-hidden { display: none !important; }

    .td-no    { color: #9ca3af; font-weight: 600; text-align: center; width: 40px; }
    .td-tiket { color: #c026d3; font-weight: 700; }
    .td-plat  {
        font-size: 0.75rem; font-weight: 700; background: #6b7280; color: #fff;
        border-radius: 5px; padding: 3px 8px; display: inline-block;
    }

    .badge-aktif {
        display: inline-flex; align-items: center; gap: 5px; background: #dcfce7; color: #16a34a;
        font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; white-space: nowrap;
    }
    .badge-aktif::before {
        content: ''; width: 7px; height: 7px; background: #16a34a; border-radius: 50%;
        display: inline-block; animation: pulse-dot 1.4s infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.5; transform: scale(0.8); }
    }
    .badge-selesai {
        display: inline-flex; align-items: center; gap: 5px; background: #ede9fe; color: #7c3aed;
        font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; white-space: nowrap;
    }
    .badge-selesai i { font-size: 0.65rem; }

    .td-cost { font-weight: 700; color: #1e2a45; }
    .td-cost.empty { color: #9ca3af; font-weight: 400; }
    .empty-state { text-align: center; padding: 40px 0; color: #9ca3af; font-size: 0.85rem; }
    .empty-state i { font-size: 2.5rem; color: #e0d0e8; display: block; margin-bottom: 10px; }
    .table-wrapper { overflow-x: auto; }
</style>

{{-- Data untuk JS --}}
<script>
    const vehicleTypesData = @json(
        $vehicleTypes->map(fn($vt) => ['id' => $vt->id, 'jenis' => strtolower($vt->jenis)])
    );
    const locationData = @json(
        $locations->map(fn($l) => ['id' => $l->id, 'name' => $l->location_name])
    );
</script>

<script>
    function showSwal({ title, text = null, html = null, icon, confirmText = 'OK',
                        confirmColor = '#c026d3', redirectUrl = null, allowOutsideClick = false }) {
        Swal.fire({
            title, text: html ? undefined : text, html, icon,
            confirmButtonText: confirmText, confirmButtonColor: confirmColor,
            allowOutsideClick, backdrop: 'rgba(0,0,0,0.45)',
        }).then(result => {
            if (redirectUrl && result.isConfirmed) window.location.href = redirectUrl;
        });
    }

    @if(session('masuk_success'))
        showSwal({
            title: 'Kendaraan Masuk!',
            html: '<div style="font-size:13px;color:#374151;">Tiket berhasil dibuat.<br>'
                + '<span style="font-size:11px;color:#9ca3af;">Klik ikon <i class=\"fa-solid fa-file-pdf\" style=\"color:#ef4444\"></i> pada tiket untuk mencetak.</span></div>',
            icon: 'success', confirmText: 'OK', confirmColor: '#c026d3', allowOutsideClick: false
        });
    @endif

    @if(session('error'))
        showSwal({
            title: 'Error!', text: '{{ session("error") }}', icon: 'error',
            confirmText: 'OK', confirmColor: '#c026d3', allowOutsideClick: false
        });
    @endif

    @if(session('keluar_success'))
        showSwal({
            title: 'Transaksi Berhasil!',
            html: '<div style="font-size:14px;text-align:center;">'
                + '<div style="margin-bottom:6px;">Total Bayar : <b>Rp {{ number_format(session("keluar_success")->total_bayar, 0, ",", ".") }}</b></div>'
                + '<div>Durasi : {{ session("keluar_success")->total_jam }} Menit</div>'
                + '</div>',
            icon: 'success', confirmText: 'Selesai', confirmColor: '#c026d3', allowOutsideClick: false
        });
    @endif
</script>

{{-- Hidden form enter vehicle --}}
<form id="hidden-enter-form" action="{{ route('transactions.enter') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="id_lokasi" id="hidden-id-lokasi">
    <input type="hidden" name="id_jenis"  id="hidden-id-jenis">
    <input type="hidden" name="no_polisi" value="">
</form>

<div class="row g-4">

    {{-- ─── KOLOM KIRI ─── --}}
    <div class="col-md-8">

        {{-- Top Row: Clock + Locations --}}
        <div class="top-row-wrapper">

            {{-- Clock --}}
            <div class="clock-col">
                <div class="clock-card">
                    <img src="{{ asset('parkir.png') }}" alt="Logo" class="clock-logo" onerror="this.style.display='none'">
                    <div class="day-name"   id="dayName"></div>
                    <div class="date-full" id="dateFull"></div>
                    <div class="clock-time" id="clock">00 : 00 : 00</div>
                    <div class="clock-label">JAM &nbsp;:&nbsp; MENIT &nbsp;:&nbsp; DETIK</div>
                    <div class="clock-tz">WIB — Cibinong, Kab. Bogor</div>
                </div>
            </div>

            {{-- Locations Grid Layout --}}
            <div class="locations-col d-flex flex-column">
                <div class="locations-grid flex-grow-1 align-items-stretch">
                    @foreach($locations as $loc)
                    <div class="location-card"
                         data-location-id="{{ $loc->id }}"
                         data-location-name="{{ $loc->location_name }}"
                         onclick="selectLocation({{ $loc->id }}, '{{ $loc->location_name }}')"
                         title="Klik untuk memilih {{ $loc->location_name }}">

                        <div class="selected-badge">✓ Terpilih</div>

                        {{-- Icon gedung gradient pink-ungu --}}
                        <div class="location-icon-wrap">
                            <i class="fa-solid fa-building"></i>
                        </div>

                        <div class="location-name">{{ $loc->location_name }}</div>

                        {{-- Baris ikon --}}
                        <div class="location-capacity-icons">
                            <i class="fa-solid fa-motorcycle"></i>
                            <i class="fa-solid fa-car"></i>
                            <i class="fa-solid fa-truck"></i>
                        </div>
                        {{-- Baris angka --}}
                        <div class="location-capacity-nums">
                            <span class="num-moto">{{ $loc->max_motorcycle }}</span>
                            <span class="num-car">{{ $loc->max_car }}</span>
                            <span class="num-other">{{ $loc->max_other }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="location-hint mt-2" id="locationHint">
                    <i class="fa-solid fa-hand-pointer me-1"></i>Klik gedung, lalu pilih jenis kendaraan di tombol atas
                </div>
            </div>
        </div>

        {{-- Form Exit --}}
        <div class="form-card">
            <form action="{{ route('transactions.exit') }}" method="POST">
                @csrf
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="form-title">Transaction <span>Input Form</span></h5>
                    <button type="submit" class="btn-exit-vehicle">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> EXIT VEHICLE
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ticket Number</label>
                        <input type="text" class="form-control" name="no_tiket"
                               id="exit-no-tiket" placeholder="Scan or Type Ticket"
                               value="{{ old('no_tiket') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Police Number</label>
                        <input type="text" class="form-control" name="no_polisi"
                               placeholder="B 1234 CD" value="{{ old('no_polisi') }}">
                    </div>
                </div>
            </form>
        </div>

    </div>

    {{-- ─── KOLOM KANAN: Tiket ─── --}}
    <div class="col-md-4">
        <div class="tickets-card">
            <div class="tickets-header">
                <span class="tickets-title">Tickets</span>
                <button type="button" class="btn-view-all" onclick="openTicketsModal()">VIEW ALL</button>
            </div>

            @forelse($tickets as $t)
            <div class="ticket-item">
                {{-- Kiri: info tiket --}}
                <div>
                    <span class="ticket-time">
                        <i class="fa-regular fa-clock me-1"></i>{{ $t->masuk }}
                    </span>
                    <span class="ticket-no"
                          onclick="fillTicketNumber('{{ $t->no_tiket }}')"
                          title="Klik untuk isi ke form exit">
                        #{{ $t->no_tiket }}
                    </span>
                    <span class="ticket-plate">{{ $t->no_polisi ?? '-' }}</span>
                </div>

                {{-- Kanan: status, lalu harga + ikon PDF di bawahnya --}}
                <div class="ticket-right">
                    {{-- Status Tiket --}}
                    @if(isset($t->keluar))
                        <span class="ticket-status selesai"><i class="fa-solid fa-check me-1"></i>Selesai</span>
                    @else
                        <span class="ticket-status">Parkir</span>
                    @endif

                    <div class="ticket-bottom-actions">
                        {{-- Kalkulasi Harga Berjalan untuk tiket aktif --}}
                        @php
                            $displayPrice = 0;
                            if(isset($t->total_bayar) && $t->total_bayar > 0) {
                                $displayPrice = $t->total_bayar;
                            } else {
                                $wMasuk = \Carbon\Carbon::parse($t->masuk);
                                $wSkg   = \Carbon\Carbon::now();
                                $tMenit = max(1, $wMasuk->diffInMinutes($wSkg));
                                if ($tMenit <= 1440) {
                                    $displayPrice = $t->perjam_pertama + ($t->perjam_berikutnya * ($tMenit - 1));
                                    if ($displayPrice > $t->max_perhari) {
                                        $displayPrice = $t->max_perhari;
                                    }
                                } else {
                                    $tHari = floor($tMenit / 1440);
                                    $displayPrice = $tHari * ($t->max_perhari * 0.6);
                                }
                            }
                        @endphp
                        
                        <span class="ticket-price">Rp {{ number_format($displayPrice, 0, ',', '.') }}</span>

                        <a href="{{ route('transactions.ticket.view', $t->id) }}"
                           target="_blank"
                           class="pdf-btn"
                           title="Lihat / Cetak Tiket PDF"
                           style="background-image: none !important; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none;">
                           
                            {{-- SVG Ikon PDF Warna Biru Gelap --}}
                            <svg width="22" height="22" viewBox="0 0 384 512" fill="#3B4B5B" xmlns="http://www.w3.org/2000/svg">
                                <path d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM112 256H144c26.5 0 48 21.5 48 48s-21.5 48-48 48H112v48c0 8.8-7.2 16-16 16s-16-7.2-16-16V272c0-8.8 7.2-16 16-16zm32 64c8.8 0 16-7.2 16-16s-7.2-16-16-16H112v32H144zm144-64H320c8.8 0 16 7.2 16 16s-7.2 16-16 16-16 7.2-16 16v32c0 8.8-7.2 16-16 16s-16-7.2-16-16V272c0-8.8 7.2-16 16-16zm32 64c-8.8 0-16 7.2-16 16s7.2 16 16 16 16-7.2 16-16-7.2-16-16-16zm-144-64H240c26.5 0 48 21.5 48 48v32c0 26.5-21.5 48-48 48H176v-16c0-8.8 7.2-16 16-16H208c8.8 0 16-7.2 16-16V304c0-8.8-7.2-16-16-16H176c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/>
                            </svg>

                            {{-- Teks "PDF" di sebelah ikon --}}
                            <span style="color: #3B4B5B; font-weight: 700; font-family: 'Segoe UI', Arial, sans-serif; font-size: 15px; letter-spacing: 0.5px;">PDF</span>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-4">
                <i class="fa-solid fa-ticket fa-2x mb-2 d-block" style="color:#e0d0e8;"></i>
                <small>No tickets yet</small>
            </div>
            @endforelse
        </div>
    </div>

</div>

{{-- ── Popup Modal for View All Tickets ── --}}
<div id="ticketsModal" class="modal-overlay">
    <div class="custom-modal">
        <div class="modal-header-row">
            <h5 class="list-title">All <span>Transactions</span></h5>
            <button class="btn-close-modal" onclick="closeTicketsModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        
        <div class="modal-body-content">
            {{-- Toolbar: Search & Filter --}}
            <div class="toolbar-row">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="Search ticket no, plate, or location..." onkeyup="filterTickets()">
                </div>
                <select id="statusFilter" class="filter-select" onchange="filterTickets()">
                    <option value="all">All Status</option>
                    <option value="active">Active (Parked)</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            {{-- Table --}}
            <div class="table-wrapper">
                <table class="tx-table" id="ticketsTable">
                    <thead>
                        <tr>
                            <th class="td-no">#</th>
                            <th>Ticket No.</th>
                            <th>Vehicle</th>
                            <th>Plate</th>
                            <th>Location</th>
                            <th>Time In</th>
                            <th>Status</th>
                            <th>Cost</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($all_transactions ?? $tickets as $index => $tx)
                        <tr class="ticket-row" data-status="{{ isset($tx->keluar) ? 'completed' : 'active' }}">
                            <td class="td-no">{{ $index + 1 }}</td>
                            <td class="td-tiket search-target">#{{ $tx->no_tiket }}</td>
                            <td>
                                @if(strtolower($tx->vehicleType->jenis ?? '') == 'motorcycle')
                                    <i class="fa-solid fa-motorcycle" style="color:#16a34a;"></i>
                                @elseif(strtolower($tx->vehicleType->jenis ?? '') == 'car')
                                    <i class="fa-solid fa-car" style="color:#dc2626;"></i>
                                @else
                                    <i class="fa-solid fa-truck" style="color:#dc2626;"></i>
                                @endif
                                <span class="ms-1">{{ ucfirst($tx->vehicleType->jenis ?? 'Unknown') }}</span>
                            </td>
                            <td>
                                @if($tx->no_polisi)
                                    <span class="td-plat search-target">{{ $tx->no_polisi }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="search-target">{{ $tx->location->location_name ?? 'N/A' }}</td>
                            <td>{{ $tx->masuk }}</td>
                            <td>
                                @if(isset($tx->keluar))
                                    <span class="badge-selesai"><i class="fa-solid fa-check"></i> Selesai</span>
                                @else
                                    <span class="badge-aktif">Parkir</span>
                                @endif
                            </td>
                            <td>
                                {{-- Kalkulasi Harga Berjalan untuk view all list (jika masih aktif) --}}
                                @php
                                    $txPrice = 0;
                                    if(isset($tx->total_bayar) && $tx->total_bayar > 0) {
                                        $txPrice = $tx->total_bayar;
                                    } else {
                                        $wMasuk = \Carbon\Carbon::parse($tx->masuk);
                                        $wSkg   = \Carbon\Carbon::now();
                                        $tMenit = max(1, $wMasuk->diffInMinutes($wSkg));
                                        if ($tMenit <= 1440) {
                                            $txPrice = $tx->perjam_pertama + ($tx->perjam_berikutnya * ($tMenit - 1));
                                            if ($txPrice > $tx->max_perhari) {
                                                $txPrice = $tx->max_perhari;
                                            }
                                        } else {
                                            $tHari = floor($tMenit / 1440);
                                            $txPrice = $tHari * ($tx->max_perhari * 0.6);
                                        }
                                    }
                                @endphp
                                <span class="td-cost">Rp {{ number_format($txPrice, 0, ',', '.') }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('transactions.ticket.view', $tx->id) }}" target="_blank" class="pdf-btn" title="Cetak PDF">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="fa-solid fa-folder-open"></i>
                                    No transaction records found.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Footer --}}
<div class="page-footer">
    © 2025, made with ❤️ by <a href="#">Coding Lover</a> for ASAS Ganjil Web And Mobile Development - SMKN 1 Cibinong.
</div>

{{-- Toast --}}
<div class="location-toast" id="locationToast">
    <i class="fa-solid fa-check-circle me-1" style="color:#a3e635;"></i>
    Gedung <span class="toast-building" id="toastBuildingName"></span> terpilih
    — pilih jenis kendaraan di atas
</div>

<script>
    // ── State ─────────────────────────────────────────
    window.selectedLocationId   = null;
    window.selectedLocationName = null;
    window.selectedVehicleType  = null;
    window.selectedVehicleId    = null;
    window.toastTimer           = null;

    window.keywordMap = {
        motorcycle: ['motorcycle','motor','sepeda motor','mtr','bike','motorbike'],
        car:        ['car','mobil','sedan','mpv','suv','automobile'],
        other:      ['other','lain','lainnya','truck','truk','bus','pick','pickup','truk/bus','bus/truk']
    };

    window.resolveVehicleId = function(typeSlug) {
        if (!vehicleTypesData?.length) return null;
        const keywords = keywordMap[typeSlug] ?? [typeSlug];
        for (const vt of vehicleTypesData) {
            const jenis = (vt.jenis || '').toLowerCase().trim();
            if (keywords.some(kw => jenis === kw || jenis.includes(kw))) return vt.id;
        }
        const order = { motorcycle: 0, car: 1, other: 2 };
        return vehicleTypesData[Math.min(order[typeSlug] ?? 0, vehicleTypesData.length - 1)].id;
    };

    window.selectVehicleType = function(typeSlug, btnEl) {
        selectedVehicleType = typeSlug;
        selectedVehicleId   = resolveVehicleId(typeSlug);
        document.querySelectorAll('.btn-vehicle-type').forEach(b => b.classList.remove('active-type'));
        if (btnEl) btnEl.classList.add('active-type');
        const hint = document.getElementById('locationHint');
        hint.classList.add('has-selection');
        hint.textContent = selectedLocationId
            ? `✓ ${selectedLocationName} + ${typeSlug.toUpperCase()} siap — klik ENTER VEHICLE`
            : `${typeSlug.toUpperCase()} dipilih — klik salah satu gedung, lalu ENTER VEHICLE`;
    };

    window.selectLocation = function(id, name) {
        selectedLocationId   = id;
        selectedLocationName = name;
        document.querySelectorAll('.location-card').forEach(c =>
            c.classList.toggle('selected', parseInt(c.dataset.locationId) === id)
        );
        document.getElementById('hidden-id-lokasi').value = id;
        const hint = document.getElementById('locationHint');
        hint.classList.add('has-selection');
        hint.textContent = selectedVehicleType
            ? `✓ ${name} + ${selectedVehicleType.toUpperCase()} siap — klik ENTER VEHICLE`
            : `✓ ${name} terpilih — klik MOTORCYCLE, CAR, atau OTHER di atas`;
        document.getElementById('toastBuildingName').textContent = name;
        showToast();
    };

    window.submitEnterForm = function() {
        if (!selectedLocationId) {
            Swal.fire({ title: 'Peringatan!', text: 'Silakan pilih gedung terlebih dahulu!', icon: 'warning', confirmButtonColor: '#c026d3' });
            return;
        }
        if (!selectedVehicleId) {
            Swal.fire({ title: 'Peringatan!', text: 'Silakan pilih jenis kendaraan terlebih dahulu!', icon: 'warning', confirmButtonColor: '#c026d3' });
            return;
        }
        document.getElementById('hidden-id-lokasi').value = selectedLocationId;
        document.getElementById('hidden-id-jenis').value  = selectedVehicleId;
        document.getElementById('hidden-enter-form').submit();
    };

    window.showToast = function() {
        const toast = document.getElementById('locationToast');
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.remove('show'), 3000);
    };

    window.fillTicketNumber = function(noTiket) {
        const input = document.getElementById('exit-no-tiket');
        input.value = noTiket;
        input.focus();
        input.closest('.form-card').scrollIntoView({ behavior: 'smooth', block: 'center' });
        input.style.borderColor = '#c026d3';
        input.style.boxShadow   = '0 0 0 3px rgba(192,38,211,0.2)';
        setTimeout(() => { input.style.borderColor = ''; input.style.boxShadow = ''; }, 1500);
    };

    window.updateLiveClock = function() {
        const wib    = new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Jakarta' }));
        const days   = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        document.getElementById('dayName').innerText  = days[wib.getDay()];
        document.getElementById('dateFull').innerText = `${wib.getDate()} ${months[wib.getMonth()]} ${wib.getFullYear()}`;
        const H = String(wib.getHours()).padStart(2,'0');
        const M = String(wib.getMinutes()).padStart(2,'0');
        const S = String(wib.getSeconds()).padStart(2,'0');
        document.getElementById('clock').innerText = `${H} : ${M} : ${S}`;
    };

    setInterval(window.updateLiveClock, 1000);
    window.updateLiveClock();

    // ── Popup Modal Logic ─────────────────────────────────────────
    const modal = document.getElementById('ticketsModal');

    window.openTicketsModal = function() {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden'; // Prevent background scroll
    };

    window.closeTicketsModal = function() {
        modal.classList.remove('show');
        document.body.style.overflow = 'auto'; // Restore background scroll
    };

    // Close when clicking outside modal content
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeTicketsModal();
        }
    });

    // Filtering & Searching Logic inside Modal
    window.filterTickets = function() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('#ticketsTable tbody tr.ticket-row');

        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const searchTargets = row.querySelectorAll('.search-target');
            let textContent = '';
            
            searchTargets.forEach(target => {
                textContent += target.textContent.toLowerCase() + ' ';
            });

            const matchesSearch = textContent.includes(query);
            const matchesStatus = statusFilter === 'all' || status === statusFilter;

            if (matchesSearch && matchesStatus) {
                row.classList.remove('tx-row-hidden');
            } else {
                row.classList.add('tx-row-hidden');
            }
        });
    };
</script>
@endsection