<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Tambahkan background pada html agar terlihat batas struk saat preview di browser */
        html {
            background-color: #f0f0f0; 
            padding: 20px;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            width: 226px; /* Standar printer thermal 58mm */
            font-size: 11px;
            color: #000;
            background: #fff;
            padding: 12px 10px;
            margin: 0 auto; /* Ini yang membuat struk pas di tengah layar */
            box-shadow: 0 0 5px rgba(0,0,0,0.1); /* Opsional: Efek bayangan untuk preview */
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .line-solid {
            border-bottom: 1px solid #000;
            margin: 8px 0;
        }

        .line-dashed {
            border-bottom: 1px dashed #000;
            margin: 8px 0;
        }

        .header-nama {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header-alamat {
            font-size: 9px;
            line-height: 1.4;
            margin-top: 3px;
        }

        .judul-tiket {
            font-size: 13px;
            font-weight: bold;
            margin: 6px 0 2px 0;
            letter-spacing: 1px;
        }

        .sub-judul {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        /* Perbaikan pada baris data agar sejajar sempurna */
        .data-row {
            display: flex;
            margin: 3px 0;
            font-size: 10px;
        }

        .data-label {
            width: 60px; /* Ukuran pas untuk teks 'No Tiket' & 'Tanggal' */
            text-align: left;
        }

        .data-colon {
            width: 15px; /* Titik dua dibuat di tengah jarak */
            text-align: center;
        }

        .data-value {
            flex: 1;
            text-align: left;
        }

        .footer-text {
            font-size: 9px;
            line-height: 1.5;
            text-align: center;
            margin-top: 4px;
            font-weight: bold;
            letter-spacing: 0.3px;
        }
    </style>
</head>
<body>

    {{-- ===== HEADER ===== --}}
    <div class="center">
        <div class="header-nama">SIJA PARKING</div>
        <div class="header-alamat">
            Jl. Raya Karadenan No.7, Karadenan,<br>
            Kec. Cibinong, Kabupaten Bogor, Jawa<br>
            Barat 16111
        </div>
    </div>

    <div class="line-solid"></div>

    {{-- ===== JUDUL TIKET ===== --}}
    <div class="center">
        <div class="judul-tiket">TIKET PARKIR</div>
        <div class="sub-judul">{{ $transaction->location->location_name }}</div>
        <div class="sub-judul">{{ ucfirst($transaction->vehicleType->jenis) }}</div>
    </div>

    <div class="line-dashed"></div>

    {{-- ===== DATA TIKET ===== --}}
    <div class="data-row">
        <div class="data-label">No Tiket</div>
        <div class="data-colon">:</div>
        <div class="data-value">{{ $transaction->no_tiket }}</div>
    </div>
    <div class="data-row">
        <div class="data-label">Tanggal</div>
        <div class="data-colon">:</div>
        <div class="data-value">{{ \Carbon\Carbon::parse($transaction->masuk)->format('Y-m-d H:i:s') }}</div>
    </div>

    <div class="line-dashed"></div>

    {{-- ===== FOOTER ===== --}}
    <div class="footer-text">
        JANGAN MENINGGALKAN TIKET DAN BARANG<br>
        BERHARGA DI DALAM KENDARAAN
    </div>

</body>
</html>