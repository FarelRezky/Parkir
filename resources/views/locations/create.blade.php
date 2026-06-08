@extends('layouts.app')

@section('title', 'Location')

@section('content')
<div class="row justify-content-start">
    <div class="col-md-6">
        <div class="card p-4 shadow-sm" style="border: none; border-radius: 10px;">
            <h5 class="mb-4">Location <span class="text-muted" style="font-weight: normal;">Input Form</span></h5>
            
            <form action="/locations" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-secondary fw-semibold">Location Name</label>
                    <input type="text" name="location_name" class="form-control form-control-lg" placeholder="Gedung A" required style="border-radius: 8px;">
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-secondary fw-semibold">Max Motorcycle</label>
                    <input type="number" name="max_motorcycle" class="form-control form-control-lg" value="0" min="0" required style="border-radius: 8px;">
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-secondary fw-semibold">Max Car</label>
                    <input type="number" name="max_car" class="form-control form-control-lg" value="0" min="0" required style="border-radius: 8px;">
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-secondary fw-semibold">Max Truck/Bus/Other</label>
                    <input type="number" name="max_other" class="form-control form-control-lg" value="0" min="0" required style="border-radius: 8px;">
                </div>
                
                <div class="d-flex gap-3">
                    <a href="/locations" class="btn btn-dark btn-lg w-50 fw-semibold" style="border-radius: 8px;">CANCEL</a>
                    <button type="submit" class="btn btn-lg w-50 fw-semibold text-white" style="background-color: #c026d3; border-radius: 8px;">SAVE LOCATION</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection