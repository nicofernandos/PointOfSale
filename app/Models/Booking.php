<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'idbooking';
    public $timestamps = false; // karena tabel tidak ada kolom created_at & updated_at

    protected $fillable = [
        'idpelanggan',
        'idkamar',
        'noinvoice',
        'tanggalbooking',
        'tanggalcheckin',
        'waktucheckin',
        'tanggalcheckout',
        'waktucheckout',
        'jumlahorang',
        'nohp',
        'fotoidentitas',
        'hargakamar',
        'denda',
        'grandtotal',
    ];

    // Relasi ke BookingDetail (satu booking punya banyak detail)
    public function bookingDetails()
    {
        return $this->hasMany(Bookingdetail::class, 'idbooking', 'idbooking');
    }

    // Relasi ke Pelanggan (satu booking milik satu pelanggan)
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'idpelanggan', 'idpelanggan');
    }

    // Relasi ke Kamar (satu booking memilih satu kamar)
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'idkamar', 'idkamar');
    }
}