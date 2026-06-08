@extends('layouts.app')
@section('title', 'All Transactions')
@section('content')
<div class="card p-4 shadow-sm" style="border: none; border-radius: 10px;">
    <h5 class="mb-4">Transaction <span style="color: #c026d3; font-weight: bold;">Data Table</span></h5>
    <table class="table table-striped text-center align-middle">
        <thead style="color: #c026d3;">
            <tr>
                <th>NO</th>
                <th>TICKET NUMBER</th>
                <th>POLICE NUM</th>
                <th>LOCATION</th>
                <th>VEHICLE TYPE</th>
                <th>TIME IN</th>
                <th>TIME OUT</th>
                <th>TOTAL HOURS</th>
                <th>TOTAL COST</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $t)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="fw-bold" style="color: #c026d3;">{{ $t->no_tiket }}</td>
                <td>{{ $t->no_polisi }}</td>
                <td>{{ $t->location->location_name ?? '-' }}</td>
                <td>{{ $t->vehicleType->jenis ?? '-' }}</td>
                <td>{{ $t->masuk }}</td>
                <td>{{ $t->keluar ?? 'Masih Parkir' }}</td>
                <td>{{ $t->total_jam ?? '-' }}</td>
                <td>{{ $t->total_bayar ? 'Rp '.number_format($t->total_bayar,0,',','.') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection