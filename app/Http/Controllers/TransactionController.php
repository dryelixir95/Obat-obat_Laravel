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
        \dd("Haloo");
        $product = Obat::all();
        return view('transaction.transaction', compact('product'));
    }

    public function transactionAdd(Request $request)
    {
        // $data = $request->all();
        $data = [
            'transactions' => [
                [
                    'obat_id' => 1,
                    'harga' => 2000,
                    'jumlah_bayar' => 50000,
                    'jumlah' => 5,
                ],
                [
                    'obat_id' => 2,
                    'harga' => 2000,
                    'jumlah_bayar' => 50000,
                    'jumlah' => 5,
                ],
            ],
            'jumlah_bayar' => 30000
        ];
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
                    $dataObat->jumlah_obat -= $value['jumlah'];
                    $dataObat->save();
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
            dd('berhasill, cek database');
            return view('', compact(['notaData', 'myTransaction']));
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Transaksi gagal: ' . $e->getMessage());
            return response()->json(['error' => 'Transaksi gagal'], 500);
        }
    }
}
