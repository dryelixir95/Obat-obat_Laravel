<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObatTerjual extends Model
{
    use HasFactory;

    protected $table = 'obat_terjual'; // Jika nama tabel berbeda

    protected $fillable = [
        'transaction_id',
        'obat_id',
        'jumlah',
        'jumlah_harga',
    ];

    // Relasi dengan tabel 'transactions'
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relasi dengan tabel 'obats'
    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
