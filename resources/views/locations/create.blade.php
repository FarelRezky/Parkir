@extends('layouts.app')

@section('title', 'Location')

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
        appearance: none;
        -webkit-appearance: none;
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

    /* Select wrapper untuk arrow custom */
    .select-wrap {
        position: relative;
    }
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
        cursor: pointer;
    }
    .btn-cancel:hover {
        background: #2d3e60;
        color: #fff;
        transform: translateY(-1px);
    }
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
    .page-footer a {
        color: #c026d3;
        font-weight: 600;
        text-decoration: none;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="form-card">
            <h5 class="form-card-title">Location <span>Input Form</span></h5>

            <form action="/locations" method="POST" id="locationForm">
                @csrf

                {{-- Location Name (dropdown) --}}
                <div class="field-group">
                    <label class="field-label">Location Name</label>
                    <div class="select-wrap">
                        <select name="location_name" id="locationName" class="field-select" required
                                onchange="syncCustomName(this)">
                            <option value="" disabled selected>-- Pilih Gedung --</option>
                            <option value="Gedung A" {{ old('location_name') == 'Gedung A' ? 'selected' : '' }}>Gedung A</option>
                            <option value="Gedung B" {{ old('location_name') == 'Gedung B' ? 'selected' : '' }}>Gedung B</option>
                            <option value="Gedung C" {{ old('location_name') == 'Gedung C' ? 'selected' : '' }}>Gedung C</option>
                            <option value="Gedung D" {{ old('location_name') == 'Gedung D' ? 'selected' : '' }}>Gedung D</option>
                            <option value="Gedung E" {{ old('location_name') == 'Gedung E' ? 'selected' : '' }}>Gedung E</option>
                            <option value="custom" {{ old('location_name') && !in_array(old('location_name'), ['Gedung A','Gedung B','Gedung C','Gedung D','Gedung E']) ? 'selected' : '' }}>Lainnya (Custom)</option>
                        </select>
                    </div>
                    {{-- Input custom name, muncul jika pilih "Lainnya" --}}
                    <input type="text"
                           name="location_name_custom"
                           id="locationNameCustom"
                           class="field-input mt-2"
                           placeholder="Tulis nama gedung..."
                           style="display:none;"
                           value="{{ old('location_name') && !in_array(old('location_name'), ['Gedung A','Gedung B','Gedung C','Gedung D','Gedung E']) ? old('location_name') : '' }}">
                </div>

                {{-- Max Motorcycle --}}
                <div class="field-group">
                    <label class="field-label">Max Motorcycle</label>
                    <input type="number" name="max_motorcycle" id="maxMotorcycle"
                           class="field-input"
                           value="{{ old('max_motorcycle', 0) }}" min="0" required>
                </div>

                {{-- Max Car --}}
                <div class="field-group">
                    <label class="field-label">Max Car</label>
                    <input type="number" name="max_car" id="maxCar"
                           class="field-input"
                           value="{{ old('max_car', 0) }}" min="0" required>
                </div>

                {{-- Max Truck/Bus/Other --}}
                <div class="field-group">
                    <label class="field-label">Max Truck / Bus / Other</label>
                    <input type="number" name="max_other" id="maxOther"
                           class="field-input"
                           value="{{ old('max_other', 0) }}" min="0" required>
                </div>

                <div class="btn-row">
                    <a href="/locations" class="btn-cancel">
                        <i class="fa-solid fa-xmark"></i> CANCEL
                    </a>
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i> SAVE LOCATION
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="page-footer">
    © 2025, made with ❤️ by <a href="#">Coding Lover</a> for ASAS Ganjil Web And Mobile Development - SMKN 1 Cibinong.
</div>

<script>
    function syncCustomName(select) {
        const customInput = document.getElementById('locationNameCustom');
        if (select.value === 'custom') {
            customInput.style.display = 'block';
            customInput.required = true;
            customInput.focus();
        } else {
            customInput.style.display = 'none';
            customInput.required = false;
            customInput.value = '';
        }
    }

    // Sebelum submit: kalau custom, pindahkan value ke location_name
    document.getElementById('locationForm').addEventListener('submit', function(e) {
        const select = document.getElementById('locationName');
        const customInput = document.getElementById('locationNameCustom');

        if (select.value === 'custom') {
            if (!customInput.value.trim()) {
                e.preventDefault();
                customInput.focus();
                customInput.style.borderColor = '#ef4444';
                return;
            }
            // Ganti value select dengan teks custom supaya name="location_name" terkirim benar
            const opt = new Option(customInput.value.trim(), customInput.value.trim(), true, true);
            select.add(opt);
            select.value = customInput.value.trim();
            customInput.removeAttribute('name'); // hindari duplikat
        }
    });

    // Restore state jika old value adalah custom
    (function() {
        const select = document.getElementById('locationName');
        const customInput = document.getElementById('locationNameCustom');
        if (select.value === 'custom' || customInput.value !== '') {
            syncCustomName({ value: 'custom' });
        }
    })();
</script>
@endsection