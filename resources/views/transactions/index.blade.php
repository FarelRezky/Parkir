@extends('layouts.app')

@section('title', 'Transaction')

@section('header_action')
    <div class="d-flex align-items-center">
        <div id="vehicle-selectors" class="me-3">
            @foreach($vehicleTypes as $vt)
                <button type="button" class="btn btn-dark btn-vehicle me-1 fw-bold" data-id="{{ $vt->id }}" onclick="selectVehicle(this, {{ $vt->id }})">
                    {{ strtoupper($vt->jenis) }}
                </button>
            @endforeach
        </div>
        
        <button class="btn text-white fw-bold shadow-sm" style="background-color: #c026d3;" data-bs-toggle="modal" data-bs-target="#enterModal">
            <i class="fa-solid fa-plus"></i> ENTER VEHICLE
        </button>
    </div>
@endsection

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('error'))
    <script>Swal.fire('Error!', '{{ session('error') }}', 'error');</script>
@endif

@if(session('masuk_success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: 'Kendaraan berhasil masuk.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                // Langsung mendownload file PDF secara otomatis
                window.location.href = '/transactions/ticket/{{ session("masuk_success") }}';
            }
        });
    </script>
@endif

@if(session('keluar_success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Fungsi untuk menampilkan alert bersih sesuai gambar
    function showCustomAlert(title, text, icon) {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            confirmButtonText: 'OK',
            confirmButtonColor: '#333' // Warna tombol OK agar netral
        });
    }

    @if(session('masuk_success'))
        Swal.fire({
            title: 'Success!',
            text: 'Kendaraan berhasil masuk.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/transactions/ticket/{{ session("masuk_success") }}';
            }
        });
    @endif

    @if(session('keluar_success'))
        showCustomAlert('Transaction Success', 'Total Bayar : Rp {{ number_format(session("keluar_success")->total_bayar, 0, ",", ".") }}', 'success');
    @endif

    @if(session('error'))
        showCustomAlert('Error!', '{{ session("error") }}', 'error');
    @endif
    
    // Alert untuk sukses simpan di modul lain (Location/VehicleType)
    @if(session('success'))
        showCustomAlert('Success!', '{{ session("success") }}', 'success');
    @endif
</script>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white text-center p-4 shadow-sm" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border-radius: 15px;">
                    <h5 class="mb-1 fw-normal" id="dayName">{{ date('l') }}</h5>
                    <small id="dateFull">{{ date('d F Y') }}</small>
                    <h2 class="mt-3 fw-bold" id="clock">{{ date('H:i:s') }}</h2>
                </div>
            </div>
            
            <div class="col-md-8 d-flex gap-3 overflow-auto">
                @foreach($locations as $loc)
                <div class="card text-center p-3 shadow-sm" style="border: 2px solid #c026d3; border-radius: 15px; min-width: 140px;">
                    <div class="text-white rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px; background-color: #c026d3;">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <h6 class="fw-bold mb-3">{{ $loc->location_name }}</h6>
                    <div class="d-flex justify-content-between text-muted small">
                        <span><i class="fa-solid fa-motorcycle text-success"></i> {{ $loc->max_motorcycle }}</span>
                        <span><i class="fa-solid fa-car text-danger"></i> {{ $loc->max_car }}</span>
                        <span><i class="fa-solid fa-truck text-danger"></i> {{ $loc->max_other }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card p-4 shadow-sm" style="border-radius: 15px; border:none;">
            <form action="/transactions/exit" method="POST">
                @csrf
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">Transaction <span class="text-muted fw-normal">Input Form</span></h5>
                    <button type="submit" class="btn btn-dark fw-bold"><i class="fa-solid fa-arrow-right-from-bracket"></i> EXIT VEHICLE</button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">Ticket Number</label>
                        <input type="text" class="form-control form-control-lg bg-light" name="no_tiket" placeholder="Scan or Type Ticket" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">Police Number</label>
                        <input type="text" class="form-control form-control-lg bg-light" name="no_polisi" placeholder="B 1234 CD">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 h-100 shadow-sm" style="border-radius: 15px; border:none;">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h6 class="mb-0 fw-bold">Tickets</h6>
                <a href="/transactions/list" class="btn btn-outline-secondary btn-sm">VIEW ALL</a>
            </div>
            @foreach($tickets as $t)
            <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded bg-light">
                <div>
                    <small class="text-muted d-block"><i class="fa-regular fa-clock"></i> {{ $t->masuk }}</small>
                    <span class="fw-bold" style="color: #c026d3;">{{ $t->no_tiket }}</span> <br>
                    <span class="badge bg-secondary">{{ $t->no_polisi }}</span>
                </div>
                <a href="/transactions/ticket/{{ $t->id }}" class="text-danger"><i class="fa-solid fa-file-pdf fs-2"></i></a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="modal fade" id="enterModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-white" style="background-color: #c026d3;">
        <h5 class="modal-title"><i class="fa-solid fa-plus"></i> Enter Vehicle</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="/transactions/enter" method="POST">
          @csrf
          <input type="hidden" name="id_jenis" id="selected_jenis" required>
          
          <div class="modal-body">
              <div class="mb-3">
                  <label class="fw-bold">Location</label>
                  <select name="id_lokasi" class="form-select form-select-lg" required>
                      @foreach($locations as $loc)
                        <option value="{{ $loc->id }}">{{ $loc->location_name }}</option>
                      @endforeach
                  </select>
              </div>
              <div class="mb-3">
                  <label class="fw-bold">Police Number</label>
                  <input type="text" name="no_polisi" class="form-control form-control-lg text-uppercase" placeholder="Contoh: B 1234 ABC" required>
              </div>
              <div class="alert alert-info py-2 mt-3">
                  Kategori Terpilih: <b id="display_jenis">...</b>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn text-white fw-bold" style="background-color: #c026d3;">Save & Print Ticket</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
    // Logika Jam Digital
    setInterval(() => {
        let date = new Date();
        document.getElementById('clock').innerHTML = date.toLocaleTimeString('id-ID');
    }, 1000);

    // Logika Pemilihan Kategori Kendaraan
    document.addEventListener("DOMContentLoaded", function() {
        let firstBtn = document.querySelector('.btn-vehicle');
        if(firstBtn) {
            // Pilih tombol pertama (Motorcycle) secara default saat halaman dimuat
            selectVehicle(firstBtn, firstBtn.getAttribute('data-id'));
        }
    });

    function selectVehicle(btnElement, id) {
        // Hapus warna ungu dari semua tombol
        document.querySelectorAll('.btn-vehicle').forEach(btn => {
            btn.classList.remove('text-white');
            btn.style.backgroundColor = '';
            btn.classList.add('btn-dark');
        });
        
        // Beri warna ungu ke tombol yang diklik
        btnElement.classList.remove('btn-dark');
        btnElement.style.backgroundColor = '#c026d3';
        btnElement.classList.add('text-white');
        
        // Simpan ID jenis ke dalam input hidden di form modal
        document.getElementById('selected_jenis').value = id;
        
        // Tampilkan teks kategori di dalam form modal sebagai konfirmasi
        document.getElementById('display_jenis').innerText = btnElement.innerText;
    }
</script>
@endsection