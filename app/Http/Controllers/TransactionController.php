<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\ObatTerjual;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $product = Obat::all();
        return view('transaction.transaction', compact('product'));
    }

    public function transactionAdd(Request $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $jumlahHarga = 0;
            $jumlahObat = 0;
            $transactions = $data['transactions'];
            $myTransaction = Transaction::create([
                'code_transaction' => "",
                'kasir' => Auth::user()->name,
                'jumlah_obat' => $jumlahObat,
                'jumlah_harga' => $jumlahHarga,
                'jumlah_bayar' => $data['jumlah_bayar'],
                'jumlah_kembalian' => 0,
                'date' => now(),
            ]);
            foreach ($transactions as $key => $value) {
                ObatTerjual::create([
                    'transaction_id' => $myTransaction->id,
                    'obat_id' => $value['obat_id'],
                    'jumlah' => $value['jumlah'],
                    'jumlah_harga' => $value['harga'] * $value['jumlah'],
                ]);

                $dataObat = Obat::find($value['obat_id']);
                if ($dataObat) {
                    if ($dataObat->jumlah_obat >= $value['jumlah']) {
                        $dataObat->jumlah_obat -= $value['jumlah'];
                        $dataObat->save();
                    } else {
                        // return response()->json(['error' => '' . $dataObat->nama . 'Stok Habis'], 500);
                    }
                }

                $jumlahObat += $value['jumlah'];
                $jumlahHarga += ($value['harga'] * $value['jumlah']);
            }

            $myTransaction->code_transaction = '00' . Auth::id() . '/' . 'PJ' . '/' . Carbon::now()->format('dmY');
            $myTransaction->jumlah_harga = $jumlahHarga;
            $myTransaction->jumlah_kembalian = $myTransaction->jumlah_bayar - $jumlahHarga;
            $myTransaction->jumlah_obat = $jumlahObat;
            $myTransaction->save();
            DB::commit();

            return response()->json('Berhasil', 200);
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Transaksi gagal: ' . $e->getMessage());
            return response()->json(['error' => 'Transaksi gagal'], 500);
        }
    }
}
