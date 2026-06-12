@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    /* Dashboard Specific Styles */
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        border: 1px solid #f0f0f5;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(192,38,211,0.12);
        border-color: #fdf4ff;
    }
    .stat-icon-wrap {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        flex-shrink: 0;
    }
    .stat-icon-purple {
        background: linear-gradient(135deg, #c026d3, #9333ea);
        color: #fff;
        box-shadow: 0 6px 16px rgba(192,38,211,0.3);
    }
    .stat-icon-blue {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #fff;
        box-shadow: 0 6px 16px rgba(59,130,246,0.3);
    }
    .stat-icon-green {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        box-shadow: 0 6px 16px rgba(16,185,129,0.3);
    }
    .stat-details h3 {
        font-size: 1.6rem;
        font-weight: 800;
        color: #1e2a45;
        margin: 0 0 4px 0;
    }
    .stat-details p {
        font-size: 0.85rem;
        font-weight: 600;
        color: #9ca3af;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Section Headers */
    .section-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #1e2a45;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title i {
        color: #c026d3;
    }

    /* Locations Capacity Card */
    .capacity-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid #f0f0f5;
        height: 100%;
    }
    .loc-item {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f3f4f6;
    }
    .loc-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .loc-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    .loc-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #fdf4ff;
        color: #c026d3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    .loc-name {
        font-weight: 700;
        color: #1e2a45;
        font-size: 1.05rem;
    }
    .capacity-bars {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    .cap-bar-item {
        background: #f9fafb;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        border: 1px solid #f3f4f6;
    }
    .cap-bar-item i {
        margin-bottom: 6px;
        font-size: 1.1rem;
    }
    .cap-bar-item .fa-motorcycle { color: #10b981; }
    .cap-bar-item .fa-car { color: #3b82f6; }
    .cap-bar-item .fa-truck { color: #f59e0b; }
    .cap-count {
        font-size: 1.1rem;
        font-weight: 800;
        color: #1e2a45;
    }
    .cap-label {
        font-size: 0.65rem;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Recent Transactions Card */
    .recent-tx-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid #f0f0f5;
        height: 100%;
    }
    .tx-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .tx-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        border-radius: 12px;
        background: #f9fafb;
        border: 1px solid #f0f0f5;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .tx-item:hover {
        border-color: #c026d3;
        box-shadow: 0 4px 12px rgba(192,38,211,0.08);
        background: #fff;
    }
    .tx-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .tx-v-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    .tx-v-icon.moto { background: #d1fae5; color: #10b981; }
    .tx-v-icon.car { background: #dbeafe; color: #3b82f6; }
    .tx-v-icon.other { background: #fef3c7; color: #f59e0b; }
    
    .tx-info h6 {
        margin: 0 0 4px 0;
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e2a45;
    }
    .tx-info span {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 500;
    }
    .tx-plat {
        background: #e5e7eb;
        color: #374151;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        margin-left: 6px;
    }
    .tx-right {
        text-align: right;
    }
    .tx-time {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-bottom: 4px;
        display: block;
    }
    .tx-status {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .tx-status.active {
        background: #ede9fe;
        color: #7c3aed;
    }
    .tx-status.completed {
        background: #dcfce7;
        color: #16a34a;
    }

    @media (max-width: 768px) {
        .stat-card { padding: 18px; gap: 14px; }
        .stat-icon-wrap { width: 52px; height: 52px; font-size: 1.4rem; }
        .stat-details h3 { font-size: 1.3rem; }
        
        .capacity-bars { gap: 8px; }
        .cap-bar-item { padding: 8px 4px; }
        .cap-count { font-size: 1rem; }
        
        .tx-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        .tx-right {
            text-align: left;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .tx-time { margin-bottom: 0; }
    }
</style>

<div class="row g-4 mb-4">
    <!-- Revenue Today -->
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon-wrap stat-icon-purple">
                <i class="fa-solid fa-rupiah-sign"></i>
            </div>
            <div class="stat-details">
                <h3>Rp {{ number_format($revenueToday, 0, ',', '.') }}</h3>
                <p>Revenue Today</p>
            </div>
        </div>
    </div>
    
    <!-- Active Parked -->
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon-wrap stat-icon-blue">
                <i class="fa-solid fa-car-side"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $activeParked }}</h3>
                <p>Vehicles Parked</p>
            </div>
        </div>
    </div>

    <!-- Total Today -->
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon-wrap stat-icon-green">
                <i class="fa-solid fa-ticket"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $totalTransactionsToday }}</h3>
                <p>Transactions Today</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Location Capacity -->
    <div class="col-md-5">
        <div class="capacity-card">
            <h4 class="section-title"><i class="fa-solid fa-building"></i> Location Capacities</h4>
            <div class="mt-4">
                @foreach($locations as $loc)
                <div class="loc-item">
                    <div class="loc-header">
                        <div class="loc-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="loc-name">{{ $loc->location_name }}</div>
                    </div>
                    <div class="capacity-bars">
                        <div class="cap-bar-item">
                            <i class="fa-solid fa-motorcycle"></i>
                            <div class="cap-count">{{ $loc->max_motorcycle }}</div>
                            <div class="cap-label">Motorcycle</div>
                        </div>
                        <div class="cap-bar-item">
                            <i class="fa-solid fa-car"></i>
                            <div class="cap-count">{{ $loc->max_car }}</div>
                            <div class="cap-label">Car</div>
                        </div>
                        <div class="cap-bar-item">
                            <i class="fa-solid fa-truck"></i>
                            <div class="cap-count">{{ $loc->max_other }}</div>
                            <div class="cap-label">Other</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-md-7">
        <div class="recent-tx-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="section-title mb-0"><i class="fa-solid fa-clock-rotate-left"></i> Recent Transactions</h4>
                <a href="{{ route('transactions.index') }}" class="btn btn-sm" style="background:#fdf4ff; color:#c026d3; font-weight:700; border-radius:8px;">View All</a>
            </div>
            
            <div class="tx-list">
                @forelse($recentTransactions as $tx)
                    @php
                        $jenis = strtolower($tx->vehicleType->jenis ?? '');
                        $iconClass = 'other';
                        $faIcon = 'fa-truck';
                        if (str_contains($jenis, 'motor')) { $iconClass = 'moto'; $faIcon = 'fa-motorcycle'; }
                        elseif (str_contains($jenis, 'mobil') || str_contains($jenis, 'car')) { $iconClass = 'car'; $faIcon = 'fa-car'; }
                    @endphp
                    <div class="tx-item">
                        <div class="tx-left">
                            <div class="tx-v-icon {{ $iconClass }}">
                                <i class="fa-solid {{ $faIcon }}"></i>
                            </div>
                            <div class="tx-info">
                                <h6>#{{ $tx->no_tiket }} @if($tx->no_polisi) <span class="tx-plat">{{ $tx->no_polisi }}</span> @endif</h6>
                                <span>{{ $tx->location->location_name ?? 'N/A' }} &bull; {{ ucfirst($tx->vehicleType->jenis ?? 'Unknown') }}</span>
                            </div>
                        </div>
                        <div class="tx-right">
                            <span class="tx-time">{{ \Carbon\Carbon::parse($tx->masuk)->diffForHumans() }}</span>
                            @if(isset($tx->keluar))
                                <span class="tx-status completed">Completed</span>
                            @else
                                <span class="tx-status active">Parked</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="fa-solid fa-inbox fa-2x mb-2" style="color:#e0d0e8;"></i>
                        <p>No recent transactions</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
