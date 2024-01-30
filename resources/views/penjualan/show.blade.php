@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h3>Penjualan pada hari {{$tanggal}}</h3>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('penjualan.index')}}" class="btn btn-success">Kembali</a>
        </div>
    </div>

    <!-- Tabel untuk menampilkan daftar laporan penjualan -->
    <table class="table">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Nama Pembeli</th>
                <th>Tanggal Transaksi</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $transaksi)
            <tr class="text-center">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $transaksi->Nama_Pembeli }}</td>
                <td>{{ $transaksi->Tanggal_Transaksi }}</td>
                <td>{{ $transaksi->created_by }}</td>
                <td>{{ $transaksi->Total_Harga }}</td>
                <td>
                    <a href="{{ route('transaksi.show', ['id' => $transaksi->ID_Transaksi]) }}" class="btn btn-info btn-sm">Show</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
