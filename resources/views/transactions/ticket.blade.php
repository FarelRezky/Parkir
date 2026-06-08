<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body { font-family: sans-serif; text-align: center; border: 2px dashed #000; padding: 20px; width: 300px; margin: 0 auto; }
        h2 { margin: 0; color: #c026d3; }
        .details { text-align: left; margin-top: 20px; font-size: 14px; }
        .details p { margin: 5px 0; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .barcode { font-size: 20px; font-weight: bold; letter-spacing: 2px; margin-top: 20px; }
    </style>
</head>
<body>
    <h2>SIJA PARKING</h2>
    <p>Official Parking Ticket</p>
    <hr>
    <div class="details">
        <p><b>Ticket No :</b> {{ $transaction->no_tiket }}</p>
        <p><b>Police No :</b> {{ $transaction->no_polisi }}</p>
        <p><b>Location  :</b> {{ $transaction->location->location_name }}</p>
        <p><b>Type      :</b> {{ strtoupper($transaction->vehicleType->jenis) }}</p>
        <p><b>Time In   :</b> {{ $transaction->masuk }}</p>
    </div>
    <div class="barcode">
        *{{ $transaction->no_tiket }}*
    </div>
    <p style="font-size: 10px; margin-top: 20px;">Please keep this ticket carefully. Do not lose it.</p>
</body>
</html>