@extends('layouts.app')

@section('title', 'Vehicle Type')

@section('content')
<div class="row justify-content-start">
    <div class="col-md-6">
        <div class="card p-4 shadow-sm" style="border: none; border-radius: 10px;">
            <h5 class="mb-4">Vehicle Type <span class="text-muted" style="font-weight: normal;">Input Form</span></h5>
            
            <form action="/vehicle-types" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-secondary fw-semibold">Vehicle Type</label>
                    <select name="jenis" class="form-select form-select-lg" required style="border-radius: 8px;">
                        <option value="motorcycle">motorcycle</option>
                        <option value="car">car</option>
                        <option value="other">other</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-secondary fw-semibold">First Hour Charges</label>
                    <input type="number" name="perjam_pertama" class="form-control form-control-lg" placeholder="e.g. 2000" min="0" required style="border-radius: 8px;">
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-secondary fw-semibold">Next Hourly Charges</label>
                    <input type="number" name="perjam_berikutnya" class="form-control form-control-lg" placeholder="e.g. 1000" min="0" required style="border-radius: 8px;">
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-secondary fw-semibold">Max Cost Per Day</label>
                    <input type="number" name="max_perhari" class="form-control form-control-lg" placeholder="e.g. 10000" min="0" required style="border-radius: 8px;">
                </div>
                
                <div class="d-flex gap-3">
                    <a href="/vehicle-types" class="btn btn-dark btn-lg w-50 fw-semibold" style="border-radius: 8px;">CANCEL</a>
                    <button type="submit" class="btn btn-lg w-50 fw-semibold text-white" style="background-color: #c026d3; border-radius: 8px;">SAVE VEHICLE TYPE</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection