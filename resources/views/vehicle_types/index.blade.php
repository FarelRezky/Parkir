@extends('layouts.app')

@section('title', 'Vehicle Type')

@section('header_action')
    <a href="/vehicle-types/create" class="btn fw-bold text-white shadow-sm" style="background-color: #c026d3;">
        <i class="fa-solid fa-plus"></i> ADD NEW VEHICLE TYPE
    </a>
@endsection

@section('content')
<div class="card p-4 shadow-sm" style="border: none; border-radius: 10px;">
    <h5 class="mb-4">Vehicle Type <span style="color: #c026d3; font-weight: bold;">Data Table</span></h5>
    
    <div class="table-responsive">
        <table class="table table-borderless table-striped align-middle text-center">
            <thead>
                <tr style="color: #c026d3; font-weight: bold; border-bottom: 2px solid #f4f6f9;">
                    <th>ID</th>
                    <th>VEHICLE TYPE</th>
                    <th>FIRST HOUR CHARGES</th>
                    <th>NEXT HOURLY CHARGES</th>
                    <th>MAX COST PER DAY</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicleTypes as $vt)
                    <tr>
                        <td>{{ $vt->id }}</td>
                        <td class="fw-bold text-secondary">{{ $vt->jenis }}</td>
                        <td>Rp {{ number_format($vt->perjam_pertama, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($vt->perjam_berikutnya, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($vt->max_perhari, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted py-4">No vehicle type data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection