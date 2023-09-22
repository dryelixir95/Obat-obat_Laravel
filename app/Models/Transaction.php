<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions'; // Nama tabel dalam basis data

    protected $fillable = [
        'code_transaction',
        'kasir',
        'jumlah_obat',
        'jumlah_harga',
        'jumlah_bayar',
        'jumlah_kembalian',
        'date',
    ];

    // Atur tanggal sebagai instance dari Carbon
    protected $dates = ['date'];

    // Definisikan relasi dengan tabel ObatTerjual
    public function obatTerjual()
    {
        return $this->hasMany(ObatTerjual::class);
    }
}
