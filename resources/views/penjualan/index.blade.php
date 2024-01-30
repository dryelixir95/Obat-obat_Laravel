@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Laporan Penjualan</h1>

    <!-- Tabel untuk menampilkan daftar laporan penjualan -->
    <table class="table">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Tanggal Laporan</th>
                <th>Total Penjualan</th>
                <th>Laba Kotor</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanPenjualans as $laporanPenjualan)
            <tr class="text-center">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $laporanPenjualan->Tanggal_Penjualan }}</td>
                <td>{{ $laporanPenjualan->Total_Penjualan }}</td>
                <td>{{ $laporanPenjualan->Laba_Kotor }}</td>
                <td>
                    <a href="{{ route('penjualan.show', $laporanPenjualan->Tanggal_Penjualan) }}" class="btn btn-info">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
