@extends('layouts.app')

@section('title', 'Location')

@section('header_action')
    <a href="/locations/create" class="btn btn-purple" style="background-color: #c026d3; color: white;">
        <i class="fa-solid fa-plus"></i> ADD NEW LOCATION
    </a>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card p-4 shadow-sm" style="border: none; border-radius: 10px;">
    <h5 class="mb-4">Location <span style="color: #c026d3; font-weight: bold;">Data Table</span></h5>
    
    <div class="table-responsive">
        <table class="table table-borderless table-striped align-middle text-center">
            <thead>
                <tr style="color: #c026d3; font-weight: bold; border-bottom: 2px solid #f4f6f9;">
                    <th>ID</th>
                    <th>LOCATION NAME</th>
                    <th>MAX MOTORCYCLE</th>
                    <th>MAX CAR</th>
                    <th>MAX TRUCK/BUS/OTHER</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $location)
                    <tr>
                        <td>{{ $location->id }}</td>
                        <td class="fw-bold text-secondary">{{ $location->location_name }}</td>
                        <td>{{ $location->max_motorcycle }}</td>
                        <td>{{ $location->max_car }}</td>
                        <td>{{ $location->max_other }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted py-4">No location data available. Click "Add New Location" to create one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection