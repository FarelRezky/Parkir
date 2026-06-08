@extends('layouts.app')

@section('title', 'Vehicle Type')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body, .content-area {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f4f6fb;
    }

    .form-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 32px 36px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        border: none;
    }
    .form-card-title {
        font-size: 1.15rem;
        font-weight: 400;
        color: #374151;
        margin-bottom: 28px;
    }
    .form-card-title span {
        color: #c026d3;
        font-weight: 800;
    }

    .field-group { margin-bottom: 18px; }
    .field-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 7px;
        display: block;
    }
    .field-input,
    .field-select {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.95rem;
        font-weight: 500;
        color: #1e2a45;
        background: #f9fafb;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        -moz-appearance: textfield;
    }
    .field-input::-webkit-outer-spin-button,
    .field-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .field-input:focus,
    .field-select:focus {
        border-color: #c026d3;
        box-shadow: 0 0 0 3px rgba(192, 38, 211, 0.1);
        background: #fff;
    }
    .field-input::placeholder { color: #c4c9d4; font-weight: 400; }

    /* Select with custom arrow */
    .select-wrap { position: relative; }
    .select-wrap::after {
        content: '\f107';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
        font-size: 0.9rem;
    }
    .field-select {
        appearance: none;
        -webkit-appearance: none;
        cursor: pointer;
        padding-right: 40px;
    }

    .btn-row {
        display: flex;
        gap: 12px;
        margin-top: 28px;
    }
    .btn-cancel {
        flex: 1;
        padding: 13px;
        background: #1e2a45;
        color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 0.7px;
        border: none;
        border-radius: 10px;
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background 0.2s, transform 0.1s;
    }
    .btn-cancel:hover { background: #2d3e60; color: #fff; transform: translateY(-1px); }
    .btn-save {
        flex: 1;
        padding: 13px;
        background: linear-gradient(135deg, #c026d3, #9333ea);
        color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 0.7px;
        border: none;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(192, 38, 211, 0.35);
        transition: opacity 0.2s, box-shadow 0.2s, transform 0.1s;
        cursor: pointer;
    }
    .btn-save:hover {
        opacity: 0.92;
        box-shadow: 0 6px 20px rgba(192, 38, 211, 0.5);
        transform: translateY(-1px);
    }

    .page-footer {
        text-align: center;
        font-size: 0.75rem;
        color: #adb5bd;
        margin-top: 28px;
        padding-top: 12px;
        border-top: 1px solid #f0f0f5;
    }
    .page-footer a { color: #c026d3; font-weight: 600; text-decoration: none; }
</style>

<div class="row">
    <div class="col-12">
        <div class="form-card">
            <h5 class="form-card-title">Vehicle Type <span>Input Form</span></h5>

            <form action="/vehicle-types" method="POST">
                @csrf

                {{-- Vehicle Type Dropdown --}}
                <div class="field-group">
                    <label class="field-label">Vehicle Type</label>
                    <div class="select-wrap">
                        <select name="jenis" class="field-select" required>
                            <option value="" disabled selected>-- Pilih Jenis Kendaraan --</option>
                            <option value="motorcycle" {{ old('jenis') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                            <option value="car"        {{ old('jenis') == 'car'        ? 'selected' : '' }}>Car</option>
                            <option value="other"      {{ old('jenis') == 'other'      ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                {{-- First Hour Charges --}}
                <div class="field-group">
                    <label class="field-label">First Hour Charges</label>
                    <input type="number" name="perjam_pertama"
                           class="field-input"
                           placeholder="2000"
                           value="{{ old('perjam_pertama') }}"
                           min="0" required>
                </div>

                {{-- Next Hourly Charges --}}
                <div class="field-group">
                    <label class="field-label">Next Hourly Charges</label>
                    <input type="number" name="perjam_berikutnya"
                           class="field-input"
                           placeholder="1000"
                           value="{{ old('perjam_berikutnya') }}"
                           min="0" required>
                </div>

                {{-- Max Cost Per Day --}}
                <div class="field-group">
                    <label class="field-label">Max Cost Per Day</label>
                    <input type="number" name="max_perhari"
                           class="field-input"
                           placeholder="10000"
                           value="{{ old('max_perhari') }}"
                           min="0" required>
                </div>

                <div class="btn-row">
                    <a href="/vehicle-types" class="btn-cancel">
                        <i class="fa-solid fa-xmark"></i> CANCEL
                    </a>
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i> SAVE VEHICLE TYPE
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="page-footer">
    © 2025, made with ❤️ by <a href="#">Coding Lover</a> for ASAS Ganjil Web And Mobile Development - SMKN 1 Cibinong.
</div>
@endsection