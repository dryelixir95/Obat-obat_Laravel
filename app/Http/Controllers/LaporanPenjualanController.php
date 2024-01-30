<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanPenjualan;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanController extends Controller
{

    public function index()
    {
        // Mengambil data transaksi berdasarkan tanggal
        $laporanPenjualans = Transaksi::select(
            DB::raw('Tanggal_Transaksi as Tanggal_Penjualan'),
            DB::raw('COUNT(*) as Total_Penjualan'),
            DB::raw('SUM(Total_Harga) as Laba_Kotor')
        )
            ->groupBy('Tanggal_Transaksi')
            ->get();

        return view('penjualan.index', compact('laporanPenjualans'));
    }
    

    public function show($tanggal)
    {
        $transaksi = Transaksi::where('Tanggal_Transaksi', $tanggal)->get();

        return view('penjualan.show', compact('transaksi','tanggal'));

    }

}
